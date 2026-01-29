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

	<!-- Doctors Grid -->
	<section class="section">
		<div class="container">
			<div class="grid grid--auto-fit-md grid--l">
			
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
						
						$data = erewhon_get_doctor_card_data();
						?>

						<article class="card card--doctor-compact">

							<!-- Avatar -->
							<div class="card__media">
								<div class="card__avatar-wrapper">
									<img
										src="<?php echo esc_url( $data['photo_url'] ); ?>"
										alt="<?php echo esc_attr( $data['full_name'] ); ?>"
										class="card__avatar"
										loading="lazy"
									>
									<?php if ( $data['status'] === 'active' ) : ?>
										<span class="card__status <?php echo esc_attr( $data['status_class'] ); ?>" title="<?php echo esc_attr( $data['status_text'] ); ?>"></span>
									<?php endif; ?>
								</div>
							</div>

							<!-- Content -->
							<div class="card__content">
								<?php if ( $data['specialty_name'] ) : ?>
									<div class="card__eyebrow"><?php echo esc_html( $data['specialty_name'] ); ?></div>
								<?php endif; ?>

								<h3 class="card__heading">
									<a href="<?php echo esc_url( $data['permalink'] ); ?>">
										<?php echo esc_html( $data['full_name'] ); ?>
									</a>
								</h3>

								<?php if ( $data['bio_short'] ) : ?>
									<p class="card__text">
										<?php echo esc_html( wp_trim_words( $data['bio_short'], 20 ) ); ?>
									</p>
								<?php endif; ?>

								<!-- Specialty Pills -->
								<?php if ( ! empty( $data['specialty_pills'] ) ) : ?>
									<ul class="pills">
										<?php foreach ( $data['specialty_pills'] as $spec ) : ?>
											<li><span class="pill pill--primary"><?php echo esc_html( $spec->name ); ?></span></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>

								<!-- Experience Badge -->
								<?php if ( $data['experience'] ) : ?>
									<div class="card__meta">
										<svg class="align-middle" width="16" height="16" viewBox="0 0 16 16" fill="none">
											<path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="1.5"/>
											<path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
										</svg>
										<span><?php echo absint( $data['experience'] ); ?> years experience</span>
									</div>
								<?php endif; ?>
							</div>

							<!-- Footer -->
							<div class="card__footer">
								<a href="<?php echo esc_url( $data['permalink'] ); ?>" class="btn btn--primary btn--block">
									View Profile
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
	</section>
</main>

<?php
get_footer();