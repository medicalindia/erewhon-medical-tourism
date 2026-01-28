<?php
/**
 * Template Name: Doctors Page
 * Displays all doctors in card grid
 * 
 * @package Erewhon
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container">
		
		<!-- Page Header -->
		<header class="page-header text-center mb-xxl">
			<h1 class="h1 mb-m"><?php the_title(); ?></h1>
			<?php if ( get_the_content() ) : ?>
				<div class="page-intro mx-auto text-secondary max-w-md">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</header>

		<!-- Doctors Grid -->
		<div class="card-grid" data-cols="3">
			
			<?php
			// Query all doctors
			$doctors = new WP_Query( array(
				'post_type'      => 'doctor',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'post_status'    => 'publish',
			) );

			if ( $doctors->have_posts() ) :
				while ( $doctors->have_posts() ) : $doctors->the_post();
					
					// Get ACF fields
					$first_name = get_field( 'first_name' );
					$last_name  = get_field( 'last_name' );
					$full_name  = trim( $first_name . ' ' . $last_name );
					if ( empty( $full_name ) ) {
						$full_name = get_the_title();
					}
					
					$bio_short   = get_field( 'bio_short' );
					$experience  = get_field( 'experience_years' );
					$status      = get_field( 'availability_status' );
					$languages   = get_field( 'languages_spoken' );
					
					// Get specialty taxonomy
					$specialties = get_the_terms( get_the_ID(), 'specialty' );
					$specialty_name = '';
					$specialty_pills = array();
					
					if ( $specialties && ! is_wp_error( $specialties ) ) {
						$specialty_name = $specialties[0]->name;
						// Get up to 3 specialties for pills
						$specialty_pills = array_slice( $specialties, 0, 3 );
					}
					
					// Profile photo
					$profile_photo = get_field( 'profile_photo' );
					if ( $profile_photo ) {
						$photo_url = wp_get_attachment_image_url( $profile_photo, 'doctor-avatar' );
					} else {
						$photo_url = get_the_post_thumbnail_url( get_the_ID(), 'doctor-avatar' );
					}
					if ( ! $photo_url ) {
						// Better fallback - use initials
						$initials = '';
						if ( $first_name ) {
							$initials .= strtoupper( substr( $first_name, 0, 1 ) );
						}
						if ( $last_name ) {
							$initials .= strtoupper( substr( $last_name, 0, 1 ) );
						}
						$photo_url = 'https://ui-avatars.com/api/?name=' . urlencode( $initials ) . '&size=300&background=6366f1&color=fff&bold=true';
					}
					
					// Status text
					$status_text = '';
					$status_class = '';
					if ( $status === 'active' ) {
						$status_text = 'Available';
						$status_class = 'card__status--active';
					}
					?>
					
					<article class="card card--doctor-compact">
						
						<!-- Avatar -->
						<div class="card__media">
							<div class="card__avatar-wrapper">
								<img 
									src="<?php echo esc_url( $photo_url ); ?>" 
									alt="<?php echo esc_attr( $full_name ); ?>"
									class="card__avatar"
									loading="lazy"
								>
								<?php if ( $status === 'active' ) : ?>
									<span class="card__status <?php echo esc_attr( $status_class ); ?>" title="<?php echo esc_attr( $status_text ); ?>"></span>
								<?php endif; ?>
							</div>
						</div>

						<!-- Content -->
						<div class="card__content">
							<?php if ( $specialty_name ) : ?>
								<div class="card__eyebrow"><?php echo esc_html( $specialty_name ); ?></div>
							<?php endif; ?>
							
							<h3 class="card__heading">
								<a href="<?php the_permalink(); ?>">
									<?php echo esc_html( $full_name ); ?>
								</a>
							</h3>
							
							<?php if ( $bio_short ) : ?>
								<p class="card__text">
									<?php echo esc_html( wp_trim_words( $bio_short, 20 ) ); ?>
								</p>
							<?php endif; ?>
							
							<!-- Specialty Pills -->
							<?php if ( ! empty( $specialty_pills ) ) : ?>
								<ul class="pills">
									<?php foreach ( $specialty_pills as $spec ) : ?>
										<li><span class="pill pill--primary"><?php echo esc_html( $spec->name ); ?></span></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							
							<!-- Experience Badge -->
							<?php if ( $experience ) : ?>
								<div class="card__meta mt-s">
									<svg class="align-middle" width="16" height="16" viewBox="0 0 16 16" fill="none">
										<path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="1.5"/>
										<path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
									</svg>
									<span><?php echo absint( $experience ); ?> years experience</span>
								</div>
							<?php endif; ?>
						</div>

						<!-- Footer -->
						<div class="card__footer">
							<a href="<?php the_permalink(); ?>" class="btn btn--primary w-full">
								View Profile
							</a>
						</div>

					</article>
					
				<?php
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<div class="col-span-full text-center p-xxl">
					<p class="text-secondary mb-l">
						No doctors found yet.
					</p>
					<?php if ( current_user_can( 'edit_posts' ) ) : ?>
						<a href="<?php echo admin_url( 'post-new.php?post_type=doctor' ); ?>" class="btn btn--primary">
							Add Your First Doctor
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</div>

	</div>
</main>

<?php
get_footer();