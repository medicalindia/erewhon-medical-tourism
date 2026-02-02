<?php
/**
 * Template Name: Treatments Page
 * Displays all treatments in 4-column grid
 * 
 * @package Erewhon
 */

get_header();
?>

<main id="primary" class="site-main">

	<!-- Page Header -->
	<section class="section section--l">
		<div class="container container--text text-center">
			<div class="stack stack--m">
				<h1 class="h1"><?php the_title(); ?></h1>
				<?php if ( get_the_content() ) : ?>
					<div class="lead text-secondary">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<!-- Treatments Grid -->
	<section class="section">
		<div class="container">
			<div class="grid grid--auto-fit-sm grid--l">
			
				<?php
				// Query all treatments
				$treatments = new WP_Query( array(
					'post_type'      => 'treatment',
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => 'publish',
				) );

				if ( $treatments->have_posts() ) :
					while ( $treatments->have_posts() ) : $treatments->the_post();
						
						$data = erewhon_get_treatment_card_data();
						?>

						<article class="card card--treatment">
							
							<!-- Image -->
							<div class="card__media">
								<img
									src="<?php echo esc_url( $data['image_url'] ); ?>"
									alt="<?php echo esc_attr( $data['title'] ); ?>"
									class="card__image"
									loading="lazy"
								>
							</div>

							<!-- Content -->
							<div class="card__content">
								<?php if ( $data['category'] ) : ?>
									<div class="card__eyebrow"><?php echo esc_html( $data['category'] ); ?></div>
								<?php endif; ?>

								<h3 class="card__heading">
									<a href="<?php echo esc_url( $data['permalink'] ); ?>">
										<?php echo esc_html( $data['title'] ); ?>
									</a>
								</h3>

								<?php if ( $data['bio_short'] ) : ?>
									<p class="card__text">
										<?php echo esc_html( wp_trim_words( $data['bio_short'], 15 ) ); ?>
									</p>
								<?php endif; ?>

								<!-- Treatment Meta Pills -->
								<?php if ( $data['procedure_duration'] || $data['recovery_time'] ) : ?>
									<ul class="pills">
										<?php if ( $data['procedure_duration'] ) : ?>
											<li>
												<span class="pill pill--primary">
													<svg class="align-middle" width="14" height="14" viewBox="0 0 16 16" fill="none">
														<path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="1.5"/>
														<path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
													</svg>
													<?php echo esc_html( $data['procedure_duration'] ); ?>
												</span>
											</li>
										<?php endif; ?>
										<?php if ( $data['recovery_time'] ) : ?>
											<li>
												<span class="pill pill--primary">
													<svg class="align-middle" width="14" height="14" viewBox="0 0 16 16" fill="none">
														<rect x="2" y="7" width="12" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
														<path d="M4 7V5a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="1.5"/>
													</svg>
													<?php echo esc_html( $data['recovery_time'] ); ?>
												</span>
											</li>
										<?php endif; ?>
									</ul>
								<?php endif; ?>
							</div>

							<!-- Footer -->
							<div class="card__footer">
								<a href="<?php echo esc_url( $data['permalink'] ); ?>" class="btn btn--outline btn--block">
									Learn More
								</a>
							</div>

						</article>

					<?php
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<div class="col-span-full text-center section--l">
						<p class="text-secondary">
							No treatments found yet.
						</p>
						<?php if ( current_user_can( 'edit_posts' ) ) : ?>
							<a href="<?php echo admin_url( 'post-new.php?post_type=treatment' ); ?>" class="btn btn--primary">
								Add Your First Treatment
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</section>
</main>

<?php
get_footer();