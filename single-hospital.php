<?php
/**
 * Template Name: Single Hospital
 *
 * @package Erewhon
 */

get_header();

// Get hospital data using helper
$data = erewhon_get_hospital_card_data();
?>

<main id="primary" class="site-main">
	<div class="container">

		<div class="l-container-grid">

			<!-- Main Content -->
			<div class="content-area">
				<header class="page-header mb-xl">
					<?php if ( $data['type_label'] ) : ?>
						<div class="text-primary font-bold text-uppercase mb-xs" style="letter-spacing: 0.05em;">
							<?php echo esc_html( $data['type_label'] ); ?>
						</div>
					<?php endif; ?>

					<h1 class="h1 mb-m"><?php the_title(); ?></h1>

					<div class="flex flex-wrap gap-l text-secondary">
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

				<div class="page-content">
					<!-- Featured Image -->
					<div class="mb-xl relative rounded-xl overflow-hidden shadow-md">
						<img
							src="<?php echo esc_url( $data['image_url'] ); ?>"
							alt="<?php echo esc_attr( $data['title'] ); ?>"
							style="width: 100%; height: auto; max-height: 500px; object-fit: cover;"
						>
						<?php if ( $data['badge'] ) : ?>
							<span class="absolute top-0 right-0 m-m bg-success text-white font-bold text-xs uppercase px-s py-xs rounded-l">
								<?php echo esc_html( $data['badge'] ); ?> Accredited
							</span>
						<?php endif; ?>
					</div>

					<div class="lead mb-l">
						<?php echo esc_html( $data['bio_short'] ); ?>
					</div>

					<?php if ( ! empty( $data['specialty_pills'] ) ) : ?>
						<div class="mb-xl">
							<strong class="block mb-s text-s text-secondary">Key Specialties</strong>
							<ul class="pills">
								<?php foreach ( $data['specialty_pills'] as $spec ) : ?>
									<li><span class="pill pill--secondary"><?php echo esc_html( $spec->name ); ?></span></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<!-- Main Body -->
					<div class="stack-m">
						<?php the_content(); ?>
					</div>
				</div>
			</div>

			<!-- Sidebar Action Card -->
			<aside class="sidebar-area">
				<div class="card card--action">
					<h3 class="card__heading">Book a Consultation</h3>
					<p class="text-secondary text-s">
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

					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn--primary">
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

	</div>
</main>

<?php
get_footer();
