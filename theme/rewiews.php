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

			<?php

			$args = array(
				'post_type' => 'reviews',
				'posts_per_page' => -1
			);
			$query = new WP_Query( $args );
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$fields = get_fields();
					get_template_part('template-parts/content/content', 'reviews');
				}
			}

			wp_reset_postdata();
			?>

		</div>

		<div class="container mx-auto">
			<form id="rev_form" class="content__form form reviews_form contact_page">
				<h3>Поделитесь впечатлениями</h3>
				<div class="form-wrap">
					<div class="form-left">
						<label class="form__label">
							<input name="name" type="text" required class="popup-input form__input name_field">
							<span class="placeholder-text">Ваше имя<span class="form__star">*</span></span>
						</label>



						<div class="step-field hide text-excurs to">
							<label class="form__label">
								<input class="field form__input name_field" name="excurs" placeholder="Напишите название экскурсии" type="text" />
							</label>
						</div>




						<label class="form__label mb0">
							<input name="email" type="email" class="popup-input form__input name_field">
							<span class="placeholder-text">Ваш Email</span>
						</label>
					</div>

					<div class="form-right">
						<div class="review-rating">
							<div class="label">Оцените ваши впечатления</div>
							<div class="rating-area">
								<input type="radio" id="star-1" name="rating" value="5">
								<label for="star-1" title="Оценка «5»"></label>
								<input type="radio" id="star-2" name="rating" value="4">
								<label for="star-2" title="Оценка «4»"></label>
								<input type="radio" id="star-3" name="rating" value="3">
								<label for="star-3" title="Оценка «3»"></label>
								<input type="radio" id="star-4" name="rating" value="2">
								<label for="star-4" title="Оценка «2»"></label>
								<input type="radio" id="star-5" name="rating" value="1">
								<label for="star-5" title="Оценка «1»"></label>
							</div>
						</div>

						<label class="form__label form__textarea-block">
							<textarea name="message" required class="form__textarea popup-input"></textarea>
							<span class="placeholder-text">Напишите свой отзыв<span class="form__star">*</span></span>
						</label>
					</div>
				</div>


				<div class="form__footer">
					<p style="font-style:italic; font-weight:bold;padding-right:40px;margin:3px 0;"><span class="form__star">*</span><b>Если у вас есть претензии, вместе с отзывом оставьте свой E-mail и с вами свяжется отдел качества. Анонимные жалобы не публикуем.</b></p>
					<div class="flex-form">
						<div id="rev_upload">
							<div class="popup-input-content popup-input-file-content">
								<input type="file" id="browse"  multiple="multiple"  class="popup-input-file photo" name="file[]" accept="image/*">
								<div class="popup-input-file-btn">Прикрепите фотографии</div>
							</div>
							<div id="preview" class="file-name"></div>
						</div>
						<input type="submit" value="Отправить" class="form__submit">
					</div>

				</div>
			</form>
		</div>

	</section>

<?php get_footer(); ?>
