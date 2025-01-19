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

$sub = array(".01." => " января ", ".02." => " февраля ",
		".03." => " марта ", ".04." => " апреля ", ".05." => " мая ", ".06." => " июня ",
		".07." => " июля ", ".08." => " августа ", ".09." => " сентября ",
		".10." => " октября ", ".11." => " ноября ", ".12." => " декабря ", "2022" => '2022', '2023' => '2023', '2024'=>'2024', '2025'=>'2025','2026'=>'2026','00:00'=>'');
?>
<div class="flex flex-col gap-3 p-4 sm:p-8 bg-white rounded-xl reviews_slider__item">
	<div class="header">
		<div class="text-[#393488] text-[18px] font-bold leading-normal"><?php the_title(); ?></div>
		<div class="text-[14px] text-[#373F41] opacity-50">
			<?php
			$text = '';
			if (isset($fields['date'])) {
				$text .= trim(strtr($fields['date'], $sub));
			}
			if(isset($fields['date']) && ( (isset($fields['excursion']) && $fields['excursion']) || (isset($fields['excursion_obj']) && $fields['excursion_obj']))) {
				$text .= ', ';
			}
			if(isset($fields['excursion']) && $fields['excursion']) {
				$text .= $fields['excursion'];
			} elseif(isset($fields['excursion_obj']) && $fields['excursion_obj']) {
				$text .= get_the_title($fields['excursion_obj']);
			}
			echo $text;
			?>
		</div>
	</div>
	<div class="text-[14px] text-[#373F41] reviews_slider__text_wrapper">
		<div class="reviews_slider__text lines fifteen-lines mb-2">
			<?php the_content(); ?>
		</div>
	</div>

	<?php
	$images = isset($fields['gallery']) && !empty($fields['gallery']) ? $fields['gallery'] : [];
	$size = 'full'; // (thumbnail, medium, large, full или произвольный размер)
	?>
	<?php if( $images ): ?>
		<div class="grid grid-cols-4 sm:grid-cols-5 gap-[10px]">
			<?php $counter = 0; ?>
			<?php foreach( $images as $image ): ?>
				<a data-fancybox="gallery<?php echo get_the_ID(); ?>" href="<?php echo $image['url']; ?>" class="gallery-item <?php echo ($counter === 3) ? 'hidden sm:block' : ''; ?> <?php echo ($counter >= 4) ? 'hidden' : ''; ?>">
					<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" class="w-[64px] h-[64px] object-cover rounded-md">
				</a>
				<?php $counter++; ?>
			<?php endforeach; ?>
			<?php if( $counter > 4 ): ?>
				<a data-fancybox="gallery<?php echo get_the_ID(); ?>" href="<?php echo $images[0]['url']; ?>" class="bg-[#F2F1FA] w-[64px] h-[64px] flex items-center justify-center rounded-md">еще...</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

</div>
