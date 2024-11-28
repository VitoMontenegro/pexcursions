<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

?>

<header id="masthead" class="header_top container mx-auto grid header_content text-center w-full mb-8">
	<div class="site_header lg:h-[90px] items-center lg:pt-5 pt-[15px]">
		<div class="flex flex-nowrap items-center flex h-full grid grid-cols-12 gap-4">
			<div class="md:flex md:items-center lg:justify-left lg:text-center lg:col-span-2 col-span-4">
				<span class="toggle-megamenu w-[18px] h-[14px] relative mr-5 cursor-pointer lg:hidden">
					toggle
				</span>
				<?php
				if ( is_front_page() ) :
					?>
					<span class="inline-block"><?php bloginfo( 'name' ); ?></span>
				<?php
				else :
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="inline-block"><?php bloginfo( 'name' ); ?></a>
				<?php
				endif;

				$tw_description = get_bloginfo( 'description', 'display' );
				if ( $tw_description || is_customize_preview() ) :
					?>
					<p><?php echo $tw_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php endif; ?>
			</div>
			<div class="lg:col-span-10 col-span-3 lg:block hidden">
				<nav id="site-navigation" class="menu primary-menu h-full"  aria-label="<?php esc_attr_e( 'Main Navigation', 'tw' ); ?>">
					<?php
					wp_nav_menu(
							array(
									'theme_location' => 'menu-1',
									'menu_id'        => 'primary-menu',
									'items_wrap'     => '<ul id="%1$s" class="%2$s" aria-label="submenu">%3$s</ul>',
							)
					);
					?>
				</nav><!-- #site-navigation -->

			</div>
		</div>
	</div>

</header><!-- #masthead -->
