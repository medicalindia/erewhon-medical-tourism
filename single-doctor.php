<?php
/**
 * Template Name: Single Doctor
 *
 * @package Erewhon
 */

get_header();

while ( have_posts() ) :
	the_post();

	// Get doctor data using helper
	$data = erewhon_get_doctor_card_data();
	?>

<main id="primary" class="site-main">
	<section class="section">
		<div class="container-grid container-grid--sidebar-wide">

			<!-- Main Content -->
			<div class="flow flow--container-gap">
				<header>
					<?php if ( $data['specialty_name'] ) : ?>
						<div class="text-primary font-bold text-uppercase tracking-wide mb-xs">
							<?php echo esc_html( $data['specialty_name'] ); ?>
						</div>
					<?php endif; ?>

					<h1 class="h1"><?php the_title(); ?></h1>

					<?php if ( $data['experience'] ) : ?>
						<div class="flex items-center gap-s text-secondary">
							<svg class="align-middle" width="18" height="18" viewBox="0 0 16 16" fill="none">
								<path d="M8 14A6 6 0 108 2a6 6 0 000 12z" stroke="currentColor" stroke-width="1.5"/>
								<path d="M8 5v3l2 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
							</svg>
							<span><?php echo absint( $data['experience'] ); ?> years experience</span>
						</div>
					<?php endif; ?>
				</header>

				<div class="flow flow--container-gap">
					<!-- Photo & Bio -->
					<div class="grid grid--sidebar gap-l">
						<div>
							<img
								src="<?php echo esc_url( $data['photo_url'] ); ?>"
								alt="<?php echo esc_attr( $data['full_name'] ); ?>"
								class="rounded-l shadow-md w-full h-auto object-cover"
							>
						</div>
						<div class="flow flow--content-gap">
							<div class="text-l text-secondary">
								<?php echo esc_html( $data['bio_short'] ); ?>
							</div>

							<?php if ( ! empty( $data['specialty_pills'] ) ) : ?>
								<div>
									<strong class="block mb-s text-s text-secondary">Specialties</strong>
									<ul class="pills">
										<?php foreach ( $data['specialty_pills'] as $spec ) : ?>
											<li><span class="pill pill--primary"><?php echo esc_html( $spec->name ); ?></span></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Main Body -->
					<div class="stack stack--m">
						<?php the_content(); ?>
					</div>
				</div>
			</div>

			<!-- Sidebar Action Card -->
			<aside>
				<div class="card card--action">
					<h3 class="card__heading">Interested in a consultation?</h3>
					<p class="text-secondary text-m">
						Contact us to schedule an appointment with <?php echo esc_html( $data['full_name'] ); ?>.
					</p>

					<?php
					$specialty = $data['specialty_name'] ? $data['specialty_name'] : 'General';
					$subject = 'Inquiry for ' . $data['full_name'];
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
						<span>Usually responds within 24h</span>
					</div>
				</div>
			</aside>

		</div>

	</section>
</main>

<?php
endwhile;
get_footer();
