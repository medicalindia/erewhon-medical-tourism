<?php
/**
 * Erewhon — ACF Field Groups
 * Adapted for Underscores theme (no namespace)
 * 
 * @package Erewhon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =====================================================
 * HELPER FUNCTIONS
 * ===================================================== */

/**
 * Get language choices
 */
function erewhon_language_choices() {
	$choices = array(
		'English' => _x( 'English', 'language', 'erewhon' ),
		'Arabic'  => _x( 'Arabic', 'language', 'erewhon' ),
		'Bangla'  => _x( 'Bangla', 'language', 'erewhon' ),
		'African' => _x( 'African', 'language', 'erewhon' ),
	);
	return apply_filters( 'erewhon/language_choices', $choices );
}

/**
 * Sanitize phone numbers
 */
function erewhon_sanitize_phone( $raw ) {
	$s = trim( (string) $raw );
	if ( $s === '' ) {
		return '';
	}
	$plus   = ( $s[0] === '+' ) ? '+' : '';
	$digits = preg_replace( '/\D+/', '', $s );
	return $plus . $digits;
}

/**
 * Get primary specialty for a post
 */
function erewhon_get_primary_specialty( $post_id ) {
	if ( $post_id <= 0 || ! function_exists( 'get_field' ) ) {
		return 0;
	}
	$val = get_field( 'primary_specialty', $post_id );
	if ( is_array( $val ) ) {
		$val = isset( $val['term_id'] ) ? $val['term_id'] : ( isset( $val['ID'] ) ? $val['ID'] : 0 );
	}
	return (int) $val;
}

/**
 * Get specialty label for context
 */
function erewhon_get_specialty_label( $term_id, $context = 'generic' ) {
	$term_id = (int) $term_id;
	if ( $term_id <= 0 ) {
		return '';
	}

	static $cache = array();
	$key = $term_id . ':' . $context;
	
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}

	$label = '';
	if ( function_exists( 'get_field' ) ) {
		switch ( $context ) {
			case 'doctor':
				$label = get_field( 'doctor_label', 'specialty_' . $term_id );
				break;
			case 'treatment':
				$label = get_field( 'treatment_label', 'specialty_' . $term_id );
				break;
			case 'hospital':
				$label = get_field( 'hospital_label', 'specialty_' . $term_id );
				break;
		}
	}

	if ( empty( $label ) ) {
		$name  = get_term_field( 'name', $term_id, 'specialty' );
		$label = is_wp_error( $name ) ? '' : (string) $name;
	}

	$cache[ $key ] = $label;
	return $label;
}

/* =====================================================
 * REGISTER ACF FIELD GROUPS
 * ===================================================== */
add_action( 'acf/init', 'erewhon_register_acf_fields' );

function erewhon_register_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$LANG_CHOICES = erewhon_language_choices();

	/* -----------------------------------------------------------
	 * GLOBAL OPTIONS PAGE
	 * ----------------------------------------------------------- */
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title'   => 'Erewhon Settings',
			'menu_title'   => 'Erewhon Settings',
			'menu_slug'    => 'erewhon-settings',
			'capability'   => 'manage_options',
			'redirect'     => false,
			'position'     => '59.5',
			'icon_url'     => 'dashicons-admin-generic',
			'show_in_rest' => true,
		) );
	}

	/* -----------------------------------------------------------
	 * DOCTOR FIELDS
	 * ----------------------------------------------------------- */
	acf_add_local_field_group( array(
		'key'          => 'group_doctor_fields',
		'title'        => 'Doctor Details',
		'location'     => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'doctor',
				),
			),
		),
		'position'     => 'acf_after_title',
		'menu_order'   => 0,
		'style'        => 'seamless',
		'show_in_rest' => true,
		'fields'       => array(
			// Identity Tab
			array(
				'key'       => 'tab_doc_identity',
				'type'      => 'tab',
				'label'     => 'Identity',
			),
			array(
				'key'      => 'field_doc_first_name',
				'name'     => 'first_name',
				'label'    => 'First Name',
				'type'     => 'text',
				'required' => 1,
			),
			array(
				'key'      => 'field_doc_last_name',
				'name'     => 'last_name',
				'label'    => 'Last Name',
				'type'     => 'text',
				'required' => 1,
			),
			array(
				'key'           => 'field_doc_profile_photo',
				'name'          => 'profile_photo',
				'label'         => 'Profile Photo',
				'type'          => 'image',
				'return_format' => 'id',
				'mime_types'    => 'jpg,jpeg,png,webp,svg',
			),
			array(
				'key'       => 'field_doc_bio_short',
				'name'      => 'bio_short',
				'label'     => 'Bio Short',
				'type'      => 'textarea',
				'maxlength' => 300,
			),
			array(
				'key'     => 'field_doc_bio_long',
				'name'    => 'bio_long',
				'label'   => 'Bio Long',
				'type'    => 'wysiwyg',
				'toolbar' => 'basic',
			),
			// Professional Tab
			array(
				'key'   => 'tab_doc_prof',
				'type'  => 'tab',
				'label' => 'Professional',
			),
			array(
				'key'        => 'field_doc_specialties',
				'name'       => 'specialties',
				'label'      => 'Specialties',
				'type'       => 'taxonomy',
				'taxonomy'   => 'specialty',
				'field_type' => 'checkbox',
			),
			array(
				'key'      => 'field_doc_experience_years',
				'name'     => 'experience_years',
				'label'    => 'Years of Experience',
				'type'     => 'number',
				'min'      => 0,
				'step'     => 1,
				'append'   => 'yrs',
			),
			array(
				'key'     => 'field_doc_languages',
				'name'    => 'languages_spoken',
				'label'   => 'Languages Spoken',
				'type'    => 'checkbox',
				'choices' => $LANG_CHOICES,
			),
			array(
				'key'           => 'field_doc_hospital_affiliation',
				'name'          => 'hospital_affiliation',
				'label'         => 'Hospital Affiliation',
				'type'          => 'relationship',
				'post_type'     => array( 'hospital' ),
				'return_format' => 'id',
			),
			// Availability Tab
			array(
				'key'   => 'tab_doc_avail',
				'type'  => 'tab',
				'label' => 'Availability',
			),
			array(
				'key'     => 'field_doc_availability_status',
				'name'    => 'availability_status',
				'label'   => 'Availability Status',
				'type'    => 'select',
				'choices' => array(
					'active'   => 'Active',
					'inactive' => 'Inactive',
				),
			),
		),
	) );

	/* -----------------------------------------------------------
	 * HOSPITAL FIELDS
	 * ----------------------------------------------------------- */
	acf_add_local_field_group( array(
		'key'          => 'group_hospital_fields',
		'title'        => 'Hospital Details',
		'location'     => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'hospital',
				),
			),
		),
		'position'     => 'acf_after_title',
		'menu_order'   => 0,
		'style'        => 'seamless',
		'show_in_rest' => true,
		'fields'       => array(
			// Profile Tab
			array(
				'key'   => 'tab_hosp_profile',
				'type'  => 'tab',
				'label' => 'Profile',
			),
			array(
				'key'     => 'field_hosp_type',
				'name'    => 'hospital_type',
				'label'   => 'Hospital Type',
				'type'    => 'select',
				'choices' => array(
					'multi'  => 'Multi-specialty',
					'super'  => 'Super-specialty',
					'clinic' => 'Clinic',
				),
			),
			array(
				'key'     => 'field_hosp_accreditation',
				'name'    => 'accreditation',
				'label'   => 'Accreditation',
				'type'    => 'checkbox',
				'choices' => array(
					'JCI'  => 'JCI',
					'NABH' => 'NABH',
					'ISO'  => 'ISO',
				),
			),
			array(
				'key'   => 'field_hosp_bio_short',
				'name'  => 'bio_short',
				'label' => 'Bio Short',
				'type'  => 'textarea',
			),
			array(
				'key'     => 'field_hosp_bio_long',
				'name'    => 'bio_long',
				'label'   => 'Bio Long',
				'type'    => 'wysiwyg',
				'toolbar' => 'basic',
			),
			// Location Tab
			array(
				'key'   => 'tab_hosp_location',
				'type'  => 'tab',
				'label' => 'Location & Capacity',
			),
			array(
				'key'        => 'field_hosp_destinations',
				'name'       => 'hospital_destinations',
				'label'      => 'Destination(s)',
				'type'       => 'taxonomy',
				'taxonomy'   => 'destination',
				'field_type' => 'checkbox',
			),
			array(
				'key'   => 'field_hosp_bed_capacity',
				'name'  => 'bed_capacity',
				'label' => 'Bed Capacity',
				'type'  => 'number',
				'min'   => 0,
				'step'  => 1,
			),
		),
	) );

	/* -----------------------------------------------------------
	 * TREATMENT FIELDS
	 * ----------------------------------------------------------- */
	acf_add_local_field_group( array(
		'key'          => 'group_treatment_fields',
		'title'        => 'Treatment Details',
		'location'     => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'treatment',
				),
			),
		),
		'position'     => 'acf_after_title',
		'menu_order'   => 0,
		'style'        => 'seamless',
		'show_in_rest' => true,
		'fields'       => array(
			// Basics Tab
			array(
				'key'   => 'tab_trt_basics',
				'type'  => 'tab',
				'label' => 'Basics',
			),
			array(
				'key'        => 'field_trt_specialty',
				'name'       => 'treatment_specialty',
				'label'      => 'Medical Specialty',
				'type'       => 'taxonomy',
				'taxonomy'   => 'specialty',
				'field_type' => 'radio',
			),
			array(
				'key'     => 'field_trt_description',
				'name'    => 'treatment_description',
				'label'   => 'Treatment Description',
				'type'    => 'wysiwyg',
				'toolbar' => 'basic',
			),
			array(
				'key'   => 'field_trt_procedure_duration',
				'name'  => 'procedure_duration',
				'label' => 'Procedure Duration',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_trt_recovery_time',
				'name'  => 'recovery_time',
				'label' => 'Recovery Time',
				'type'  => 'text',
			),
			// Cost Tab
			array(
				'key'   => 'tab_trt_cost',
				'type'  => 'tab',
				'label' => 'Cost',
			),
			array(
				'key'   => 'field_trt_bio_short',
				'name'  => 'bio_short',
				'label' => 'Bio Short',
				'type'  => 'textarea',
			),
		),
	) );
}