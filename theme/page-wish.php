<?php
/**
 * Template Name: Страница Избранное
 */

get_header();
?>

<section class="content content--bus content--wishlist">
	<div class="container mx-auto">
		<h1><?php the_title();?></h1>

		<div class="content__tours" id="tours">
		</div>

	</div>
</section>
<?php get_footer(); // подключаем footer.php ?>

