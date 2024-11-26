<?php
/**
 * Страница таксономии туров
 * @package WordPress
 * @subpackage your-clean-template-3
 */


$current_term = get_queried_object();
if ($current_term && isset($current_term->slug) && $current_term->slug === 'ekskursii-peterburg') {
	wp_redirect('/' , 301);
	exit();
}

get_header();
?>

	<section id="primary">
		<main id="main">
			<?php get_template_part( 'template-parts/layout/breadcrumbs', 'content' ); ?>

			<div class="container mx-auto mt-4">
				<div class="grid grid-cols-12 gap-4">
					<div class="col-span-3 border-2 p-4">
						<?php get_sidebar(); ?>
					</div>
					<div class="col-span-9">
						<div class="flex flex-col">
							<div class="main_nav">

								<?php $categories = get_nested_categories('excursion_category'); ?>
								<?php if (!empty($categories)) : ?>
									<div class="flex justify-between">
										<?php foreach ($categories as $category) : ?>
											<div>
												<a href="<?php echo esc_url($category['link']) ?>">
													<?php echo esc_html($category['name'])?>
												</a>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="grid grid-cols-4 gap-6 w-full mt-5"  id="posts-container">
								<?php
									if ($current_term && isset($current_term->term_id)) {
										$category_id = $current_term->term_id;
										my_custom_template($category_id, 'template-parts/content/content-loop-excursion');
									}
								?>
							</div>
							<div id="pagination">
								<!-- Пагинация будет обновляться AJAX-ом -->
							</div>
						</div>
					</div>
				</div>
			</div>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();


