<?php
/*var_dump(get_post_type()); // excursion везде
var_dump(is_singular('excursion')); //true на экскурсиях
var_dump(is_tax()); //категория экскурсии*/
$fields = get_fields();
$i=0
?>
<section class="breadcrumbs">
	<div class="container mx-auto">
		<ol itemscope itemtype="http://schema.org/BreadcrumbList" class="flex gap-4">
			<?php if(is_category()): ?>
				<li itemprop="itemListElement" itemscope
					itemtype="http://schema.org/ListItem">
					<a itemprop="item"  href="<?php echo get_site_url();?>">
						<span itemprop="name">Блог</span>
					</a>
					<meta itemprop="position" content="<?php echo ++$i; ?>" />
				</li>
				<li>></li>
				<?php
					$category = get_queried_object();
					$ancestors = get_ancestors($category->term_id, 'category');
				?>
				<?php if($ancestors): ?>
					<?php foreach(array_reverse($ancestors) as $ancestor_id) : ?>
						<?php $ancestor = get_term($ancestor_id, 'category'); ?>
						<li itemprop="itemListElement" itemscope
							itemtype="https://schema.org/ListItem">
							<a itemprop="item" href="<?php echo get_term_link($ancestor)?>">
								<span itemprop="name"><?php echo $ancestor->name; ?></span></a>
							<meta itemprop="position" content="<?php echo ++$i; ?>" />
						</li>
						<li>></li>
					<?php endforeach; ?>
				<?php endif; ?>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<span itemprop="name"><?php echo $category->name; ?></span>
					<meta itemprop="position" content="<?php echo ++$i; ?>" />
				</li>
			<?php elseif(is_single()): ?>
				<?php $post_type = get_post_type(); ?>
				<?php if($post_type === 'excursion'): ?>

					<?php $terms = wp_get_post_terms(get_the_ID(), 'excursion_category'); ?>
					<?php if (!empty($terms) && !is_wp_error($terms)) : ?>

						<?php
							$term = $fields['cat_hk'] ? get_term($fields['cat_hk']) : $terms[0];
							$ancestors = get_ancestors($term->term_id, 'excursion_category');
						?>
						<?php if ($ancestors) : ?>
							<?php  foreach (array_reverse($ancestors) as $ancestor_id) : ?>
								<?php $ancestor = get_term($ancestor_id, 'excursion_category'); ?>
								<li itemprop="itemListElement" itemscope
									itemtype="https://schema.org/ListItem">
									<a itemprop="item" href="<?php echo get_term_link($ancestor); ?>">
										<span itemprop="name"><?php echo $ancestor->name; ?></span></a>
									<meta itemprop="position" content="<?php echo ++$i; ?>" />
								</li>
								<li>></li>
							<?php endforeach; ?>
						<?php endif; ?>
						<li itemprop="itemListElement" itemscope
							itemtype="https://schema.org/ListItem">
							<a itemprop="item" href="<?php echo get_term_link($term); ?>">
								<span itemprop="name"><?php echo $term->name; ?></span></a>
							<meta itemprop="position" content="<?php echo ++$i; ?>" />
						</li>
						<li>></li>
					<?php endif; ?>
				<?php else: ?>
					<?php $categories = get_the_category(); ?>
					<?php if (!empty($categories)): ?>
						<?php
							$category = $categories[0];
							$ancestors = get_ancestors($category->term_id, 'category');
						?>
						<?php if ($ancestors): ?>
							<?php foreach(array_reverse($ancestors) as $ancestor_id) : ?>
								<?php $ancestor = get_term($ancestor_id, 'category'); ?>
								<li itemprop="itemListElement" itemscope
									itemtype="https://schema.org/ListItem">
									<a itemprop="item" href="<?php echo get_term_link($ancestor)?>">
										<span itemprop="name"><?php echo $ancestor->name; ?></span></a>
									<meta itemprop="position" content="<?php echo ++$i; ?>" />
								</li>
								<li>></li>
							<?php endforeach; ?>
						<?php endif; ?>
						<li itemprop="itemListElement" itemscope
							itemtype="https://schema.org/ListItem">
							<a itemprop="item" href="<?php echo get_term_link($category); ?>">
								<span itemprop="name"><?php echo $category->name; ?></span></a>
							<meta itemprop="position" content="<?php echo ++$i; ?>" />
						</li>
						<li>></li>
					<?php endif; ?>
				<?php endif; ?>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<span itemprop="name"><?php echo get_the_title(); ?></span>
					<meta itemprop="position" content="<?php echo ++$i; ?>" />
				</li>
			<?php elseif (is_tax('excursion_category')) : ?>
				<?php
					$term = get_queried_object();
					$ancestors = get_ancestors($term->term_id, 'excursion_category');
				?>

				<?php if ($ancestors) : ?>
					<?php foreach (array_reverse($ancestors) as $ancestor_id) : ?>
						<?php $ancestor = get_term($ancestor_id, 'excursion_category'); ?>

						<li itemprop="itemListElement" itemscope
							itemtype="https://schema.org/ListItem">
							<a itemprop="item" href="<?php echo get_term_link($ancestor); ?>">
								<span itemprop="name"><?php echo $ancestor->name; ?></span></a>
							<meta itemprop="position" content="<?php echo ++$i; ?>" />
						</li>
						<li>></li>
					<?php endforeach; ?>
				<?php endif; ?>

				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<span itemprop="name"><?php echo $term->name; ?></span>
					<meta itemprop="position" content="<?php echo ++$i; ?>" />
				</li>

			<?php elseif (is_home() || is_front_page()) : ?>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<span itemprop="name">Блог</span>
					<meta itemprop="position" content="<?php echo ++$i; ?>" />
				</li>

					// Для остальных случаев (например, страниц)
			<?php else : ?>
				<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
					<span itemprop="name"><?php echo get_the_title(); ?></span>
					<meta itemprop="position" content="<?php echo ++$i; ?>" />
				</li>
			<?php endif; ?>
		</ol>
	</div>
</section>
