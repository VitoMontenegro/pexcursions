<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _bukvica
 */

?>
<section>
	<div class="container mx-auto">
		<div class="flex justify-between items-center">
			<ul class="flex items-center gap-6  hidden md:flex tracking-[.2px]">
				<li class="group relative pt-[14px] pb-[12px] md:items-start lg:items-center flex items-center lg:gap-2">
					<a href="/" class="font-medium items-center max-w-none sm:max-v-[165px] lg:max-w-none  leading-[16px] sm:max-w-[145px] lg:max-w-none">
						Экскурсии по Петербургу
					</a>
					<svg class="mt-0 sm:mt-[2px] min-w-[12px] " xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
						<g clip-path="url(#clip0_135_6833)">
							<path d="M1.5 3.75L6 8.25L10.5 3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						</g>
						<defs>
							<clipPath>
								<rect width="12" height="12" fill="white" transform="translate(12) rotate(90)"></rect>
							</clipPath>
						</defs>
					</svg>
					<ul class="submenu absolute top-10 bg-[#FFFFFF] w-full px-2 py-4 z-10  flex-col gap-1 border hidden group-hover:flex rounded-md min-w-[125px]">
						<?php $cats = get_nested_categories_by_parent(2); ?>
						<?php foreach($cats as $cat): ?>
							<li>
								<a href="<?php echo $cat['link']; ?>" class="font-semibold py-1.5 rounded-md hover:bg-[#52A6b2]  hover:text-white block w-full px-2"><?php echo $cat['name'] ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>
				<li class="group relative pt-[14px] pb-[12px] md:items-start lg:items-center flex items-center lg:gap-2">
					<a href="<?php echo get_term_link(8 ,'excursion') ?>" class="font-medium items-center max-w-none sm:max-v-[165px] lg:max-w-none  leading-[16px] sm:max-w-[145px] lg:max-w-none">
						Пригороды
					</a>
					<svg class="mt-0 sm:mt-[2px] min-w-[12px] " xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
						<g clip-path="url(#clip0_135_6833)">
							<path d="M1.5 3.75L6 8.25L10.5 3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						</g>
						<defs>
							<clipPath>
								<rect width="12" height="12" fill="white" transform="translate(12) rotate(90)"></rect>
							</clipPath>
						</defs>
					</svg>
					<ul class="submenu absolute top-10 bg-[#FFFFFF] w-full px-2 py-4 z-10  flex-col gap-1 border hidden group-hover:flex rounded-md min-w-[125px]">
						<?php $cats = get_nested_categories_by_parent(8); ?>
						<?php foreach($cats as $cat): ?>
							<li>
								<a href="<?php echo $cat['link']; ?>" class="font-semibold py-1.5 rounded-md hover:bg-[#52A6b2]  hover:text-white block w-full px-2"><?php echo $cat['name'] ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>
				<li class="group relative pt-[14px] pb-[12px] md:items-start lg:items-center flex items-center lg:gap-2">
					<a href="<?php echo get_term_link(16 ,'excursion') ?>" class="font-medium items-center max-w-none sm:max-v-[165px] lg:max-w-none  leading-[16px] sm:max-w-[145px] lg:max-w-none">
						Дальнего следования
					</a>
					<svg class="mt-0 sm:mt-[2px] min-w-[12px] " xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
						<g clip-path="url(#clip0_135_6833)">
							<path d="M1.5 3.75L6 8.25L10.5 3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						</g>
						<defs>
							<clipPath>
								<rect width="12" height="12" fill="white" transform="translate(12) rotate(90)"></rect>
							</clipPath>
						</defs>
					</svg>
					<ul class="submenu absolute top-10 bg-[#FFFFFF] w-full px-2 py-4 z-10  flex-col gap-1 border hidden group-hover:flex rounded-md min-w-[125px]">
						<?php $cats = get_nested_categories_by_parent(16); ?>
						<?php foreach($cats as $cat): ?>
							<li>
								<a href="<?php echo $cat['link']; ?>" class="font-semibold py-1.5 rounded-md hover:bg-[#52A6b2]  hover:text-white block w-full px-2"><?php echo $cat['name'] ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>


				<?php $menu_items_second = wp_get_nav_menu_items('menu-1'); ?>
				<?php foreach ($menu_items_second as $item): ?>
					<li class="group relative pt-[14px] pb-[12px] md:items-start lg:items-center flex items-center lg:gap-2">
						<a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

</section>
