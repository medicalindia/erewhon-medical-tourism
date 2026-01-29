<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site-wrapper">
	
	<!-- Skip link for accessibility -->
	<a class="skip-link screen-reader-text" href="#primary">
		<?php esc_html_e( 'Skip to content', 'erewhon' ); ?>
	</a>

	<!-- Main Header -->
	<header id="masthead" class="site-header">
		<div class="container">
			
			<!-- Logo / Site Title -->
			<div class="site-branding">
				<?php
				// If custom logo exists
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
					?>
					<h1 class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
					</h1>
					<?php
				endif;
				?>
			</div>

			<!-- Mobile Menu Toggle Button -->
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'erewhon' ); ?></span>
				<span class="icon">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>

			<!-- Main Navigation -->
			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'container'      => false,
					)
				);
				?>

				<!-- CTA Button -->
				<div class="nav-cta">
					<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn btn--primary">
						<?php esc_html_e( 'Book Appointment', 'erewhon' ); ?>
					</a>
				</div>
			</nav>

		</div>
	</header><!-- #masthead -->