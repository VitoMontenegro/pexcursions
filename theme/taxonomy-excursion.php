<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default. Please note that
 * this is the WordPress construct of pages: specifically, posts with a post
 * type of `page`.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */
$current_term = get_queried_object();
get_header();
?>

	<section id="primary">
		<main id="main">
			<?php
				if ($current_term && isset($current_term->term_id)) {
					$category_id = $current_term->term_id;
					my_custom_template($category_id, 'template-parts/content/content-loop-excursion');
				}
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
