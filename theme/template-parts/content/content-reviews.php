<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

$fields = get_fields();
$date = ($fields['date_reviews']) ?? get_the_date('j F Y');

?>
<div class="content__review review">
	<?php if ($fields['excursion']): ?>
		<div class="review__author">
			<div class="excursion">
				<div class="excursion-list">Экскурсия</div>
				<?php $excursionarr = explode(',', $fields['excursion']); ?>
				<ul>
					<?php foreach ($excursionarr as $excursion): ?>
						<?php if ($excursion): ?>
							<li><?php echo $excursion; ?></li>
						<?php endif ?>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
	<?php endif ?>

	<div class="review__text">
		<p class="review__author-name"><?php the_title(); ?></p>
		<p class="review__author-date"><?php echo $date?></p>
		<?php $rating = $fields['rating'] ? 20*$fields['rating'] : 0 ?>
		Рейтинг: <?php echo $rating; ?>%
		<div class="rating__stars">
			<span class="rating__stars-empty" data-ll-status="observed"></span>
			<span class="rating__stars-fill" style="width: <?php echo $rating; ?>%" data-ll-status="observed"></span>
		</div>
		<?php if($fields['review_img']): ?>
			<a class="review_slider--img_href" rel="gall<?php echo get_the_ID(); ?>" href="<?php echo $fields['review_img']['url']?>">
				<img src="<?php echo $fields['review_img']['sizes']['thumbnail']?>" alt="">
			</a>
		<?php else: ?>
			<?php the_content(); ?>
		<?php endif; ?>

		<?php
			$images = $fields['gallery'];
			$size = 'full'; // (thumbnail, medium, large, full или произвольный размер)
		?>
		<?php if( $images ): ?>
			<div class="flex gap-4">
				<?php foreach( $images as $image ): ?>
					<div>
						<a data-fancybox="gallery<?php echo get_the_ID(); ?>" href="<?php echo $image['url']; ?>">
							<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ($fields['review_answer'] && $fields['review_answer_show'] !== true): ?>
			<div class="review_answer_title">Официальный ответ<span class="sm-hide"> Администрации</span></div>
			<div class="review_answer_txt">
				<?php echo $fields['review_answer']; ?>
			</div>
		<?php endif ?>
////////////////////////////////
	</div>
</div>
