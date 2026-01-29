<?php
/**
 * SEO Helpers
 * Schema.org structured data generation
 *
 * @package Erewhon
 */

/**
 * Output JSON-LD Schema in wp_head
 */
function erewhon_output_schema() {
	if ( ! is_single() ) {
		return;
	}

	$schema = null;
	$post_id = get_the_ID();
	$post_type = get_post_type();

	if ( 'doctor' === $post_type ) {
		$schema = erewhon_get_doctor_schema( $post_id );
	} elseif ( 'hospital' === $post_type ) {
		$schema = erewhon_get_hospital_schema( $post_id );
	} elseif ( 'treatment' === $post_type ) {
		$schema = erewhon_get_treatment_schema( $post_id );
	}

	if ( $schema ) {
		echo '<script type="application/ld+json">' . json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'erewhon_output_schema' );

/**
 * Get Doctor Schema (Physician)
 */
function erewhon_get_doctor_schema( $post_id ) {
	$data = erewhon_get_doctor_card_data( $post_id );

	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'Physician',
		'name'     => $data['full_name'],
		'image'    => $data['photo_url'],
		'description' => $data['bio_short'],
		'medicalSpecialty' => $data['specialty_name'],
		'url'      => get_permalink( $post_id ),
	);
}

/**
 * Get Hospital Schema (MedicalOrganization)
 */
function erewhon_get_hospital_schema( $post_id ) {
	$data = erewhon_get_hospital_card_data( $post_id );

	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'MedicalOrganization', // Or Hospital
		'name'     => $data['title'],
		'image'    => $data['image_url'],
		'description' => $data['bio_short'],
		'medicalSpecialty' => array_map( function( $p ) { return $p->name; }, $data['specialty_pills'] ),
		'location' => array(
			'@type' => 'Place',
			'name'  => $data['location'],
		),
		'url'      => get_permalink( $post_id ),
	);
}

/**
 * Get Treatment Schema (MedicalProcedure)
 */
function erewhon_get_treatment_schema( $post_id ) {
	$data = erewhon_get_treatment_card_data( $post_id );

	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'MedicalProcedure',
		'name'     => $data['title'],
		'image'    => $data['image_url'],
		'description' => $data['bio_short'],
		'procedureType' => 'SurgicalProcedure', // Simplification
		'bodyLocation' => $data['category'],
		'howTo' => array(
			'@type' => 'HowTo',
			'name'  => 'Procedure Details',
			'step'  => array(
				array(
					'@type' => 'HowToStep',
					'text'  => 'Duration: ' . $data['procedure_duration']
				),
				array(
					'@type' => 'HowToStep',
					'text'  => 'Recovery: ' . $data['recovery_time']
				)
			)
		),
		'url'      => get_permalink( $post_id ),
	);
}
