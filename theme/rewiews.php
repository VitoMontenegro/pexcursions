<?php
/**
 * Страница с кастомным шаблоном (page-custom.php)
 * @package WordPress
 * Template Name: Отзывы
 */



get_header();

?>
	<section class="content content--reviews">
		<div class="container mx-auto">
			<h1><?php the_title() ?></h1>

			<?php the_content(); ?>
		</div>
	</section>

<?php get_footer(); ?>
