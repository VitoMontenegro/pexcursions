
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

get_header();
$fields = get_fields();
?>


<section id="primary">
	<main id="main">

	<section id="response" class="content content--group-tour-page " itemprop="event" itemscope itemtype="http://schema.org/Event">
		<meta itemprop="eventAttendanceMode" content="offline">
		<meta itemprop="inLanguage" content="ru-RU">

		<div class="single_tour">
			<h1 class="mb-6"><?php echo $fields['h1'] ?? get_the_title(); ?></h1>

			<div class="grid grid-cols-12 gap-8">
				<div class="lg:col-span-8 col-span-12">
					<div class="relative">
						<div class="swiper_tour">
							<div class="swiper mySwiper2 w-full h-[480px] mx-auto mb-4">
								<div class="swiper-wrapper">
									<?php foreach ($fields["galery"] as $image) : ?>
										<div class="swiper-slide text-center flex justify-center items-center bg-cover">
											<img src="<?php echo $image["sizes"]["medium_large"] ?>" class="block w-full h-full object-cover" />
										</div>
									<?php endforeach; ?>
								</div>
								<div class="swiper-button-next"></div>
								<div class="swiper-button-prev"></div>
							</div>
							<div class="swiper mySwiper w-full h-[120px] mx-auto">
								<div class="swiper-wrapper">
									<?php foreach ($fields["galery"] as $image) : ?>
										<div class="swiper-slide w-[25%] h-full text-center flex justify-center items-center bg-cover cursor-pointer">
											<img src="<?php echo $image["sizes"]["medium_large"] ?>" class="block w-full h-full object-cover" />
										</div>
									<?php endforeach; ?>

								</div>
							</div>
						</div>
						<button class="wish-btn absolute top-4 left-4 w-[35px] h-[35px] flex justify-center items-center rounded-full bg-white group z-10" data-wp-id="<?php echo get_the_ID(); ?>">
							<div class="icon">
								<svg class="w-6 h-6 fill-current text-[#A5A5A5] group-[.active]:text-red-600">
									<path class="icon-path" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
								</svg>
							</div>
						</button>
					</div>
					<div class="content_single mt-9">

						<?php if ( (isset($fields['video_after_gates']) && $fields['video_after_gates']) || (isset($fields['video_after_gates_dzen']) && $fields['video_after_gates_dzen']) ): ?>

							<div class="tours-wrap mb-8">
								<div class="tours-content" id="after_gates_wrap">
									<h2 class="mt-3 mb-6">Видео экскурсии</h2>
									<?php if((isset($fields['video_after_gates_dzen']) && $fields['video_after_gates_dzen'])): ?>
										<div class="slider_videos">
											<iframe style="width: 100%; aspect-ratio: 16 / 9;" src="<?php echo getDzenSrc($fields['video_after_gates_dzen'])?>" allow="autoplay; fullscreen; accelerometer; gyroscope; picture-in-picture; encrypted-media" frameborder="0" scrolling="no" allowfullscreen></iframe>
											<?php if((isset($fields['video_after_gates_dzen_more']) && $fields['video_after_gates_dzen_more']) && count($fields['video_after_gates_dzen_more'])): ?>
												<?php foreach($fields['video_after_gates_dzen_more'] as $item): ?>
													<iframe style="width: 100%;height: 440px;" src="<?php echo getDzenSrc($item['code'])?>" allow="autoplay; fullscreen; accelerometer; gyroscope; picture-in-picture; encrypted-media" frameborder="0" scrolling="no" allowfullscreen></iframe>
												<?php endforeach ?>
											<?php endif ?>
										</div>
									<?php else: ?>
										<iframe style="width: 100%; aspect-ratio: 16 / 9;" src="//www.youtube.com/embed/<?php echo getYoutubeEmbedUrl($fields['video_after_gates'])?>" allow="autoplay; fullscreen; accelerometer; gyroscope; picture-in-picture; encrypted-media" frameborder="0" scrolling="no" allowfullscreen></iframe>
									<?php endif ?>
								</div>
							</div>

						<?php endif ?>

						<?php //Вопрос - Ответ
						if (get_field('faq_title') && have_rows('faq_list')) { ?>
							<div class="container">
								<div class="block_wrap">
									<div class="content-header">
										<h2 class="content-header__title"><?php the_field('faq_title'); ?></h2>
									</div>
									<div class="faq">
										<?php while (have_rows('faq_list')) : the_row(); ?>
											<div class="faq__item">
												<div class="faq__title_wrap">
													<div class="faq__title">
														<?php the_sub_field('faq_list_question'); ?>
													</div>
													<svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M9.87978 6.43895L5.99978 2.55895L2.11978 6.43895C1.72978 6.82895 1.09978 6.82895 0.709784 6.43895C0.319784 6.04895 0.319784 5.41895 0.709784 5.02895L5.29978 0.438946C5.68978 0.0489458 6.31978 0.0489458 6.70978 0.438946L11.2998 5.02895C11.6898 5.41895 11.6898 6.04895 11.2998 6.43895C10.9098 6.81895 10.2698 6.82895 9.87978 6.43895Z" fill="#028AE0"></path>
													</svg>
												</div>
												<div class="faq__text">
													<?php the_sub_field('faq_list_answer'); ?>
												</div>
											</div>
										<?php endwhile; ?>
									</div>
								</div>
							</div>
						<?php } ?>




							<div class="flex mb-6 w-full items-center justify-between">
								<h3>Рекомендуем также следующие экскурсии:</h3>
								<a href="/" class="text-[21px]">Вернуться к списку экскурсий</a>
							</div>

							<div class="content__tours content__tours-related">
				<?php if (!empty($fields['recommended'])) : ?>
					<?php
						$query = new WP_Query([
								'post_type' => 'tours', // Замените на ваш тип записей, если это не стандартный
								'posts_per_page' => -1, // Показывать все посты (или укажите ограничение)
								'post__in' => $fields['recommended'],
						]);

					// Проверяем, есть ли посты
					if ($query->have_posts()) : ?>
								<div class="content__tours grid gap-6 ms:grid-cols-1 md:grid-cols-2" >
									<?php while ($query->have_posts()) :
						$query->the_post();
						$fieldsRecommended = get_fields();
						if (!empty($fieldsRecommended['duration'])) {
							$duration_no_let = preg_replace("/[^,.:0-9]/", '', correctTime($fieldsRecommended['duration']));
							$duration_clear = str_replace(',','.',$duration_no_let);
						}
						?>
						<!-- Фильтруемый контент -->
						<div class="card item basis-0 grow" data-duration="<?php echo $duration_clear; ?>" data-cost="<?php echo get_cost($fieldsRecommended)['cost_sale'] ?? get_cost($fieldsRecommended)['cost']; ?>" data-popular="<?php echo ++$count;?>">
							<div class="shadow-[4px_4px_20px_#0000001a] group overflow-hidden rounded-[10px] bg-white">
								<div class="overflow-hidden mb-5 min-h-[300px]">
									<div class="relative">
										<a href="<?php echo get_permalink(); ?>">
											<?php if ( has_post_thumbnail() ) : ?>
												<img class="w-full h-[240px] object-cover rounded-lg" src="<?php  the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
											<?php else : ?>
												<img class="w-full h-[240px] object-cover rounded-lg bg-gray-300" src="<?php echo get_stylesheet_directory_uri(); ?>/img/woocommerce-placeholder.webp" alt="No image available">
											<?php endif; ?>

										</a>
										<button class="wish-btn absolute top-4 left-4 w-[35px] h-[35px] flex justify-center items-center rounded-full bg-white group" data-wp-id="<?php echo $post->ID; ?>">
											<div class="icon">
												<svg class="w-6 h-6 fill-current text-[#A5A5A5] group-[.active]:text-red-600">
													<path class="icon-path" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
												</svg>
											</div>
										</button>
										<?php if (isset($fieldsRecommended['video_after_gates']) && !empty($fieldsRecommended['video_after_gates'])): ?>
											<span class="absolute bottom-1 left-0 w-[76px] h-[60px] p-[20px] flex justify-center" data-ll-status="observed">
												<svg height="100%" version="1.1" viewBox="0 0 68 48" width="35" ><path class="" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"></path><path d="M 45,24 27,14 27,34" fill="#fff"></path>
												</svg>
											</span>
										<?php endif ?>
									</div>
									<div class="p-4">
										<a href="<?php echo get_permalink(); ?>">
											<h2 class="text-xl font-semibold text-gray-800 mb-2 text-center filter"><?php echo get_the_title();	?></h2>
										</a>
										<div class="h-[60px] text-[14px] leading-[20px] relative overflow-hidden custom-blur mb-4">
											<?php echo wp_trim_words(get_the_content(), 20) ?>
										</div>

										<div class="flex flex-col gap-1 mb-4  h-[125px]">
											<?php if (isset($fieldsRecommended['periodicity']) && $fieldsRecommended['periodicity']) : ?>
												<span class="flex items-center gap-2">
													<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[18px]" width="18" height="18" viewBox="0 0 18 18" fill="none">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M13.8857 2.05714H9.77143V0.771429C9.77143 0.34538 9.42605 0 9 0C8.57395 0 8.22857 0.34538 8.22857 0.771429V2.05714H4.11429V0.771429C4.11429 0.34538 3.76891 0 3.34286 0C2.91681 0 2.57143 0.34538 2.57143 0.771429V2.06867C1.12976 2.19859 0 3.41022 0 4.88571V6.68571V8.22857V15.1714C0 16.7336 1.26639 18 2.82857 18H15.1714C16.7336 18 18 16.7336 18 15.1714V8.22857V6.68571V4.88571C18 3.41022 16.8702 2.19859 15.4286 2.06867V0.771429C15.4286 0.34538 15.0832 0 14.6571 0C14.2311 0 13.8857 0.34538 13.8857 0.771429V2.05714ZM3.34286 3.6L2.82857 3.6C2.11849 3.6 1.54286 4.17563 1.54286 4.88571V6.68571H16.4571V4.88571C16.4571 4.17563 15.8815 3.6 15.1714 3.6L14.6571 3.6H9H3.34286ZM1.54286 15.1714V8.22857H16.4571V15.1714C16.4571 15.8815 15.8815 16.4571 15.1714 16.4571H2.82857C2.11849 16.4571 1.54286 15.8815 1.54286 15.1714Z" fill="#2C84D1"/>
													</svg>
													<?php echo $fieldsRecommended['periodicity']; ?>
												</span>
											<?php endif ?>

											<?php if (isset($fieldsRecommended['start_time']) && $fields['$fieldsRecommended']) : ?>
												<span class="flex items-center gap-2">
													<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[18px]" width="18" height="18" viewBox="0 0 18 18" fill="none">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18ZM9 16.6154C4.79414 16.6154 1.38462 13.2059 1.38462 9C1.38462 4.79414 4.79414 1.38462 9 1.38462C13.2059 1.38462 16.6154 4.79414 16.6154 9C16.6154 13.2059 13.2059 16.6154 9 16.6154ZM9 4.43143C9.38235 4.43143 9.69231 4.74139 9.69231 5.12374V9.63631L11.9407 11.8847C12.2111 12.1551 12.2111 12.5934 11.9407 12.8638C11.6703 13.1341 11.232 13.1341 10.9616 12.8638L8.51046 10.4126C8.38063 10.2828 8.30769 10.1067 8.30769 9.92308V5.12374C8.30769 4.74139 8.61765 4.43143 9 4.43143Z" fill="#2C84D1"/>
													</svg>
													<?php echo $fieldsRecommended['start_time']; ?>
												</span>
											<?php endif ?>

											<?php if (isset($fieldsRecommended['duration']) && $fieldsRecommended['duration']) : ?>
												<span class="flex items-center gap-2">
													<svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17" fill="none">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M9.3041 17C14.1067 17 18 13.1944 18 8.5C18 3.80558 14.1067 0 9.3041 0C4.5015 0 0.608227 3.80558 0.608227 8.5C0.608227 10.5534 1.35819 12.4881 2.67467 14.0016H0.668914C0.299483 14.0016 1.1134e-08 14.2944 0 14.6555C-1.11359e-08 15.0166 0.299483 15.3093 0.668913 15.3093L4.45286 15.3093C4.82229 15.3093 5.12177 15.0166 5.12177 14.6555V10.9567C5.12177 10.5956 4.82229 10.3029 4.45286 10.3029C4.08343 10.3029 3.78395 10.5956 3.78395 10.9567V13.2562C2.61413 11.9609 1.94605 10.2842 1.94605 8.5C1.94605 4.5278 5.24037 1.30769 9.3041 1.30769C13.3678 1.30769 16.6622 4.5278 16.6622 8.5C16.6622 12.4722 13.3678 15.6923 9.3041 15.6923C8.93467 15.6923 8.63519 15.985 8.63519 16.3462C8.63519 16.7073 8.93467 17 9.3041 17ZM9.30415 4.18521C9.67358 4.18521 9.97306 4.47794 9.97306 4.83905V9.10093L12.1455 11.2244C12.4067 11.4797 12.4067 11.8937 12.1455 12.1491C11.8842 12.4044 11.4607 12.4044 11.1995 12.1491L8.83115 9.8341C8.70571 9.71148 8.63523 9.54517 8.63523 9.37176V4.83905C8.63523 4.47794 8.93472 4.18521 9.30415 4.18521Z" fill="#2C84D1"/>
													</svg>
													<?php echo $fieldsRecommended['duration']; ?>  часов
												</span>
											<?php endif ?>
											<?php if (isset($fieldsRecommended['departure_address']) && $fieldsRecommended['departure_address']) : ?>
												<span class="flex items-center gap-2">
													<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[18px]" width="14" height="18" viewBox="0 0 14 18" fill="none">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M7.00001 1.51868C3.99313 1.51868 1.55557 3.89539 1.55557 6.83401C1.55557 8.98292 2.53635 11.0711 3.75124 12.81C4.93113 14.5109 6.28446 15.8169 7.00001 16.4548C7.71557 15.8169 9.0689 14.5109 10.2488 12.81C11.4637 11.0711 12.4445 8.98292 12.4445 6.83401C12.4445 3.89539 10.0069 1.51868 7.00001 1.51868ZM0 6.834C0 3.06011 3.13367 0 7 0C10.8663 0 14 3.06011 14 6.834C14 9.41574 12.8326 11.8 11.536 13.668C10.2324 15.536 8.74689 16.9559 7.99556 17.6241C7.427 18.1253 6.573 18.1253 6.00445 17.6241C5.25311 16.9559 3.76756 15.536 2.464 13.668C1.16744 11.8 0 9.41574 0 6.834ZM6.99999 5.31531C6.14132 5.31531 5.44443 5.99871 5.44443 6.83397C5.44443 7.66924 6.14132 8.35264 6.99999 8.35264C7.85865 8.35264 8.55554 7.66924 8.55554 6.83397C8.55554 5.99871 7.85865 5.31531 6.99999 5.31531ZM3.88886 6.83403C3.88886 5.1559 5.28186 3.79669 6.99997 3.79669C8.71808 3.79669 10.1111 5.1559 10.1111 6.83403C10.1111 8.51215 8.71808 9.87136 6.99997 9.87136C5.28186 9.87136 3.88886 8.51215 3.88886 6.83403Z" fill="#2C84D1"/>
													</svg>
													<?php echo $fieldsRecommended['departure_address']; ?></span>
											<?php endif ?>
										</div>

										<div class="tour__cost">
											Стоимость:
											<span>
												<span class="font-semibold text-[#08c] text-[21px]">от</span>
												<span id="min_cost">
													<span class="text-[#f24941] font-semibold text-[21px] mr-[5px] line-through"><?php echo get_cost($fieldsRecommended)['cost']; ?></span>
													<span class="text-[#08c] text-[21px]"><?php echo get_cost($fieldsRecommended)['cost_sale']; ?></span>  руб/чел</span>
											</span>
										</div>

									</div>
								</div>
							</div>



						</div>
					<?php endwhile; ?>
								</div>


					<?php else : ?>
						<p>no wishes</p>
					<?php endif; wp_reset_postdata(); ?>

				<?php endif;?>
							</div>
						<!-- /recent posts-->
					</div>
				</div>

				<div class="lg:col-span-4 col-span-12 tours flex flex-col gap-4">
						<div class="content__wrap-price">
							<div class="tour__cost">
								Стоимость:
								<span>
												<span class="font-semibold text-[#08c] text-[21px]">от</span>
												<span id="min_cost">
													<span class="text-[#f24941] font-semibold text-[21px] mr-[5px] line-through"><?php echo  get_cost($fields)['cost']; ?></span>
													<span class="text-[#08c] text-[21px]"><?php echo  get_cost($fields)['cost_sale'];; ?></span>  руб/чел</span>
											</span>
							</div>
						</div>
						<div class="flex flex-col gap-1 mb-4 ">
							<?php if (isset($fields['periodicity']) && $fields['periodicity']) : ?>
								<span class="flex items-center gap-2">
									<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[18px]" width="18" height="18" viewBox="0 0 18 18" fill="none">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M13.8857 2.05714H9.77143V0.771429C9.77143 0.34538 9.42605 0 9 0C8.57395 0 8.22857 0.34538 8.22857 0.771429V2.05714H4.11429V0.771429C4.11429 0.34538 3.76891 0 3.34286 0C2.91681 0 2.57143 0.34538 2.57143 0.771429V2.06867C1.12976 2.19859 0 3.41022 0 4.88571V6.68571V8.22857V15.1714C0 16.7336 1.26639 18 2.82857 18H15.1714C16.7336 18 18 16.7336 18 15.1714V8.22857V6.68571V4.88571C18 3.41022 16.8702 2.19859 15.4286 2.06867V0.771429C15.4286 0.34538 15.0832 0 14.6571 0C14.2311 0 13.8857 0.34538 13.8857 0.771429V2.05714ZM3.34286 3.6L2.82857 3.6C2.11849 3.6 1.54286 4.17563 1.54286 4.88571V6.68571H16.4571V4.88571C16.4571 4.17563 15.8815 3.6 15.1714 3.6L14.6571 3.6H9H3.34286ZM1.54286 15.1714V8.22857H16.4571V15.1714C16.4571 15.8815 15.8815 16.4571 15.1714 16.4571H2.82857C2.11849 16.4571 1.54286 15.8815 1.54286 15.1714Z" fill="#2C84D1"/>
									</svg>
									<?php echo $fields['periodicity']; ?>
								</span>
							<?php endif ?>

							<?php if (isset($fields['start_time']) && $fields['start_time']) : ?>
								<span class="flex items-center gap-2">
									<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[18px]" width="18" height="18" viewBox="0 0 18 18" fill="none">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18ZM9 16.6154C4.79414 16.6154 1.38462 13.2059 1.38462 9C1.38462 4.79414 4.79414 1.38462 9 1.38462C13.2059 1.38462 16.6154 4.79414 16.6154 9C16.6154 13.2059 13.2059 16.6154 9 16.6154ZM9 4.43143C9.38235 4.43143 9.69231 4.74139 9.69231 5.12374V9.63631L11.9407 11.8847C12.2111 12.1551 12.2111 12.5934 11.9407 12.8638C11.6703 13.1341 11.232 13.1341 10.9616 12.8638L8.51046 10.4126C8.38063 10.2828 8.30769 10.1067 8.30769 9.92308V5.12374C8.30769 4.74139 8.61765 4.43143 9 4.43143Z" fill="#2C84D1"/>
									</svg>
									<?php echo $fields['start_time']; ?>
								</span>
							<?php endif ?>

							<?php if (isset($fields['duration']) && $fields['duration']) : ?>
								<span class="flex items-center gap-2">
									<svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17" fill="none">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M9.3041 17C14.1067 17 18 13.1944 18 8.5C18 3.80558 14.1067 0 9.3041 0C4.5015 0 0.608227 3.80558 0.608227 8.5C0.608227 10.5534 1.35819 12.4881 2.67467 14.0016H0.668914C0.299483 14.0016 1.1134e-08 14.2944 0 14.6555C-1.11359e-08 15.0166 0.299483 15.3093 0.668913 15.3093L4.45286 15.3093C4.82229 15.3093 5.12177 15.0166 5.12177 14.6555V10.9567C5.12177 10.5956 4.82229 10.3029 4.45286 10.3029C4.08343 10.3029 3.78395 10.5956 3.78395 10.9567V13.2562C2.61413 11.9609 1.94605 10.2842 1.94605 8.5C1.94605 4.5278 5.24037 1.30769 9.3041 1.30769C13.3678 1.30769 16.6622 4.5278 16.6622 8.5C16.6622 12.4722 13.3678 15.6923 9.3041 15.6923C8.93467 15.6923 8.63519 15.985 8.63519 16.3462C8.63519 16.7073 8.93467 17 9.3041 17ZM9.30415 4.18521C9.67358 4.18521 9.97306 4.47794 9.97306 4.83905V9.10093L12.1455 11.2244C12.4067 11.4797 12.4067 11.8937 12.1455 12.1491C11.8842 12.4044 11.4607 12.4044 11.1995 12.1491L8.83115 9.8341C8.70571 9.71148 8.63523 9.54517 8.63523 9.37176V4.83905C8.63523 4.47794 8.93472 4.18521 9.30415 4.18521Z" fill="#2C84D1"/>
									</svg>
									<?php echo $fields['duration']; ?>  часов
								</span>
							<?php endif ?>
							<?php if (isset($fields['departure_address']) && $fields['departure_address']) : ?>
								<span class="flex items-center gap-2">
									<svg xmlns="http://www.w3.org/2000/svg" class="min-w-[18px]" width="14" height="18" viewBox="0 0 14 18" fill="none">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M7.00001 1.51868C3.99313 1.51868 1.55557 3.89539 1.55557 6.83401C1.55557 8.98292 2.53635 11.0711 3.75124 12.81C4.93113 14.5109 6.28446 15.8169 7.00001 16.4548C7.71557 15.8169 9.0689 14.5109 10.2488 12.81C11.4637 11.0711 12.4445 8.98292 12.4445 6.83401C12.4445 3.89539 10.0069 1.51868 7.00001 1.51868ZM0 6.834C0 3.06011 3.13367 0 7 0C10.8663 0 14 3.06011 14 6.834C14 9.41574 12.8326 11.8 11.536 13.668C10.2324 15.536 8.74689 16.9559 7.99556 17.6241C7.427 18.1253 6.573 18.1253 6.00445 17.6241C5.25311 16.9559 3.76756 15.536 2.464 13.668C1.16744 11.8 0 9.41574 0 6.834ZM6.99999 5.31531C6.14132 5.31531 5.44443 5.99871 5.44443 6.83397C5.44443 7.66924 6.14132 8.35264 6.99999 8.35264C7.85865 8.35264 8.55554 7.66924 8.55554 6.83397C8.55554 5.99871 7.85865 5.31531 6.99999 5.31531ZM3.88886 6.83403C3.88886 5.1559 5.28186 3.79669 6.99997 3.79669C8.71808 3.79669 10.1111 5.1559 10.1111 6.83403C10.1111 8.51215 8.71808 9.87136 6.99997 9.87136C5.28186 9.87136 3.88886 8.51215 3.88886 6.83403Z" fill="#2C84D1"/>
									</svg>
									<?php echo $fields['departure_address']; ?>
								</span>
							<?php endif ?>
						</div>
				</div>
		</div>

	</section>



<?php get_footer(); // подключаем footer.php ?>

	</main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();
