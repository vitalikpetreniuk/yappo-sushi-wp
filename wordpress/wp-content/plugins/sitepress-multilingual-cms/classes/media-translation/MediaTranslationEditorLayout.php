<?php

namespace WPML\MediaTranslation;

class MediaTranslationEditorLayout implements \IWPML_Action {
	public function add_hooks() {
		if ( \WPML_Media_Duplication_Setup::isTranslateMediaLibraryTextsEnabled() ) {
			add_filter( 'wpml_tm_job_layout', [ $this, 'group_media_fields' ] );
			add_filter( 'wpml_tm_adjust_translation_fields', [ $this, 'set_custom_labels' ] );
		}
	}

	public function group_media_fields( $fields ) {

		$media_fields = [];

		foreach ( $fields as $k => $field ) {
			$media_field = $this->is_media_field( $field );
			if ( $media_field ) {
				unset( $fields[ $k ] );
				$media_fields[ $media_field['attachment_id'] ][] = $field;
			}
		}

		if ( $media_fields ) {

			$media_section_field = [
				'field_type'    => 'tm-section',
				'title'         => __( 'Media', 'wpml-media' ),
				'fields'        => [],
				'empty'         => false,
				'empty_message' => '',
				'sub_title'     => '',
			];

			foreach ( $media_fields as $attachment_id => $media_field ) {

				$media_group_field = [
					'title'      => '',
					'divider'    => false,
					'field_type' => 'tm-group',
					'fields'     => $media_field,
				];

				$image       = wp_get_attachment_image_src( (int) $attachment_id, [ 100, 100 ] );
				$image_field = [
					'field_type' => 'wcml-image',
					'divider'    => $media_field !== end( $media_fields ),
					'image_src'  => $image[0],
					'fields'     => [ $media_group_field ],
				];

				$media_section_field['fields'][] = $image_field;

			}

			$fields[] = $media_section_field;
		}

		return array_values( $fields );
	}

	private function is_media_field( $field ) {
		$media_field = [];
		if ( is_string( $field ) && preg_match( '/^media_([0-9]+)_([a-z_]+)/', $field, $match ) ) {
			$media_field = [
				'attachment_id' => (int) $match[1],
				'label'         => $match[2],
			];
		}

		return $media_field;
	}

	public function set_custom_labels( $fields ) {

		foreach ( $fields as $k => $field ) {
			$media_field = $this->is_media_field( $field['field_type'] );
			if ( $media_field ) {
				$fields[ $k ]['title'] = $this->get_field_label( $media_field );
			}
		}

		return $fields;
	}

	private function get_field_label( $media_field ) {

		switch ( $media_field['label'] ) {
			case 'title':
				$label = __( 'Title', 'wpml-media' );
				break;
			case 'caption':
				$label = __( 'Caption', 'wpml-media' );
				break;
			case 'description':
				$label = __( 'Description', 'wpml-media' );
				break;
			case 'alt_text':
				$label = __( 'Alt Text', 'wpml-media' );
				break;
			default:
				$label = '';
		}

		return $label;
	}
}
