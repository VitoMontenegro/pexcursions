<?php
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php'); 
require_once(ABSPATH . 'wp-admin/includes/image.php');

function custom_rest_filter_posts() {
	register_rest_route('my_namespace/v1', '/filter-posts/', [
		'methods' => 'GET',
		'callback' => 'handle_filter_posts_request',
		'permission_callback' => '__return_true',
	]);
	register_rest_route('custom/v1', '/reviews-form', [
			'methods' => 'POST',
			'callback' => 'handle_reviews_form',
			'permission_callback' => '__return_true',
	]);
}
add_action('rest_api_init', 'custom_rest_filter_posts');


function handle_filter_posts_request(WP_REST_Request $request) {

	$duration = $request->get_param('duration');
	$price_ranges = $request->get_param('price');
	$grade = $request->get_param('grade');
	$posts_per_page = (int) $request->get_param('posts_per_page') ?: 5;
	$page = (int) $request->get_param('page') ?: 1;
	$category_id = $request->get_param('category_id');

	// Получаем дочерние категории
	$child_categories = get_terms([
		'taxonomy'    => 'excursion_category',
		'child_of'    => $category_id,
		'fields'      => 'ids',
		'hide_empty'  => true,
	]);
	$categories = array_merge([$category_id], $child_categories);

	// Инициализация основного массива для WP_Query
	$query_args = [
		'post_type'      => 'excursion',
		'posts_per_page' => $posts_per_page,
		'paged'          => $page,
		'tax_query'      => [
			[
				'taxonomy'         => 'excursion_category',
				'field'            => 'term_id',
				'terms'            => $categories,
				'include_children' => false,
			],
		],
		'meta_query' => ['relation' => 'AND'], // Гарантируем, что все условия применяются к одной записи
	];

	// Обработка фильтра по grade
	if (!empty($grade)) {
		$grade_values = json_decode($grade);
		if (!empty($grade_values)) {
			$grade_query = ['relation' => 'OR'];
			foreach ($grade_values as $value) {
				$grade_query[] = [
					'key'     => 'grade',
					'value'   => ':"' . $value . '"',
					'compare' => 'LIKE',
				];
			}
			$grade_query[] = ['key' => 'grade', 'compare' => 'NOT EXISTS'];
			$grade_query[] = ['key' => 'grade', 'value' => '', 'compare' => 'IS NULL'];
			$query_args['meta_query'][] = $grade_query;
		}
	}

	// Обработка фильтра по duration
	if (!empty($duration)) {
		$duration_values = json_decode($duration);
		if (!empty($duration_values)) {
			$duration_query = ['relation' => 'OR'];
			foreach ($duration_values as $value) {
				$duration_query[] = [
					'key'     => 'duration',
					'value'   => $value,
					'compare' => '=',
				];
			}
			$duration_query[] = ['key' => 'duration', 'compare' => 'NOT EXISTS'];
			$duration_query[] = ['key' => 'duration', 'value' => '', 'compare' => 'IS NULL'];
			$query_args['meta_query'][] = $duration_query;
		}
	}

	// Обработка фильтра по price_range
	if (!empty($price_ranges)) {
		$price_values = json_decode($price_ranges);

		if (!empty($price_values)) {
			$price_queries = ['relation' => 'OR']; // Логическое объединение условий
			foreach ($price_values as $price_range) {
				$min_price = $max_price = null;
				if ($price_range) {
					list($min_price, $max_price) = explode('-', $price_range);
					$min_price = floatval($min_price);
					$max_price = floatval($max_price);
				}

				if ($min_price !== null && $max_price !== null) {
					$price_queries[] = [
						'key'     => 'discount_price',
						'value'   => [$min_price, $max_price],
						'compare' => 'BETWEEN',
						'type'    => 'NUMERIC',
					];
				}
			}

			// Добавляем условие на отсутствие значения
			$price_queries[] = [
				'key'     => 'discount_price',
				'compare' => 'NOT EXISTS',
			];

			// Добавляем условие на пустое значение
			$price_queries[] = [
				'key'     => 'discount_price',
				'value'   => '',
				'compare' => 'IS NULL',
			];

			// Добавляем условия в основной meta_query
			$query_args['meta_query'][] = $price_queries;
		}
	}

	//запрос
	$query = new WP_Query($query_args);
	ob_start();
	// Формируем ответ
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			$fields = get_fields();
			?>
			<div class="flex flex-col gap-4">
				<a href="<?php echo get_permalink(); ?>" class="relative">
					<img class="rounded-xl w-full" src="<?php echo $fields['gallery'][0]['sizes']['medium_large']; ?>" alt="<?php echo $fields['gallery'][0]['name']; ?>">
					<div class="absolute left-3 bottom-4 flex gap-1 items-center">
						<span class="text-white font-600 leading-100"><?php echo $fields['duration']; ?></span>
					</div>
				</a>
				<div class="flex flex-wrap text-xl text-global-luckypush font-400">
					<span class="mr-1"><?php echo $fields['price']; ?> / <?php echo $fields['discount_price']; ?></span>
				</div>
				<button class="wish-btn content__tour__wish-btn group" data-wp-id="<?php echo get_the_ID(); ?>">
					<div class="icon">
						<svg class="w-6 h-6 fill-current text-[#A5A5A5] group-[.active]:text-red-600">
							<path class="icon-path" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
						</svg>
					</div>
				</button>
				<?php if (!empty($fields['video_after_gates'])): ?>
					<span class="has_video" data-ll-status="observed">
                    <svg height="100%" version="1.1" viewBox="0 0 68 48" width="35">
                        <path class="" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"></path>
                        <path d="M 45,24 27,14 27,34" fill="#fff"></path>
                    </svg>
                </span>
				<?php endif; ?>
				<a href="<?php echo get_permalink(); ?>" class="text-3xl font-700 leading-100"><?php echo get_the_title(); ?></a>
			</div>
			<?php
		}
	} else {
		echo '<p>Нет записей для выбранных фильтров.</p>';
	}
	echo ob_get_clean();

}


function handle_reviews_form(WP_REST_Request $request) {
	$params = $request->get_params();
	$files = $_FILES['file'] ?? null;

	$recepient = 'testdev@kometatek.ru';
	$sitename = "Flagman";
	$name = sanitize_text_field($params["name"]);
	$email = sanitize_email($params["email"]);
	$text = sanitize_textarea_field($params["message"]);

	$excurs = isset($params["excurs"]) ? wp_strip_all_tags($params["excurs"]) : '';
	$rating = isset($params["rating"]) ? (int)$params["rating"] : 0;

	$message = "Дата: " . date('d/m/Y') . "<br/><br/>\r\n";
	$message .= "Имя: " .  $name . "<br/><br/>\r\n";
	$message .= "Экскурсия: " .  $excurs . "<br/><br/>";
	$message .= "Рейтинг: " .  $rating . "<br/><br/>";
	$message .= "Телефон или Email: " .  $email . "<br/><br/>";
	$message .= "Сообщение: " .  $text . "<br/><br/>";

	$pagetitle = "Новый отзыв с сайта \"$sitename\"";

	// Создание записи "Отзыв"
	$post_data = [
			'post_title'   => $name,
			'post_content' => $text,
			'post_status'  => 'pending',
			'post_author'  => 1,
			'post_type'    => 'reviews',
	];
	$post_id = wp_insert_post($post_data);

	// Обработка файлов
	if ($files) {
		$uploaded_files = array_map('RemapFilesArray',
				$files['name'],
				$files['type'],
				$files['tmp_name'],
				$files['error'],
				$files['size']
		);

		$gallery = [];
		
		foreach ($uploaded_files as $file) {
			if($file["name"] && $file["type"] && $file["tmp_name"]) {
				$attachment = my_update_attachment($file, $post_id);
				if (isset($attachment['attach_id'])) {
					$gallery[] = $attachment['attach_id'];
				}
			}
		}

		// Сохранение дополнительных полей (ACF или meta)
		update_field('field_5fad894783054', $gallery, $post_id); // Поле для галереи
	}

	// Сохранение дополнительных данных
	update_field('field_5fad895583055', $excurs, $post_id);
	update_field('field_5fad897183057', $rating, $post_id);
	update_field('field_612cc6d2ad914', $email, $post_id);

	// Добавление ссылки на запись в сообщение
	$message .= "Ссылка на отзыв: https://parus-peterburg.ru/wp-admin/post.php?post=$post_id&action=edit<br/><br/>";

	// Возвращаем ответ REST API
	return rest_ensure_response([
			'success' => true,
			'message' => 'Отзыв успешно отправлен!',
			'post_id' => $post_id,
	]);
}

// Вспомогательная функция для обработки массива файлов
function RemapFilesArray($name, $type, $tmp_name, $error, $size) {
    return array(
        'name' => $name,
        'type' => $type,
        'tmp_name' => $tmp_name,
        'error' => $error,
        'size' => $size,
    );
}

// Вспомогательная функция для загрузки вложений

function my_update_attachment($f,$pid,$t='',$c='') {
  wp_update_attachment_metadata( $pid, $f );
  if( !empty( $f['name'] )) { //New upload
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    $override['test_form'] = false;
    $file = wp_handle_upload( $f, $override );

    if ( isset( $file['error'] )) {
      return new WP_Error( 'upload_error', $file['error'] );
    }

    $file_type = wp_check_filetype($f['name'], array(
      'jpg|jpeg' => 'image/jpeg',
      'gif' => 'image/gif',
      'png' => 'image/png',
    ));
    if ($file_type['type']) {
      $name_parts = pathinfo( $file['file'] );
      $name = $f['name'];
      $type = $file['type'];
      $title = $t ? $t : $name;
      $content = $c;

      $attachment = array(
        'post_title' => $title,
        'post_type' => 'attachment',
        'post_content' => $content,
        'post_parent' => $pid,
        'post_mime_type' => $type,
        'guid' => $file['url'],
      );


      foreach( get_intermediate_image_sizes() as $s ) {
        $sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => true );
        $sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
        $sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
        $sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options
      }

      $sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );

      foreach( $sizes as $size => $size_data ) {
        $resized = image_make_intermediate_size( $file['file'], $size_data['width'], $size_data['height'], $size_data['crop'] );
        if ( $resized )
          $metadata['sizes'][$size] = $resized;
      }

      $attach_id = wp_insert_attachment( $attachment, $file['file'] /*, $pid - for post_thumbnails*/);

      if ( !is_wp_error( $attach_id )) {
        $attach_meta = wp_generate_attachment_metadata( $attach_id, $file['file'] );
        wp_update_attachment_metadata( $attach_id, $attach_meta );
      }

   return array(
  'pid' =>$pid,
  'url' =>$file['url'],
  'file'=>$file,
  'attach_id'=>$attach_id
   );
    }
  }
}

