<?php
$sidebar_term = get_query_var('sidebar_term');

if ($sidebar_term) {
	$current_category = $sidebar_term;
} else {
	$current_category = get_queried_object(); // Текущая категория
}

if ($current_category && isset($current_category->term_id)) {
	// Получаем самого верхнего родителя
	$top_parent = get_top_parent_category($current_category->term_id, 'excursion_category');
	if ($top_parent) {
		// Получаем все категории, у которых родительский ID равен ID верхнего родителя
		/*
			$sibling_categories = get_sibling_categories($top_parent->term_id, 'excursion_category');

			if (!empty($sibling_categories)) : ?>
				<ul class="categories">
					<?php foreach ($sibling_categories as $category) : ?>
						<li>
							<a href="<?php echo esc_url($category['link']); ?>">
								<?php echo esc_html($category['name']); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif;
		*/
		$categories_tree = get_nested_categories_by_parent($top_parent->term_id, 'excursion_category');
		if (!empty($categories_tree)) : ?>
			<form id="filter-form" class="mb-7">
				<input type="hidden" id="category_id" value="<?php echo $current_category->term_id; ?>" />
				<div class="flex gap-1 flex-col w-full mb-[30px]">
					<label for="posts_per_page">Возрастная группа/класс:</label>
					<div class="flex gap-4 flex-wrap radio-group">
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="d" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	Дошкольники
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="n" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	Начальные классы
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="1" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	1
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="2" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	2
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="3" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	3
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="4" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	4
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="5" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	5
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="6" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	6
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="7" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	7
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="8" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	8
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="9" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	9
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="10" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	10
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="grade" value="10" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	11
						</span>
						</label>
					</div>
				</div>

				<div class="flex gap-1 flex-col w-full mb-[30px]">
					<div class="text-grey">Продолжительность</div>
					<div class="flex gap-4 flex-wrap radio-group">

						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="duration" value="12" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	1-2 часа
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="duration" value="23" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	2-3 часа
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="duration" value="45" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	4-5 часов
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="duration" value="67" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	6-7 часов
						</span>
						</label>
					</div>
				</div>

				<div class="flex gap-1 flex-col w-full">
					<div class="text-grey">Цена:</div>
					<div class="flex gap-4 flex-wrap radio-group">
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="price" value="1000-1500" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	1000₽ - 1500₽
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="price" value="1500-2000" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	1500₽ - 2000₽
						</span>
						</label>
						<label class="flex items-center cursor-pointer">
							<input type="checkbox" name="price" value="2000-10000" class="hidden peer" />
							<span class="px-4 py-2 border rounded-xl bg-gray-200 text-black peer-checked:bg-gray-400 peer-checked:text-white transition">
						  	2000₽ - 10000₽
						</span>
						</label>
					</div>
				</div>
			</form>
			<div class="flex flex-col gap-1">
				<div class="title_sidebar">Виды экскурсий</div>
			<div class="flex flex-col gap-4">
				<?php foreach ($categories_tree as $category) : ?>
					<?php $link = $category["single_post_slug"] ?? $category['link']; ?>
					<div>
						<a href="<?php echo $link; ?>" class="flex items-center gap-2 group<?php echo is_current_category($category["id"]) ? ' active' : ''; ?>">
							<span class="w-10 h-10 border-2 rounded-md border-black flex items-center justify-center group-active:bg-gray-200 group-[.active]:text-red-500 group-[.active]:border-red-500">
									<span class="invisible group-[.active]:visible">✓</span>
							</span>
							<span class="text-black group-[.active]:text-red-500"><?php echo esc_html($category['name']); ?></span>
						</a>
						<?php if (!empty($category['children'])) : ?>
							<?php render_children_categories($category['children']); ?>
						<?php endif; ?>
					</div>

				<?php endforeach; ?>
			</div>
			</div>
		<?php endif;
	}
}

