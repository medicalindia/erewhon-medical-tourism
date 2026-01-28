<?php
/**
 * Template Name: Single Treatment
 *
 * @package Erewhon
 */

get_header();

// Get treatment data using helper
$data = erewhon_get_treatment_card_data();
?>

<main id="primary" class="site-main">
	<div class="container">

		<div class="l-container-grid">

			<!-- Main Content -->
			<div class="content-area">
				<header class="page-header mb-xl">
					<?php if ( $data['category'] ) : ?>
						<div class="text-primary font-bold text-uppercase mb-xs" style="letter-spacing: 0.05em;">
							<?php echo esc_html( $data['category'] ); ?>
						</div>
					<?php endif; ?>

					<h1 class="h1 mb-m"><?php the_title(); ?></h1>

					<?php if ( $data['bio_short'] ) : ?>
						<div class="lead text-secondary max-w-md">
							<?php echo esc_html( $data['bio_short'] ); ?>
						</div>
					<?php endif; ?>
				</header>

				<div class="page-content">
					<!-- Featured Image -->
					<div class="mb-xl relative rounded-xl overflow-hidden shadow-md">
						<img
							src="<?php echo esc_url( $data['image_url'] ); ?>"
							alt="<?php echo esc_attr( $data['title'] ); ?>"
							style="width: 100%; height: auto; max-height: 400px; object-fit: cover;"
						>
					</div>

					<!-- Key Stats -->
					<?php if ( $data['procedure_duration'] || $data['recovery_time'] ) : ?>
						<div class="grid grid-2 gap-m mb-xl p-m bg-surface border rounded-l">
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
					<div class="stack-m">
						<?php the_content(); ?>
					</div>
				</div>
			</div>

			<!-- Sidebar Action Card -->
			<aside class="sidebar-area">
				<div class="card card--action">
					<h3 class="card__heading">Get a Quote</h3>
					<p class="text-secondary text-s">
						Find out the cost and details for <?php echo esc_html( $data['title'] ); ?>.
					</p>

					<?php
					$specialty = $data['category'] ? $data['category'] : 'Treatment';
					$subject = 'Inquiry for ' . $data['title'];
					$contact_url = '/contact/?subject=' . urlencode( $subject );
					?>

					<a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn--primary">
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

	</div>
</main>

<?php
get_footer();
