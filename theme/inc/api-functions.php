<?php
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once( ABSPATH . 'wp-admin/includes/file.php' );

function custom_rest_filter_posts() {
	register_rest_route('my_namespace/v1', '/filter-posts/', [
		'methods' => 'GET',
		'callback' => 'handle_filter_posts_request',
		'permission_callback' => '__return_true',
	]);
	register_rest_route('custom/v1', '/reviews-form', [
			'methods' => 'POST',
			'callback' => 'handle_reviews_form',
			'permission_callback' => '__return_true',
	]);
}
add_action('rest_api_init', 'custom_rest_filter_posts');

function isDateInRange($datesArr, $datesFromTo) {
	// Нормализуем начало и конец диапазона из $datesFromTo
	$startDate = isset($datesFromTo[0]) ? trim($datesFromTo[0]) : null;
	$endDate = isset($datesFromTo[1]) ? trim($datesFromTo[1]) : null;

	// Если есть только одна дата, рассматриваем её как единственную границу
	if ($startDate && !$endDate) {
		$endDate = $startDate;
	} elseif (!$startDate && $endDate) {
		$startDate = $endDate;
	}

	// Преобразуем в объекты DateTime
	$startDate = new DateTime($startDate);
	$endDate = new DateTime($endDate);

	// Проверяем даты
	foreach ($datesArr as $date) {
		$currentDate = new DateTime(trim($date));

		// Если текущая дата в диапазоне, возвращаем true
		if ($currentDate >= $startDate && $currentDate <= $endDate) {
			return true;
		}
	}

	// Если ничего не найдено, возвращаем false
	return false;
}


function handle_filter_posts_request(WP_REST_Request $request) {

	$duration = $request->get_param('duration');
	$price_ranges = $request->get_param('price');
	//$grade = $request->get_param('grade');
	$grade_sort = $request->get_param('grade_sort');
	$dateForm = $request->get_param('dateForm');
	$grade_sorts = json_decode($grade_sort);
	if($grade_sort) {
		if (is_array($grade_sort) && count($grade_sort) > 0) {
			$grade_sort = $grade_sorts[0];
		}
	} else {
		$grade_sort = 'pops';
	}
	$category_id = $request->get_param('category_id');

	// Получаем дочерние категории
	$child_categories = get_terms([
			'taxonomy'    => 'excursion',
			'child_of'    => $category_id,
			'fields'      => 'ids',
			'hide_empty'  => true,
	]);
	$categories = array_merge([$category_id], $child_categories);

	// Инициализация основного массива для WP_Query
	$query_args = [
			'post_type'      => 'tours',
			'posts_per_page' => -1,
			'tax_query'      => [
					[
							'taxonomy'         => 'excursion',
							'field'            => 'term_id',
							'terms'            => $categories,
					],
			]
	];

	$query = new WP_Query($query_args);

	$posts = [];
	ob_start();
	if ($query->have_posts()) {
		$count = 0;
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
			if (!empty($duration)) {
				$duration_values = json_decode($duration);
				if(!empty($duration_values)) {
					$durationRange = convertTime($fields['duration']) ?? 0 ;
					$returnDuration = false;
					foreach ($duration_values as $duration_range) {
						if ($duration_range) {
							$explode = explode('-', $duration_range);
							if (($durationRange >= (int)$explode[0]) && ($durationRange <= (int)$explode[1])) {
								$returnDuration = true;
							}
						}
					}
				}
			}


			$returnDateForm =  true;
			if ($dateForm) {
				$dateForm_values = explode('—', $dateForm);
				$returnDateForm = ($datesArray) ? isDateInRange($datesArray, $dateForm_values) : true;
			}

			// Обработка фильтра по price_range
			if (!empty($price_ranges)) {
				$price_values = json_decode($price_ranges);
				if (!empty($price_values)) {
					$priceRange = get_cost($fields)['cost_sale'] ? get_cost($fields)['cost_sale'] : get_cost($fields)['cost'];
					$returnPrice = false;
					foreach ($price_values as $price_range) {
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
			}

			//запрос
			if (!$returnPrice || !$returnDuration || !$returnGrade || !$returnDateForm) {
				continue;
			}


			// Обработка фильтра по price_range
			if (!empty($grade_sort)) {
				$gradeSort = get_cost($fields)['cost_sale'] ? get_cost($fields)['cost_sale'] : get_cost($fields)['cost'];
				$posts[] = [
						'post' => get_post(),
						'fields' => $fields,
						'datesArray' => $datesArray,
						'uniqueArray' => $uniqueArray,
						'gradeSort' => $gradeSort,
						'date' => get_the_date('Y-m-d H:i:s'), // Добавляем дату в формате строки
				];
			} else {
				$posts[] = [
						'post' => get_post(),
						'fields' => $fields,
						'datesArray' => $datesArray,
						'uniqueArray' => $uniqueArray,
						'gradeSort' => '',
						'date' => get_the_date('Y-m-d H:i:s'), // Добавляем дату в формате строки
				];
			}
		}
		if ($grade_sort === 'expensive') {
			usort($posts, function ($a, $b) {
				return $a['gradeSort'] >= $b['gradeSort'];
			});
		} elseif($grade_sort === 'chip') {
			usort($posts, function ($a, $b) {
				return $a['gradeSort'] <= $b['gradeSort'];
			});
		} else {
			usort($posts, function ($a, $b) {
				return strtotime($b['date']) <=> strtotime($a['date']);
			});
		}


		foreach ($posts as $postData) {
			$post = $postData['post'];
			setup_postdata($post);

			$fields = $postData['fields'];
			$datesArray = $postData['datesArray'];
			$uniqueArray = $postData['uniqueArray'];

			?>
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
						<div class="absolute left-4 sm:left-6 bottom-[18px] flex gap-1 items-center bg-[#FFFFFF] rounded-[6px] h-[28px] px-2">
							<span class="w-6 h-6 rounded-full bg-white flex items-center justify-center">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/clock.svg" alt="" class="object-cover">
							</span>
							<div class="text-[#6B7280] leading-0"><?php echo $fields['duration']; ?></div>
						</div>
					<?php endif ?>
					<?php if (isset($fields['sticker']) && $fields['sticker']) : ?>
						<?php
						$bg_stick = (isset($fields['sticker_background']) &&!empty($fields['sticker_background']) ) ? $fields['sticker_background'] : "#D45E5E";
						$bg_color = (isset($fields['sticker_text']) &&!empty($fields['sticker_text']) ) ? $fields['sticker_text'] : "#FFF";
						?>
						<div class="absolute left-4 sm:left-6 top-[18px] flex items-center rounded-[6px] h-[34px] px-4 text-white" style="background: <?php echo $bg_stick;?>;color:<?php echo $bg_color;?>">
							<div class="leading-0"><?php echo $fields['sticker'];?></div>
						</div>
					<?php endif ?>
					<button class="absolute right-4 sm:right-[18px] top-[10px] sm:top-[18px] wish-btn w-12 h-12 flex items-center justify-center group" data-wp-id="<?php echo $post->ID; ?>" aria-label="Добавить в избранное">
						<span class="w-9 h-9 rounded-full bg-white flex items-center justify-center">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/heart.svg" alt="" class="object-cover block group-[:hover]:hidden group-[.active]:hidden">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/heart-hover.svg" alt="" class="object-cover hidden group-[:hover]:block group-[.active]:hidden">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/heart-active.svg" alt="" class="object-cover hidden group-[.active]:block">
						</span>
					</button>
					<?php if (isset($fields['video_after_gates']) && !empty($fields['video_after_gates'])): ?>
						<button class="absolute right-[65px]  top-[10px] sm:top-[18px]  w-12 h-12 flex items-center justify-center group" aria-label="Смотреть видео">
							<span class="w-6 h-6 rounded-full bg-white flex items-center justify-center">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/play.svg" alt="" class="object-cover">
							</span>
						</button>
					<?php endif ?>
				</div>
				<div class="px-4 sm:px-6 flex flex-col gap-5 h-full">
					<div class="flex flex-col gap-1 flex-grow relative min-h-[96px]">
						<a href="<?php echo get_permalink($post->ID); ?>" class="card-title text-[18px] lg:text-[20px] font-bold leading-[1.2] three-lines"><?php echo get_the_title($post->ID); ?></a>
						<div class="date flex items-center gap-2 text-[14px] sm:text-[16px]">
							<?php if(count($uniqueArray)) : ?>
								<div class="flex items-center gap-[2px] text-[#6B7280]">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/calendar.svg" alt="" class="object-cover">
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
									<div class="dropdown-menu absolute right-0 z-10 mt-2  w-[310px] origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden">

										<div class="">
											<div class="p-4 calendar-wrapper" data-dates='<?php echo json_encode($datesArray) ;?>'>
												<div class="flex justify-end relative -t-[2px]">
													<button class="close-menu ">
														<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
															<path d="M12 20L16 16M16 16L19.6667 12.3333M16 16L12 12M16 16L20 20M29 16C29 23.1797 23.1797 29 16 29C8.8203 29 3 23.1797 3 16C3 8.8203 8.8203 3 16 3C23.1797 3 29 8.8203 29 16Z" stroke="#9CA3AF" stroke-width="2.67" stroke-linecap="round" stroke-linejoin="round"/>
														</svg>
													</button>
												</div>
												<div class="calendar pointer-events-none"></div>
												<a href="<?php echo get_permalink($post->ID); ?>" class="px-2 h-8 text-[14px] rounded-md bg-[#D6BD7F] flex sm:hidden items-center justify-center text-white">
													Подробнее
												</a>
											</div>
										</div>
									</div>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="flex items-center justify-between">
						<div class="price flex flex-col gap-1">
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
						<a href="<?php echo get_permalink($post->ID); ?>" class="inline-flex h-11 items-center justify-center font-medium px-7 sm:px-10 rounded-md bg-[#52A6B2] hover:bg-[#44909B] text-white text-[14px] lg:text-[16px]">Подробнее</a>
					</div>
				</div>
			</div>
			<?php
		}


		if ($count === 0) {
			echo '<div class="col-span-12 pb-8"> <div class="bold text-lg pb-8 w-full">Попробуйте другие варианты фильтра. По заданным вами параметрам мы не нашли экскурсии. </div><button type="button" class="close-filter-btn button-cancel h-10 w-full max-w-[160px] flex items-center justify-center bg-[#52A6B2] text-white rounded-[6px] hover:bg-[#44909B]" id="cancelBtnFilter">Сбросить фильтры</button></div>';
		}
	} else {
		echo '<div class="col-span-12 pb-8"> <div class="bold text-lg pb-8 w-full">Попробуйте другие варианты фильтра. По заданным вами параметрам мы не нашли экскурсии. </div><button type="button" class="close-filter-btn button-cancel h-10 w-full max-w-[160px] flex items-center justify-center bg-[#52A6B2] text-white rounded-[6px] hover:bg-[#44909B]" id="cancelBtnFilter">Сбросить фильтры</button></div>';
	}
	$output = ob_get_clean();

// Верните данные через REST API
	wp_send_json_success(['html' => $output]);
}


function handle_reviews_form(WP_REST_Request $request) {
	$params = $request->get_params();
	$files = $_FILES['file'] ?? null;

	$recepient = 'testdev@kometatek.ru';
	$sitename = "Flagman";
	$name = sanitize_text_field($params["name"]);
	$email = sanitize_email($params["email"]);
	$text = sanitize_textarea_field($params["message"]);

	$excurs = isset($params["excurs"]) ? wp_strip_all_tags($params["excurs"]) : '';
	$rating = isset($params["rating"]) ? (int)$params["rating"] : 0;

	$message = "Дата: " . date('d/m/Y') . "<br/><br/>\r\n";
	$message .= "Имя: " .  $name . "<br/><br/>\r\n";
	$message .= "Экскурсия: " .  $excurs . "<br/><br/>";
	$message .= "Рейтинг: " .  $rating . "<br/><br/>";
	$message .= "Телефон или Email: " .  $email . "<br/><br/>";
	$message .= "Сообщение: " .  $text . "<br/><br/>";

	$pagetitle = "Новый отзыв с сайта \"$sitename\"";

	// Создание записи "Отзыв"
	$post_data = [
			'post_title'   => $name,
			'post_content' => $text,
			'post_status'  => 'pending',
			'post_author'  => 1,
			'post_type'    => 'reviews',
	];
	$post_id = wp_insert_post($post_data);

	// Обработка файлов
	if ($files) {
		$uploaded_files = array_map('RemapFilesArray',
				$files['name'],
				$files['type'],
				$files['tmp_name'],
				$files['error'],
				$files['size']
		);

		$gallery = [];

		foreach ($uploaded_files as $file) {
			if($file["name"] && $file["type"] && $file["tmp_name"]) {
				$attachment = my_update_attachment($file, $post_id);
				if (isset($attachment['attach_id'])) {
					$gallery[] = $attachment['attach_id'];
				}
			}
		}

		// Сохранение дополнительных полей (ACF или meta)
		update_field('field_5fad894783054', $gallery, $post_id); // Поле для галереи
	}

	// Сохранение дополнительных данных
	update_field('field_5fad895583055', $excurs, $post_id);
	update_field('field_5fad897183057', $rating, $post_id);
	update_field('field_612cc6d2ad914', $email, $post_id);

	// Добавление ссылки на запись в сообщение
	$message .= "Ссылка на отзыв: https://parus-peterburg.ru/wp-admin/post.php?post=$post_id&action=edit<br/><br/>";

	// Возвращаем ответ REST API
	return rest_ensure_response([
			'success' => true,
			'message' => 'Отзыв успешно отправлен!',
			'post_id' => $post_id,
	]);
}

// Вспомогательная функция для обработки массива файлов
function RemapFilesArray($name, $type, $tmp_name, $error, $size) {
    return array(
        'name' => $name,
        'type' => $type,
        'tmp_name' => $tmp_name,
        'error' => $error,
        'size' => $size,
    );
}

// Вспомогательная функция для загрузки вложений

function my_update_attachment($f,$pid,$t='',$c='') {
  wp_update_attachment_metadata( $pid, $f );
  if( !empty( $f['name'] )) {

    $override['test_form'] = false;
    $file = wp_handle_upload( $f, $override );

    if ( isset( $file['error'] )) {
      return new WP_Error( 'upload_error', $file['error'] );
    }

    $file_type = wp_check_filetype($f['name'], array(
      'jpg|jpeg' => 'image/jpeg',
      'gif' => 'image/gif',
      'png' => 'image/png',
    ));
    if ($file_type['type']) {
      $name_parts = pathinfo( $file['file'] );
      $name = $f['name'];
      $type = $file['type'];
      $title = $t ? $t : $name;
      $content = $c;

      $attachment = array(
        'post_title' => $title,
        'post_type' => 'attachment',
        'post_content' => $content,
        'post_parent' => $pid,
        'post_mime_type' => $type,
        'guid' => $file['url'],
      );


      foreach( get_intermediate_image_sizes() as $s ) {
        $sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => true );
        $sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
        $sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
        $sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options
      }

      $sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );

      foreach( $sizes as $size => $size_data ) {
        $resized = image_make_intermediate_size( $file['file'], $size_data['width'], $size_data['height'], $size_data['crop'] );
        if ( $resized )
          $metadata['sizes'][$size] = $resized;
      }

      $attach_id = wp_insert_attachment( $attachment, $file['file'] /*, $pid - for post_thumbnails*/);

      if ( !is_wp_error( $attach_id )) {
        $attach_meta = wp_generate_attachment_metadata( $attach_id, $file['file'] );
        wp_update_attachment_metadata( $attach_id, $attach_meta );
      }

   return array(
  'pid' =>$pid,
  'url' =>$file['url'],
  'file'=>$file,
  'attach_id'=>$attach_id
   );
    }
  }
}

