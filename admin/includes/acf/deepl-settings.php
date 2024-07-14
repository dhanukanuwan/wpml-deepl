<?php
/**
 * Settings page ACF fields.
 *
 * @link       https://hashcodeab.se
 * @since      1.0.0
 *
 * @package    Deepl_Wpml
 * @subpackage Deepl_Wpml/admin
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group(
		array(
			'key'      => 'group_deepl_settings',
			'title'    => __( 'DeepL Settings', 'deepl-wpml' ),
			'fields'   => array(
				array(
					'key'                 => 'field_deepl_api_key',
					'label'               => __( 'DeepL API Key', 'deepl-wpml' ),
					'name'                => 'deepl_api_key',
					'type'                => 'text',
					'wpml_cf_preferences' => 3,
				),
				array(
					'key'                 => 'field_deepl_review_users',
					'label'               => __( 'Review Users', 'deepl-wpml' ),
					'name'                => 'deepl_review_users',
					'type'                => 'user',
					'instructions'        => __( 'Please select users who are reviewing the translations once translations are marked as ready for review', 'deepl-wpml' ),
					'wpml_cf_preferences' => 3,
					'role'                => array(
						0 => 'administrator',
					),
					'return_format'       => 'array',
					'multiple'            => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'deepl-wpml-settings',
					),
				),
			),
			'active'   => true,
		)
	);

endif;
