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
	<div class="container">
		
		<!-- Page Header -->
		<header class="page-header" style="margin-bottom: var(--space-xxl); text-align: center;">
			<h1 style="font-size: var(--h1); margin-bottom: var(--space-m);"><?php the_title(); ?></h1>
			<?php if ( get_the_content() ) : ?>
				<div class="page-intro" style="max-width: 42rem; margin: 0 auto; color: var(--text-secondary);">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</header>

		<!-- Treatments Grid -->
		<div class="card-grid" data-cols="4">
			
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
					
					// Get ACF fields
					$bio_short          = get_field( 'bio_short' );
					$procedure_duration = get_field( 'procedure_duration' );
					$recovery_time      = get_field( 'recovery_time' );
					
					// Get specialty taxonomy
					$specialties = get_the_terms( get_the_ID(), 'specialty' );
					$category = '';
					if ( $specialties && ! is_wp_error( $specialties ) ) {
						$category = $specialties[0]->name;
					}
					
					// Featured image
					$image_url = get_the_post_thumbnail_url( get_the_ID(), 'treatment-card' );
					if ( ! $image_url ) {
						// Medical-themed placeholder
						$image_url = 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=400&h=300&fit=crop';
					}
					?>
					
					<article class="card card--treatment">
						
						<!-- Image -->
						<div class="card__media">
							<img 
								src="<?php echo esc_url( $image_url ); ?>" 
								alt="<?php echo esc_attr( get_the_title() ); ?>"
								class="card__image"
								loading="lazy"
							>
						</div>

						<!-- Content -->
						<div class="card__content">
							<?php if ( $category ) : ?>
								<div class="card__eyebrow"><?php echo esc_html( $category ); ?></div>
							<?php endif; ?>
							
							<h3 class="card__heading">
								<a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h3>
							
							<?php if ( $bio_short ) : ?>
								<p class="card__text">
									<?php echo esc_html( wp_trim_words( $bio_short, 15 ) ); ?>
								</p>
							<?php endif; ?>
							
							<!-- Treatment Meta Pills -->
							<?php if ( $procedure_duration || $recovery_time ) : ?>
								<ul class="pills" style="margin-top: var(--space-m);">
									<?php if ( $procedure_duration ) : ?>
										<li>
											<span class="pill pill--outline">
												<svg width="14" height="14" viewBox="0 0 16 16" fill="none" style="vertical-align: middle; margin-right: 4px;">
													<path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="1.5"/>
													<path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
												</svg>
												<?php echo esc_html( $procedure_duration ); ?>
											</span>
										</li>
									<?php endif; ?>
									<?php if ( $recovery_time ) : ?>
										<li>
											<span class="pill pill--outline">
												<svg width="14" height="14" viewBox="0 0 16 16" fill="none" style="vertical-align: middle; margin-right: 4px;">
													<rect x="2" y="7" width="12" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
													<path d="M4 7V5a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="1.5"/>
												</svg>
												<?php echo esc_html( $recovery_time ); ?>
											</span>
										</li>
									<?php endif; ?>
								</ul>
							<?php endif; ?>
						</div>

						<!-- Footer -->
						<div class="card__footer">
							<a href="<?php the_permalink(); ?>" class="btn btn--outline" style="width: 100%;">
								Learn More
							</a>
						</div>

					</article>
					
				<?php
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<div style="grid-column: 1 / -1; text-align: center; padding: var(--space-xxl);">
					<p style="color: var(--text-secondary); margin-bottom: var(--space-l);">
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
</main>

<?php
get_footer();