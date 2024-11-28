<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package _tw
 */
get_header();
?>


	<section id="primary">
		<main id="main">
			<div class="container mx-auto"><?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content/content', 'page' );

					// If comments are open, or we have at least one comment, load
					// the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}

				endwhile; // End of the loop.
				?>
			</div>
			<?php
				$current_category =  get_term_by('slug', 'ekskursii-peterburg','excursion');
				$category_id = $current_category->term_id;
				my_custom_template($category_id, 'template-parts/content/content-loop-excursion');
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
