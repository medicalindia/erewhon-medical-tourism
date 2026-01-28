<?php
/**
 * Template Name: Hospitals Page
 * Displays all hospitals in stack layout
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

		<!-- Hospitals Stack -->
		<div class="card-stack">
			
			<?php
			// Query all hospitals
			$hospitals = new WP_Query( array(
				'post_type'      => 'hospital',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'post_status'    => 'publish',
			) );

			if ( $hospitals->have_posts() ) :
				while ( $hospitals->have_posts() ) : $hospitals->the_post();
					
					// Get ACF fields
					$hospital_type  = get_field( 'hospital_type' );
					$bio_short      = get_field( 'bio_short' );
					$bed_capacity   = get_field( 'bed_capacity' );
					$accreditation  = get_field( 'accreditation' ); // Array of checkboxes
					
					// Get taxonomies
					$destinations = get_the_terms( get_the_ID(), 'destination' );
					$location = '';
					if ( $destinations && ! is_wp_error( $destinations ) ) {
						$location = $destinations[0]->name;
					}
					
					$specialties = get_the_terms( get_the_ID(), 'specialty' );
					$specialty_pills = array();
					if ( $specialties && ! is_wp_error( $specialties ) ) {
						$specialty_pills = array_slice( $specialties, 0, 4 );
					}
					
					// Featured image
					$image_url = get_the_post_thumbnail_url( get_the_ID(), 'hospital-featured' );
					if ( ! $image_url ) {
						$image_url = 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=800&h=500&fit=crop';
					}
					
					// Type labels
					$type_labels = array(
						'multi'  => 'Multi-Specialty Hospital',
						'super'  => 'Super-Specialty Hospital',
						'clinic' => 'Clinic',
					);
					$type_label = $type_labels[ $hospital_type ] ?? $hospital_type;
					
					// Accreditation badge (show first one)
					$badge = '';
					if ( is_array( $accreditation ) && ! empty( $accreditation ) ) {
						$badge = $accreditation[0];
					}
					?>
					
					<article class="card card--hospital">
						
						<!-- Image -->
						<div class="card__media">
							<img 
								src="<?php echo esc_url( $image_url ); ?>" 
								alt="<?php echo esc_attr( get_the_title() ); ?>"
								class="card__image"
								loading="lazy"
							>
							<?php if ( $badge ) : ?>
								<span class="card__badge"><?php echo esc_html( $badge ); ?> Accredited</span>
							<?php endif; ?>
						</div>

						<!-- Content -->
						<div class="card__content">
							<?php if ( $type_label ) : ?>
								<div class="card__eyebrow"><?php echo esc_html( $type_label ); ?></div>
							<?php endif; ?>
							
							<h3 class="card__heading">
								<a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h3>
							
							<?php if ( $bio_short ) : ?>
								<p class="card__text">
									<?php echo esc_html( wp_trim_words( $bio_short, 30 ) ); ?>
								</p>
							<?php endif; ?>
							
							<!-- Meta Info -->
							<div style="display: flex; flex-wrap: wrap; gap: var(--space-l); margin-top: var(--space-m);">
								<?php if ( $location ) : ?>
									<div class="card__meta">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="vertical-align: middle;">
											<path d="M8 8.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" stroke="currentColor" stroke-width="1.5"/>
											<path d="M13 7c0 3.5-5 7-5 7s-5-3.5-5-7a5 5 0 0110 0z" stroke="currentColor" stroke-width="1.5"/>
										</svg>
										<span><?php echo esc_html( $location ); ?></span>
									</div>
								<?php endif; ?>
								
								<?php if ( $bed_capacity ) : ?>
									<div class="card__meta">
										<svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="vertical-align: middle;">
											<rect x="2" y="7" width="12" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
											<path d="M4 7V5a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="1.5"/>
										</svg>
										<span><?php echo absint( $bed_capacity ); ?> beds</span>
									</div>
								<?php endif; ?>
							</div>
							
							<!-- Specialty Pills -->
							<?php if ( ! empty( $specialty_pills ) ) : ?>
								<div style="margin-top: var(--space-m);">
									<ul class="pills">
										<?php foreach ( $specialty_pills as $spec ) : ?>
											<li><span class="pill pill--secondary"><?php echo esc_html( $spec->name ); ?></span></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>

						<!-- Footer -->
						<div class="card__footer">
							<a href="<?php the_permalink(); ?>" class="btn btn--primary">
								View Hospital Details
							</a>
						</div>

					</article>
					
				<?php
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<div style="text-align: center; padding: var(--space-xxl);">
					<p style="color: var(--text-secondary); margin-bottom: var(--space-l);">
						No hospitals found yet.
					</p>
					<?php if ( current_user_can( 'edit_posts' ) ) : ?>
						<a href="<?php echo admin_url( 'post-new.php?post_type=hospital' ); ?>" class="btn btn--primary">
							Add Your First Hospital
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>

	</div>
</main>

<?php
get_footer();