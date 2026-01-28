<?php
/**
 * Data Helpers
 * Functions to process and return data for templates
 *
 * @package Erewhon
 */

/**
 * Get Doctor Card Data
 *
 * @param int|null $post_id Post ID. Defaults to current post.
 * @return array Data for the doctor card.
 */
function erewhon_get_doctor_card_data( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	// Get ACF fields
	$first_name = get_field( 'first_name', $post_id );
	$last_name  = get_field( 'last_name', $post_id );
	$full_name  = trim( $first_name . ' ' . $last_name );
	if ( empty( $full_name ) ) {
		$full_name = get_the_title( $post_id );
	}

	$bio_short   = get_field( 'bio_short', $post_id );
	$experience  = get_field( 'experience_years', $post_id );
	$status      = get_field( 'availability_status', $post_id );
	$languages   = get_field( 'languages_spoken', $post_id );

	// Get specialty taxonomy
	$specialties = get_the_terms( $post_id, 'specialty' );
	$specialty_name = '';
	$specialty_pills = array();

	if ( $specialties && ! is_wp_error( $specialties ) ) {
		$specialty_name = $specialties[0]->name;
		// Get up to 3 specialties for pills
		$specialty_pills = array_slice( $specialties, 0, 3 );
	}

	// Profile photo
	$profile_photo = get_field( 'profile_photo', $post_id );
	$photo_url = '';
	if ( $profile_photo ) {
		$photo_url = wp_get_attachment_image_url( $profile_photo, 'doctor-avatar' );
	} else {
		$photo_url = get_the_post_thumbnail_url( $post_id, 'doctor-avatar' );
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

	return array(
		'full_name'       => $full_name,
		'bio_short'       => $bio_short,
		'experience'      => $experience,
		'status'          => $status,
		'status_text'     => $status_text,
		'status_class'    => $status_class,
		'photo_url'       => $photo_url,
		'specialty_name'  => $specialty_name,
		'specialty_pills' => $specialty_pills,
		'permalink'       => get_permalink( $post_id ),
	);
}

/**
 * Get Hospital Card Data
 *
 * @param int|null $post_id Post ID. Defaults to current post.
 * @return array Data for the hospital card.
 */
function erewhon_get_hospital_card_data( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	// Get ACF fields
	$hospital_type  = get_field( 'hospital_type', $post_id );
	$bio_short      = get_field( 'bio_short', $post_id );
	$bed_capacity   = get_field( 'bed_capacity', $post_id );
	$accreditation  = get_field( 'accreditation', $post_id ); // Array of checkboxes

	// Get taxonomies
	$destinations = get_the_terms( $post_id, 'destination' );
	$location = '';
	if ( $destinations && ! is_wp_error( $destinations ) ) {
		$location = $destinations[0]->name;
	}

	$specialties = get_the_terms( $post_id, 'specialty' );
	$specialty_pills = array();
	if ( $specialties && ! is_wp_error( $specialties ) ) {
		$specialty_pills = array_slice( $specialties, 0, 4 );
	}

	// Featured image
	$image_url = get_the_post_thumbnail_url( $post_id, 'hospital-featured' );
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

	return array(
		'title'           => get_the_title( $post_id ),
		'type_label'      => $type_label,
		'bio_short'       => $bio_short,
		'location'        => $location,
		'bed_capacity'    => $bed_capacity,
		'image_url'       => $image_url,
		'badge'           => $badge,
		'specialty_pills' => $specialty_pills,
		'permalink'       => get_permalink( $post_id ),
	);
}

/**
 * Get Treatment Card Data
 *
 * @param int|null $post_id Post ID. Defaults to current post.
 * @return array Data for the treatment card.
 */
function erewhon_get_treatment_card_data( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	// Get ACF fields
	$bio_short          = get_field( 'bio_short', $post_id );
	$procedure_duration = get_field( 'procedure_duration', $post_id );
	$recovery_time      = get_field( 'recovery_time', $post_id );

	// Get specialty taxonomy
	$specialties = get_the_terms( $post_id, 'specialty' );
	$category = '';
	if ( $specialties && ! is_wp_error( $specialties ) ) {
		$category = $specialties[0]->name;
	}

	// Featured image
	$image_url = get_the_post_thumbnail_url( $post_id, 'treatment-card' );
	if ( ! $image_url ) {
		// Medical-themed placeholder
		$image_url = 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=400&h=300&fit=crop';
	}

	return array(
		'title'              => get_the_title( $post_id ),
		'category'           => $category,
		'bio_short'          => $bio_short,
		'procedure_duration' => $procedure_duration,
		'recovery_time'      => $recovery_time,
		'image_url'          => $image_url,
		'permalink'          => get_permalink( $post_id ),
	);
}
