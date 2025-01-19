<?php
$category_id = get_query_var('custom_id', false);
$count = 0;
$get_params = custom_get_params();
$duration = (!empty($get_params["duration"])) ? $get_params["duration"] : null;
$price_ranges = (!empty($get_params["price"])) ? $get_params["price"] : null;

if (isset($category_id)) {


	// Получаем все дочерние категории для таксономии excursion
	$child_categories = get_terms([
		'taxonomy' => 'excursion',
		'child_of' => $category_id, // ID родительской категории
		'fields' => 'ids', // Получаем только ID категорий
		'hide_empty' => true, // Показывать только категории с постами
	]);

	// Добавляем родительскую категорию к списку
	$categories = array_merge([$category_id], $child_categories);

	// Настройка WP_Query
	$query = new WP_Query([
		'post_type' => 'tours', // Замените на ваш тип записей, если это не стандартный
		'posts_per_page' => -1, // Показывать все посты (или укажите ограничение)
		'tax_query' => [
			[
				'taxonomy' => 'excursion',
				'field' => 'term_id',
				'terms' => $categories,
				'include_children' => false, // Дочерние категории уже включены вручную
			],
		],
	]);

	// Проверяем, есть ли посты
	$posts = [];
	if ($query->have_posts()) : ?>
		<div class="filter">
			<!-- Форма с полем для выбора даты -->
			<div id="response" class="mb-8">
				<div class="grid grid-col-12 xs:grid-cols-12 gap-3 sm:gap-6 w-full mt-1 lg:mt-4 content__tours" id="tours">
					<?php
					while ($query->have_posts()) {
						$query->the_post();
						$fields = get_fields(get_the_ID());
						$returnDuration = true;
						$returnPrice = true;
						$returnGrade = true;

						$json_decode = json_decode(get_post_meta(get_the_ID(), 'tickets', 1));
						$sopr = $fields['id_crm_eks'];
						if(!$json_decode && $sopr){
							$sopr_post = get_posts([
									'post_type' => 'tours',
									'post_status' => 'any',
									'meta_query' => [
											[
													'key' => 'id_crm',
													'value' => $sopr
											]
									]
							])[0];
							$json_decode = json_decode(get_post_meta($sopr_post->ID, 'tickets', 1));
						}
						$m = [
								'января',
								'февраля',
								'марта',
								'апреля',
								'мая',
								'июня',
								'июля',
								'августа',
								'сентября',
								'октября',
								'ноября',
								'декабря'
						];
						$uniqueArray = [];
						$datesArray = [];

						if(is_array($json_decode)) {
							foreach ($json_decode as $item) {
								$json_decode_start_date = explode('.', $item->date);
								$start_dates =  $json_decode_start_date[0]. ' ' .$m[(int)$json_decode_start_date[1] -1];
								$startDates =  $json_decode_start_date[2]. '-' .$json_decode_start_date[1] . '-' . $json_decode_start_date[0];
								if (!in_array($start_dates, $uniqueArray)) {
									$uniqueArray[] = $start_dates;
								}
								$datesArray[] = $startDates;
							}
						}

						// Обработка фильтра по duration

						if(!empty($duration)) {
							$durationRange = convertTime($fields['duration']) ?? 0 ;
							$returnDuration = false;
							foreach ($duration as $duration_range) {
								if ($duration_range) {
									$explode = explode('-', $duration_range);
									if (($durationRange >= (int)$explode[0]) && ($durationRange <= (int)$explode[1])) {
										$returnDuration = true;
									}
								}
							}
						}


						$returnDateForm =  true;

						// Обработка фильтра по price_range
							if (!empty($price_ranges)) {
								$priceRange = get_cost($fields)['cost_sale'] ? get_cost($fields)['cost_sale'] : get_cost($fields)['cost'];
								$returnPrice = false;
								foreach ($price_ranges as $price_range) {
									if ($price_range) {
										list($min_price, $max_price) = explode('-', $price_range);
										$min_price = floatval($min_price);
										$max_price = floatval($max_price);
										if ((int)$priceRange >= (int)$min_price && (int)$priceRange <=  (int)$max_price) {
											$returnPrice = true;
										}
									}
								}
							}

						//запрос
						if (!$returnPrice || !$returnDuration ) {
							continue;
						}

						$posts[] = [
								'post' => get_post(),
								'fields' => $fields,
								'datesArray' => $datesArray,
								'uniqueArray' => $uniqueArray,
								'gradeSort' => '',
								'date' => get_the_date('Y-m-d H:i:s'), // Добавляем дату в формате строки
						];
					}

					foreach ($posts as $postData) {
						$post = $postData['post'];
						setup_postdata($post);

						$fields = $postData['fields'];
						$datesArray = $postData['datesArray'];
						$uniqueArray = $postData['uniqueArray'];

						?>
						<!-- Фильтруемый контент -->
						<div class="card flex flex-col col-span-12 md:col-span-6 bg-white rounded-2xl shadows_custom pb-6" data-cost="<?php echo get_cost($fields)['cost_sale'] ?? get_cost($fields)['cost']; ?>" data-popular="<?php echo ++$count;?>">
							<div class="relative mb-5">
								<a href="<?php echo get_permalink($post->ID); ?>">
									<?php if ( has_post_thumbnail($post->ID) ) : ?>
										<img class="w-full h-[235px] object-cover rounded-t-2xl" src="<?php echo  get_the_post_thumbnail_url($post->ID, 'medium'); ?>" alt="<?php echo get_the_title($post->ID); ?>" loading="lazy">
									<?php else : ?>
										<img class="w-full h-[240px] object-cover rounded-lg bg-gray-300" src="<?php echo get_stylesheet_directory_uri(); ?>/img/woocommerce-placeholder.webp" alt="No image available">
									<?php endif; ?>
								</a>

								<?php if (isset($fields['duration']) && $fields['duration']) : ?>
									<div class="absolute left-[22px] bottom-[18px] flex gap-1 items-center bg-[#FFFFFF] rounded-[6px] h-[28px] px-3">
										<svg xmlns="http://www.w3.org/2000/svg" width="19" height="20" viewBox="0 0 19 20" fill="none">
											<g clip-path="url(#clip0_2001_272)">
												<path d="M9.20006 1.86499C13.6901 1.86499 17.3301 5.50499 17.3301 9.99499C17.3301 14.485 13.6901 18.125 9.20006 18.125C4.71006 18.125 1.06006 14.495 1.06006 10.005C1.06006 5.51499 4.70006 1.86499 9.20006 1.86499Z" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												<path d="M8.47021 5.95508V10.3551" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												<path d="M12.2802 12.555L8.47021 10.355" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
											</g>
											<defs>
												<clipPath id="clip0_2001_272">
													<rect width="18.39" height="18.39" fill="white" transform="translate(0 0.804932)"/>
												</clipPath>
											</defs>
										</svg>
										<div class="text-[#6B7280] leading-0"><?php echo $fields['duration']; ?></div>
									</div>
								<?php endif ?>

								<?php if (isset($fields['sticker']) && $fields['sticker']) : ?>
									<?php
									$bg_stick = (isset($fields['sticker_background']) &&!empty($fields['sticker_background']) ) ? $fields['sticker_background'] : "#D45E5E";
									$bg_color = (isset($fields['sticker_text']) &&!empty($fields['sticker_text']) ) ? $fields['sticker_text'] : "#FFF";
									?>
									<div class="absolute left-[22px] top-[18px] flex items-center rounded-[6px] h-[34px] px-4 text-white" style="background: <?php echo $bg_stick;?>;color:<?php echo $bg_color;?>">
										<div class="leading-0"><?php echo $fields['sticker'];?></div>
									</div>
								<?php endif ?>


								<button class="absolute right-[20px] top-[10px] wish-btn w-12 h-12 flex items-center justify-center group" data-wp-id="<?php echo $post->ID; ?>" aria-label="Добавить в избранное">
									<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect width="36" height="36" rx="18" fill="white"/>
										<path d="M10.318 12.318C8.56066 14.0754 8.56066 16.9246 10.318 18.682L18.0001 26.364L25.682 18.682C27.4393 16.9246 27.4393 14.0754 25.682 12.318C23.9246 10.5607 21.0754 10.5607 19.318 12.318L18.0001 13.6361L16.682 12.318C14.9246 10.5607 12.0754 10.5607 10.318 12.318Z" stroke="#1A1A18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</button>
								<?php if (isset($fields['video_after_gates']) && !empty($fields['video_after_gates'])): ?>
									<button class="absolute right-[65px] top-[10px] wish-btn w-12 h-12 flex items-center justify-center group" aria-label="Смотреть видео">
										<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
											<rect width="36" height="36" rx="18" fill="white"/>
											<path d="M25.1906 19.8041C26.2698 19.2633 26.2698 17.7367 25.1906 17.1959L13.1422 11.1581C12.2051 10.6885 11 11.3234 11 12.4622L11 24.5378C11 25.6766 12.2051 26.3115 13.1422 25.8419L25.1906 19.8041Z" stroke="black" stroke-width="2"/>
										</svg>
									</button>
								<?php endif ?>
							</div>
							<div class="px-4 flex flex-col gap-5 h-full">
								<div class="flex flex-col gap-1 flex-grow relative">
									<a href="<?php echo get_permalink($post->ID); ?>" class="card-title text-[18px] lg:text-[20px] font-bold leading-[1.2] three-lines"><?php echo get_the_title($post->ID); ?></a>
									<div class="date flex items-center gap-2">
										<?php if(count($uniqueArray)) : ?>
											<div class="flex items-center gap-[2px] text-[#6B7280]">
												<svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
													<path d="M6 4.75V1.75M12 4.75V1.75M5.25 7.75H12.75M3.75 15.25H14.25C15.0784 15.25 15.75 14.5784 15.75 13.75V4.75C15.75 3.92157 15.0784 3.25 14.25 3.25H3.75C2.92157 3.25 2.25 3.92157 2.25 4.75V13.75C2.25 14.5784 2.92157 15.25 3.75 15.25Z" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
												<div class="">
													<span><?php echo $uniqueArray[0]; ?></span>
													<?php if(count($uniqueArray)>1) : ?>
														|
														<span><?php echo $uniqueArray[1]; ?></span>
													<?php endif; ?>
												</div>
											</div>
											<?php if(count($uniqueArray)>2) : ?>
												<button aria-expanded="true" data-close-on-click="false" class="dropdown-button text-[#52A6B2]">Другие даты</button>
												<div class="dropdown-menu absolute right-0 z-10 mt-2  w-full max-w-[310px] origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden">

													<div class="item">
														<div class="p-4 calendar-wrapper item" data-dates='<?php echo json_encode($datesArray) ;?>'>
															<button class="close-menu ">Закрыть</button>
															<div class="calendar"></div>
														</div>
													</div>
												</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</div>
								<div class="flex items-center justify-between">
									<div class="price flex flex-col gap-1 text-[#111827]">
										<?php if (get_cost($fields)['cost_sale']) : ?>
											<div class="old_price line-through">
												от <span><?php echo get_cost($fields)['cost']; ?></span> ₽/чел.
											</div>
											<div class="price text-[20px] font-bold">
												от <span><?php echo get_cost($fields)['cost_sale']; ?></span> ₽/чел.
											</div>
										<?php else: ?>
											<div class="price text-[20px] font-bold">
												от <span><?php echo get_cost($fields)['cost']; ?></span> ₽/чел.
											</div>
										<?php endif ?>
									</div>
									<a href="<?php echo get_permalink($post->ID); ?>" class="inline-flex h-11 items-center justify-center font-bold   px-7 sm:px-10 rounded-md bg-[#52A6B2] text-white text-[12px] lg:text-sm">Подробнее</a>
								</div>
							</div>
						</div>
						<?php
					}

					?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<p>Нет записей для выбранной категории.</p>
	<?php endif; wp_reset_postdata();

}
?>
