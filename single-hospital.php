<?php
/**
 * Template Name: Single Hospital
 *
 * @package Erewhon
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Get hospital data using helper
	$data = erewhon_get_hospital_card_data();
	?>

<main id="primary" class="site-main">
	<section class="section">
		<div class="container-grid container-grid--sidebar-wide">

			<!-- Main Content -->
			<div class="flow flow--container-gap">
				<header>
					<?php if ( $data['type_label'] ) : ?>
						<div class="text-primary font-bold text-uppercase tracking-wide mb-xs">
							<?php echo esc_html( $data['type_label'] ); ?>
						</div>
					<?php endif; ?>

					<h1 class="h1"><?php the_title(); ?></h1>

					<div class="cluster cluster--l text-secondary">
						<?php if ( $data['location'] ) : ?>
							<div class="flex items-center gap-s">
								<svg class="align-middle" width="18" height="18" viewBox="0 0 16 16" fill="none">
									<path d="M8 8.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" stroke="currentColor" stroke-width="1.5"/>
									<path d="M13 7c0 3.5-5 7-5 7s-5-3.5-5-7a5 5 0 0110 0z" stroke="currentColor" stroke-width="1.5"/>
								</svg>
								<span><?php echo esc_html( $data['location'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( $data['bed_capacity'] ) : ?>
							<div class="flex items-center gap-s">
								<svg class="align-middle" width="18" height="18" viewBox="0 0 16 16" fill="none">
									<rect x="2" y="7" width="12" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
									<path d="M4 7V5a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="1.5"/>
								</svg>
								<span><?php echo absint( $data['bed_capacity'] ); ?> beds</span>
							</div>
						<?php endif; ?>
					</div>
				</header>

				<div class="flow flow--container-gap">
					<!-- Featured Image -->
					<div class="relative rounded-xl overflow-hidden shadow-md">
						<img
							src="<?php echo esc_url( $data['image_url'] ); ?>"
							alt="<?php echo esc_attr( $data['title'] ); ?>"
							class="w-full h-auto object-cover"
						>
						<?php if ( $data['badge'] ) : ?>
							<span class="absolute top-0 right-0 m-m pill pill--success text-uppercase">
								<?php echo esc_html( $data['badge'] ); ?> Accredited
							</span>
						<?php endif; ?>
					</div>

					<div class="text-l text-secondary">
						<?php echo esc_html( $data['bio_short'] ); ?>
					</div>

					<?php if ( ! empty( $data['specialty_pills'] ) ) : ?>
						<div>
							<strong class="block mb-s text-s text-secondary">Key Specialties</strong>
							<ul class="pills">
								<?php foreach ( $data['specialty_pills'] as $spec ) : ?>
									<li><span class="pill pill--secondary"><?php echo esc_html( $spec->name ); ?></span></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<!-- Main Body -->
					<div class="stack stack--m">
						<?php the_content(); ?>
					</div>
				</div>
			</div>

			<!-- Sidebar Action Card -->
			<aside>
				<div class="card card--action">
					<h3 class="card__heading">Book a Consultation</h3>
					<p class="text-secondary text-m">
						Get more information about treatments at <?php echo esc_html( $data['title'] ); ?>.
					</p>

					<?php
					// Determine primary specialty for button
					$primary_specialty = 'General Medicine';
					if ( ! empty( $data['specialty_pills'] ) ) {
						$primary_specialty = $data['specialty_pills'][0]->name;
					}

					$subject = 'Inquiry for ' . $data['title'];
					$contact_url = '/contact/?subject=' . urlencode( $subject );
					?>

					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn--primary btn--block">
						Inquiry about <?php echo esc_html( $primary_specialty ); ?>
					</a>

					<hr class="border-top w-full my-s">

					<div class="flex items-center gap-s text-s text-secondary">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="1.5"/>
							<path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
						</svg>
						<span>International patient support</span>
					</div>
				</div>
			</aside>

		</div>

	</section>

	<!-- Related Hospitals -->
	<?php
	$related = erewhon_get_related_posts( get_the_ID(), 'hospital', 'destination', 3 );
	if ( $related->have_posts() ) :
	?>
	<section class="section section--bg-muted">
		<div class="container">
			<h2 class="h2 mb-l">Other Hospitals in <?php echo esc_html( $data['location'] ); ?></h2>
			<div class="grid grid--auto-fit-md grid--l">
				<?php
				while ( $related->have_posts() ) : $related->the_post();
					$r_data = erewhon_get_hospital_card_data();
					?>
					<article class="card card--hospital">
						<div class="card__media">
							<img
								src="<?php echo esc_url( $r_data['image_url'] ); ?>"
								alt="<?php echo esc_attr( $r_data['title'] ); ?>"
								class="card__image"
								loading="lazy"
							>
						</div>
						<div class="card__content">
							<h3 class="card__heading">
								<a href="<?php echo esc_url( $r_data['permalink'] ); ?>">
									<?php echo esc_html( $r_data['title'] ); ?>
								</a>
							</h3>
							<p class="text-s text-secondary line-clamp-2"><?php echo esc_html( strip_tags( $r_data['bio_short'] ) ); ?></p>
						</div>
						<div class="card__footer">
							<a href="<?php echo esc_url( $r_data['permalink'] ); ?>" class="btn btn--outline btn--block">View Details</a>
						</div>
					</article>
				<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php endif; ?>
</main>

<?php
endwhile;
get_footer();
