<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package _tw
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function tw_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'tw_pingback_header' );

/**
 * Changes comment form default fields.
 *
 * @param array $defaults The default comment form arguments.
 *
 * @return array Returns the modified fields.
 */
function tw_comment_form_defaults( $defaults ) {
	$comment_field = $defaults['comment_field'];

	// Adjust height of comment form.
	$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

	return $defaults;
}
add_filter( 'comment_form_defaults', 'tw_comment_form_defaults' );

/**
 * Filters the default archive titles.
 */
function tw_get_the_archive_title() {
	if ( is_category() ) {
		$title = __( 'Category Archives: ', 'tw' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_tag() ) {
		$title = __( 'Tag Archives: ', 'tw' ) . '<span>' . single_term_title( '', false ) . '</span>';
	} elseif ( is_author() ) {
		$title = __( 'Author Archives: ', 'tw' ) . '<span>' . get_the_author_meta( 'display_name' ) . '</span>';
	} elseif ( is_year() ) {
		$title = __( 'Yearly Archives: ', 'tw' ) . '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'tw' ) ) . '</span>';
	} elseif ( is_month() ) {
		$title = __( 'Monthly Archives: ', 'tw' ) . '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'tw' ) ) . '</span>';
	} elseif ( is_day() ) {
		$title = __( 'Daily Archives: ', 'tw' ) . '<span>' . get_the_date() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$cpt   = get_post_type_object( get_queried_object()->name );
		$title = sprintf(
			/* translators: %s: Post type singular name */
			esc_html__( '%s Archives', 'tw' ),
			$cpt->labels->singular_name
		);
	} elseif ( is_tax() ) {
		$tax   = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf(
			/* translators: %s: Taxonomy singular name */
			esc_html__( '%s Archives', 'tw' ),
			$tax->labels->singular_name
		);
	} else {
		$title = __( 'Archives:', 'tw' );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'tw_get_the_archive_title' );

/**
 * Determines whether the post thumbnail can be displayed.
 */
function tw_can_show_post_thumbnail() {
	return apply_filters( 'tw_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Returns the size for avatars used in the theme.
 */
function tw_get_avatar_size() {
	return 60;
}

/**
 * Create the continue reading link
 *
 * @param string $more_string The string shown within the more link.
 */
function tw_continue_reading_link( $more_string ) {

	if ( ! is_admin() ) {
		$continue_reading = sprintf(
			/* translators: %s: Name of current post. */
			wp_kses( __( 'Continue reading %s', 'tw' ), array( 'span' => array( 'class' => array() ) ) ),
			the_title( '<span class="sr-only">"', '"</span>', false )
		);

		$more_string = '<a href="' . esc_url( get_permalink() ) . '">' . $continue_reading . '</a>';
	}

	return $more_string;
}

// Filter the excerpt more link.
add_filter( 'excerpt_more', 'tw_continue_reading_link' );

// Filter the content more link.
add_filter( 'the_content_more_link', 'tw_continue_reading_link' );

/**
 * Outputs a comment in the HTML5 format.
 *
 * This function overrides the default WordPress comment output in HTML5
 * format, adding the required class for Tailwind Typography. Based on the
 * `html5_comment()` function from WordPress core.
 *
 * @param WP_Comment $comment Comment to display.
 * @param array      $args    An array of arguments.
 * @param int        $depth   Depth of the current comment.
 */

function tw_html5_comment( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';

	$commenter          = wp_get_current_commenter();
	$show_pending_links = ! empty( $commenter['comment_author'] );

	if ( $commenter['comment_author_email'] ) {
		$moderation_note = __( 'Your comment is awaiting moderation.', 'tw' );
	} else {
		$moderation_note = __( 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.', 'tw' );
	}
	?>
	<<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $comment->has_children ? 'parent' : '', $comment ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<?php
					$comment_author = get_comment_author_link( $comment );

					if ( '0' === $comment->comment_approved && ! $show_pending_links ) {
						$comment_author = get_comment_author( $comment );
					}

					printf(
						/* translators: %s: Comment author link. */
						wp_kses_post( __( '%s <span class="says">says:</span>', 'tw' ) ),
						sprintf( '<b class="fn">%s</b>', wp_kses_post( $comment_author ) )
					);
					?>
				</div><!-- .comment-author -->

				<div class="comment-metadata">
					<?php
					printf(
						'<a href="%s"><time datetime="%s">%s</time></a>',
						esc_url( get_comment_link( $comment, $args ) ),
						esc_attr( get_comment_time( 'c' ) ),
						esc_html(
							sprintf(
							/* translators: 1: Comment date, 2: Comment time. */
								__( '%1$s at %2$s', 'tw' ),
								get_comment_date( '', $comment ),
								get_comment_time()
							)
						)
					);

					edit_comment_link( __( 'Edit', 'tw' ), ' <span class="edit-link">', '</span>' );
					?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' === $comment->comment_approved ) : ?>
				<em class="comment-awaiting-moderation"><?php echo esc_html( $moderation_note ); ?></em>
				<?php endif; ?>
			</footer><!-- .comment-meta -->

			<div <?php tw_content_class( 'comment-content' ); ?>>
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<?php
			if ( '1' === $comment->comment_approved || $show_pending_links ) {
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply">',
							'after'     => '</div>',
						)
					)
				);
			}
			?>
		</article><!-- .comment-body -->
	<?php
}

// add optionpage
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
			'page_title'    => 'Основные настройки сайта',
			'menu_title'    => 'Настройки сайта',
			'menu_slug'     => 'theme-general-settings',
	));
}

# Добавляет SVG в список разрешенных для загрузки файлов.
function svg_upload_allow( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}
add_filter( 'upload_mimes', 'svg_upload_allow' );

# Исправление MIME типа для SVG файлов.
function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ){

	// WP 5.1 +
	if( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) ){
		$dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
	}
	else {
		$dosvg = ( '.svg' === strtolower( substr( $filename, -4 ) ) );
	}

	// mime тип был обнулен, поправим его
	// а также проверим право пользователя
	if( $dosvg ){

		// разрешим
		if( current_user_can('manage_options') ){

			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		// запретим
		else {
			$data['ext']  = false;
			$data['type'] = false;
		}

	}

	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );


function register_custom_taxonomy() {
	/**
	 * Таксономия: Категория экскурсий.
	 */
	register_taxonomy('excursion', 'tours', [
			'hierarchical' => true, // Позволяет создавать подкатегории
			'labels' => [
					'name'              => 'Виды экскурсий',
					'singular_name'     => 'Вид экскурсий',
					'search_items'      => 'Поиск видов экскурсий',
					'all_items'         => 'Все виды экскурсий',
					'edit_item'         => 'Редактировать вид экскурсий',
					'update_item'       => 'Обновить вид экскурсий',
					'add_new_item'      => 'Добавить новый вид экскурсий',
					'new_item_name'     => 'Новое имя вида экскурсий',
					'menu_name'         => 'Виды экскурсий',
					'view_item '        => 'Просмотр вида экскурсий',
					'parent_item'       => 'Родительский вид экскурсий',
					'parent_item_colon' => 'Родительский вид экскурсий:',
					'back_to_items'     => '← Назад к категории',
			],
			'show_ui'       => true,
			'query_var'     => true,
			"show_admin_column" => true,
			'rewrite' => [
					'slug' => 'excursion'
			],
			'show_in_rest' => true,
			'rest_base' => 'excursion'
	]);
}
add_action('init', 'register_custom_taxonomy');


function register_custom_post_type() {
	/**
	 * Post Type: Экскурсии.
	 */
	register_post_type('tours', [
			'labels' => [
					'name' => 'Экскурсии',
					'singular_name' => 'Экскурсии',
					'add_new' => 'Добавить экскурсию',
					'add_new_item' => 'Добавить новую экскурсию',
					'edit_item' => 'Редактировать экскурсию',
					'new_item' => 'Новая экскурсия',
					'all_items' => 'Все экскурсии',
					'view_item' => 'Просмотр экскурсии на сайте',
					'search_items' => 'Искать экскурсию',
					'not_found' =>  'Экскурсия не найдена.',
					'not_found_in_trash' => 'В корзине нет экскурсий.',
					'menu_name' => 'Экскурсии'
			],
			'public' => true,
			'show_ui' => true,
			'has_archive' => true,
			'menu_position' => 20,
			'supports' => array( 'title', 'editor', 'comments', 'author', 'thumbnail','custom-fields', 'revisions'),
			'menu_icon' => 'dashicons-admin-site-alt',
			'taxonomies'          => array( 'excursion' ),
			'show_in_rest' => true,
			'rest_base'             => 'tours',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
	]);

	/**
	 * Post Type: Отзывы.
	 */
	register_post_type('reviews', [
			'labels' => [
					"name" => "Отзывы",
					"singular_name" => "Отзыв",
					"menu_name" => "Отзывы",
					"all_items" => "Все отзывы",
					"add_new" => "Добавить отзыв",
					"add_new_item" => "Добавить новый отзыв",
					"edit_item" => "Редактировать отзыв",
					"new_item" => "Новый отзыв",
					"view_item" => "Смотреть отзыв",
					"view_items" => "Смотреть отзывы",
					"search_items" => "Найти отзыв",
					"not_found" => "Отзывы не найдены",
					"not_found_in_trash" => "Отзывы не найдены в корзине",
					"featured_image" => "Изображение",
					"set_featured_image" => "Установить изображение",
					"remove_featured_image" => "Удалить изображение",
					"use_featured_image" => "Использовать как изображение к отзыву",
					"archives" => "Архив отзывов",
					"insert_into_item" => "Вставить в отзыв",
					"uploaded_to_this_item" => "Загружено к этому отзыву",
					"filter_items_list" => "Фильтровать список отзывов",
					"items_list_navigation" => "Навигация по списку отзывов",
					"items_list" => "Список отзывов",
					"attributes" => "Атрибуты отзыва",
					"name_admin_bar" => "Отзыв",
					"parent_item_colon" => "Родительский отзыв",
			],
			"description" => "",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"delete_with_user" => false,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"has_archive" => false,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"exclude_from_search" => false,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"rewrite" => [
				"slug" => "reviews", "with_front" => true
			],
			"query_var" => true,
			"supports" => ["title", "editor"]
	]);

	/**
	 * Post Type: Акции.
	 */
	register_post_type( "promos", [
			"labels" => [
				"name" => "Акции",
				"singular_name" => "Акция",
				"menu_name" => "Акции",
				"all_items" => "Все акции",
				"add_new" => "Добавить акцию",
				"add_new_item" => "Добавить новую акцию",
				"edit_item" => "Редактировать акцию",
				"new_item" => "Новая акция",
				"view_item" => "Смотреть акцию",
				"view_items" => "Смотреть акции",
				"search_items" => "Найти акцию",
				"not_found" => "Акции не найдены",
				"not_found_in_trash" => "Акции не найдены в корзине",
				"parent" => "Родительская акция",
				"featured_image" => "Картинка к этой акции",
				"set_featured_image" => "Установить картинку к этой акции",
				"remove_featured_image" => "Удалить картинку акции",
				"use_featured_image" => "Использовать как изображение к акции",
				"archives" => "Архивы акций",
				"insert_into_item" => "Вставить в акцию",
				"uploaded_to_this_item" => "Загружено к этой акции",
				"filter_items_list" => "Фильтровать список акций",
				"items_list_navigation" => "Навигация по списку акций",
				"items_list" => "Список акций",
				"attributes" => "Атрибуты акции",
				"name_admin_bar" => "Акция",
				"parent_item_colon" => "Родительская акция",
			],
			"description" => "",
			"public" => true,
			"publicly_queryable" => true,
			"show_ui" => true,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"rest_namespace" => "wp/v2",
			"has_archive" => true,
			"show_in_menu" => true,
			"show_in_nav_menus" => true,
			"delete_with_user" => false,
			"exclude_from_search" => false,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"can_export" => true,
			"rewrite" => [ "slug" => "promos", "with_front" => true ],
			"query_var" => true,
			"supports" => [ "title", "editor", "thumbnail" ],
			"show_in_graphql" => false
	]);
}
add_action('init', 'register_custom_post_type');


// скрыть в меню от пользоватля с ролью 'author'
function tw_remove_menu_items() {
	if( current_user_can( 'author' ) ):
		remove_menu_page( 'edit.php?post_type=promos' );
		remove_menu_page( 'edit.php?post_type=reviews' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'tools.php' );
	endif;
}
add_action( 'admin_menu', 'tw_remove_menu_items' );


//Вывод массива рейтингов экскурсий [excursion] => rating
function get_rating_excursion () {

	$arr = [];

	$args = array(
			'posts_per_page' => '999',
			'post_type' => 'reviews'
	);
	$query = new WP_Query( $args );

	while ( $query->have_posts() ) {
		$query->the_post();


		if (get_field('excursion') &&  get_field('rating')) {
			$excurs_arr = explode(',', get_field('excursion'));
			foreach ($excurs_arr as  $value) {
				$arr[$value][] = get_field('rating');
			}
		}

	}
	wp_reset_postdata();

	foreach ($arr as $key => $value) {
		if (!empty($key)) {
			$slice = array_slice($value, 0, 3);
			$arr[$key] = array_sum($slice) / sizeof($slice);
		} else {
			unset($arr[$key]);
		}
	}

	return $arr;
}


function getYoutubeEmbedUrl($url) {
	$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
	preg_match($pattern, $url, $matches);
	return (isset($matches[1])) ? $matches[1] : false;
}

function getDzenSrc($url) {
	$pattern = '/(https.*)(")/U';
	preg_match($pattern, $url, $matches);

	return (isset($matches[1])) ? $matches[1] : false;
}

function correctTime($time){
	if (stripos($time,'h')>-1) return $time;
	if (stripos($time,'мин')>-1) return $time;
	if (stripos($time,'час')===false&&stripos($time,'-')===false){
		if (stripos($time,':')>-1){
			$arr_time = explode(':',$time);
			if ($arr_time[1]=='30')
				$time = $arr_time[0]+0.5;
			else
				$time = $arr_time[0];
		}
		if ($time == 1.5)
			$time = $time.' часа';
		else
			$time = $time.' '.getNumEnding($time, array('час', 'часа', 'часов'));
	}
	return $time;
}


function getNumEnding($number, $endingArray) {
	$number = (int)$number;
	$number = $number % 100;
	if ($number>=11 && $number<=19) {
		$ending=$endingArray[2];
	}
	else {
		$i = $number % 10;
		switch ($i)
		{
			case (1): $ending = $endingArray[0]; break;
			case (2):
			case (3):
			case (4): $ending = $endingArray[1]; break;
			default: $ending=$endingArray[2];
		}
	}
	return $ending;
}
function remove_all_acf_meta_boxes() {
	?>
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function() {
			// Скрыть все метабоксы ACF в редакторе
			var metaBoxes = document.querySelectorAll('.edit-post-meta-boxes-main__presenter');
			metaBoxes.forEach(function(metaBox) {
				metaBox.style.display = 'none';
			});

			// Сделать контент редактируемым на всю ширину
			var content = document.querySelector('.edit-post-layout__content');
			if (content) {
				content.style.width = '100%';
			}
		});
	</script>
	<?php
}
add_action('admin_head', 'remove_all_acf_meta_boxes');

function my_custom_template($id, $part) {
	set_query_var('custom_id', $id);
	get_template_part($part);
}
