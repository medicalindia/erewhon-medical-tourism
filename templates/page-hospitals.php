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
		<header class="page-header text-center mb-xxl">
			<h1 class="h1 mb-m"><?php the_title(); ?></h1>
			<?php if ( get_the_content() ) : ?>
				<div class="page-intro mx-auto text-secondary max-w-md">
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
					
					$data = erewhon_get_hospital_card_data();
					?>
					
					<article class="card card--hospital">
						
						<!-- Image -->
						<div class="card__media">
							<img 
								src="<?php echo esc_url( $data['image_url'] ); ?>"
								alt="<?php echo esc_attr( $data['title'] ); ?>"
								class="card__image"
								loading="lazy"
							>
							<?php if ( $data['badge'] ) : ?>
								<span class="card__badge"><?php echo esc_html( $data['badge'] ); ?> Accredited</span>
							<?php endif; ?>
						</div>

						<!-- Content -->
						<div class="card__content">
							<?php if ( $data['type_label'] ) : ?>
								<div class="card__eyebrow"><?php echo esc_html( $data['type_label'] ); ?></div>
							<?php endif; ?>
							
							<h3 class="card__heading">
								<a href="<?php echo esc_url( $data['permalink'] ); ?>">
									<?php echo esc_html( $data['title'] ); ?>
								</a>
							</h3>
							
							<?php if ( $data['bio_short'] ) : ?>
								<p class="card__text">
									<?php echo esc_html( wp_trim_words( $data['bio_short'], 30 ) ); ?>
								</p>
							<?php endif; ?>
							
							<!-- Meta Info -->
							<div class="flex flex-wrap gap-l mt-m">
								<?php if ( $data['location'] ) : ?>
									<div class="card__meta">
										<svg class="align-middle" width="16" height="16" viewBox="0 0 16 16" fill="none">
											<path d="M8 8.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" stroke="currentColor" stroke-width="1.5"/>
											<path d="M13 7c0 3.5-5 7-5 7s-5-3.5-5-7a5 5 0 0110 0z" stroke="currentColor" stroke-width="1.5"/>
										</svg>
										<span><?php echo esc_html( $data['location'] ); ?></span>
									</div>
								<?php endif; ?>
								
								<?php if ( $data['bed_capacity'] ) : ?>
									<div class="card__meta">
										<svg class="align-middle" width="16" height="16" viewBox="0 0 16 16" fill="none">
											<rect x="2" y="7" width="12" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
											<path d="M4 7V5a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="1.5"/>
										</svg>
										<span><?php echo absint( $data['bed_capacity'] ); ?> beds</span>
									</div>
								<?php endif; ?>
							</div>
							
							<!-- Specialty Pills -->
							<?php if ( ! empty( $data['specialty_pills'] ) ) : ?>
								<div class="mt-m">
									<ul class="pills">
										<?php foreach ( $data['specialty_pills'] as $spec ) : ?>
											<li><span class="pill pill--secondary"><?php echo esc_html( $spec->name ); ?></span></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>

						<!-- Footer -->
						<div class="card__footer">
							<a href="<?php echo esc_url( $data['permalink'] ); ?>" class="btn btn--primary">
								View Hospital Details
							</a>
						</div>

					</article>
					
				<?php
				endwhile;
				wp_reset_postdata();
			else :
				?>
				<div class="text-center p-xxl">
					<p class="text-secondary mb-l">
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