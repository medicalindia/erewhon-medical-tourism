<?php
/**
 * Template Name: Single Treatment
 *
 * @package Erewhon
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Get treatment data using helper
	$data = erewhon_get_treatment_card_data();
	?>

<main id="primary" class="site-main">
	<section class="section">
		<div class="container-grid container-grid--sidebar-wide">

			<!-- Main Content -->
			<div class="flow flow--container-gap">
				<header>
					<?php if ( $data['category'] ) : ?>
						<div class="text-primary font-bold text-uppercase tracking-wide mb-xs">
							<?php echo esc_html( $data['category'] ); ?>
						</div>
					<?php endif; ?>

					<h1 class="h1"><?php the_title(); ?></h1>

					<?php if ( $data['bio_short'] ) : ?>
						<div class="text-l text-secondary max-w-md">
							<?php echo esc_html( $data['bio_short'] ); ?>
						</div>
					<?php endif; ?>
				</header>

				<div class="flow flow--container-gap">
					<!-- Featured Image -->
					<div class="relative rounded-xl overflow-hidden shadow-md">
						<img
							src="<?php echo esc_url( $data['image_url'] ); ?>"
							alt="<?php echo esc_attr( $data['title'] ); ?>"
							class="w-full h-auto object-cover"
						>
					</div>

					<!-- Key Stats -->
					<?php if ( $data['procedure_duration'] || $data['recovery_time'] ) : ?>
						<div class="grid grid--cols-2 grid--m p-m bg-surface border rounded-l">
							<?php if ( $data['procedure_duration'] ) : ?>
								<div class="flex items-start gap-s">
									<svg class="text-primary mt-xs" width="20" height="20" viewBox="0 0 16 16" fill="none">
										<path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="1.5"/>
										<path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
									</svg>
									<div>
										<span class="block text-xs text-secondary uppercase font-bold">Duration</span>
										<span class="text-m font-medium"><?php echo esc_html( $data['procedure_duration'] ); ?></span>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( $data['recovery_time'] ) : ?>
								<div class="flex items-start gap-s">
									<svg class="text-primary mt-xs" width="20" height="20" viewBox="0 0 16 16" fill="none">
										<rect x="2" y="7" width="12" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
										<path d="M4 7V5a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="1.5"/>
									</svg>
									<div>
										<span class="block text-xs text-secondary uppercase font-bold">Recovery</span>
										<span class="text-m font-medium"><?php echo esc_html( $data['recovery_time'] ); ?></span>
									</div>
								</div>
							<?php endif; ?>
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
					<h3 class="card__heading">Get a Quote</h3>
					<p class="text-secondary text-m">
						Find out the cost and details for <?php echo esc_html( $data['title'] ); ?>.
					</p>

					<?php
					$specialty = $data['category'] ? $data['category'] : 'Treatment';
					$subject = 'Inquiry for ' . $data['title'];
					$contact_url = '/contact/?subject=' . urlencode( $subject );
					?>

					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn--primary btn--block">
						Inquiry about <?php echo esc_html( $specialty ); ?>
					</a>

					<hr class="border-top w-full my-s">

					<div class="flex items-center gap-s text-s text-secondary">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="1.5"/>
							<path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
						</svg>
						<span>Free initial assessment</span>
					</div>
				</div>
			</aside>

		</div>

	</section>

	<!-- Related Treatments -->
	<?php
	$related = erewhon_get_related_posts( get_the_ID(), 'treatment', 'specialty', 4 );
	if ( $related->have_posts() ) :
	?>
	<section class="section section--bg-muted">
		<div class="container">
			<h2 class="h2 mb-l">Similar Treatments</h2>
			<div class="grid grid--auto-fit-sm grid--l">
				<?php
				while ( $related->have_posts() ) : $related->the_post();
					$r_data = erewhon_get_treatment_card_data();
					?>
					<article class="card card--treatment">
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
							<?php if ( $r_data['category'] ) : ?>
								<div class="card__eyebrow"><?php echo esc_html( $r_data['category'] ); ?></div>
							<?php endif; ?>
						</div>
						<div class="card__footer">
							<a href="<?php echo esc_url( $r_data['permalink'] ); ?>" class="btn btn--outline btn--block">Learn More</a>
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
