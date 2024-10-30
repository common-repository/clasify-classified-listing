<?php
namespace Clasify\Classified\Admin\MetaBoxes\Components;

use Clasify\Classified\Traitval\Traitval;

class Fields {

	use Traitval;

	protected function preset_data() {
		$field_data[ $this->prefix . 'pricing' ]     = array(
			'type'    => 'number',
			'name'    => esc_html__( 'Price', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'pricing',
			'columns' => 3,
			'desc'    => esc_html__( 'Price', 'clasify-classified-listing' ),
		);
		$field_data[ $this->prefix . 'beds' ]        = array(
			'type'    => 'number',
			'name'    => esc_html__( 'Bedroom', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'beds',
			'columns' => 3,
			'desc'    => esc_html__( 'Number of bedroom', 'clasify-classified-listing' ),
		);
		$field_data[ $this->prefix . 'bath' ]        = array(
			'type'    => 'number',
			'name'    => esc_html__( 'Washroom', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'bath',
			'columns' => 3,
			'desc'    => esc_html__( 'Number of washroom', 'clasify-classified-listing' ),
		);
		$field_data[ $this->prefix . 'area' ]        = array(
			'type'    => 'number',
			'name'    => esc_html__( 'Area Size', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'area',
			'columns' => 3,
			'desc'    => esc_html__( 'Area of the listing', 'clasify-classified-listing' ),
		);
		$field_data[ $this->prefix . 'garage' ]      = array(
			'type'    => 'number',
			'name'    => esc_html__( 'Garage', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'garage',
			'columns' => 3,
			'desc'    => esc_html__( 'Number of Garage', 'clasify-classified-listing' ),
		);
		$field_data[ $this->prefix . 'year' ]        = array(
			'type'    => 'number',
			'name'    => esc_html__( 'Year', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'year',
			'columns' => 3,
			'desc'    => esc_html__( 'Build Year of the listing', 'clasify-classified-listing' ),
		);
		$field_data[ $this->prefix . 'address' ]     = array(
			'type'    => 'text',
			'name'    => esc_html__( 'Address', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'address',
			'columns' => 3,
		);
		$field_data[ $this->prefix . 'zip' ]         = array(
			'type'    => 'text',
			'name'    => esc_html__( 'Zip/Post Code', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'zip',
			'columns' => 3,
		);
		$field_data[ $this->prefix . 'phone' ]       = array(
			'type'    => 'text',
			'name'    => esc_html__( 'Phone', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'phone',
			'columns' => 3,
		);
		$field_data[ $this->prefix . 'email' ]       = array(
			'type'    => 'text',
			'name'    => esc_html__( 'Email', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'email',
			'columns' => 3,
		);
		$field_data[ $this->prefix . 'website' ]     = array(
			'type'    => 'url',
			'name'    => esc_html__( 'Website', 'clasify-classified-listing' ),
			'id'      => $this->prefix . 'website',
			'columns' => 3,
		);
		$field_data[ $this->prefix . 'gallery' ]     = array(
			'type' => 'image_advanced',
			'name' => esc_html__( 'Gallery', 'clasify-classified-listing' ),
			'id'   => $this->prefix . 'gallery',
			'desc' => esc_html__( 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.', 'clasify-classified-listing' ),
		);
		$field_data[ $this->prefix . 'features' ]    = array(
			'id'         => $this->prefix . 'features',
			'name'       => esc_html__( 'Advance Features', 'clasify-classified-listing' ),
			'type'       => 'group',
			'clone'      => true,
			'columns'    => 12,
			'sort_clone' => true,
			'fields'     => array(
				array(
					'type'    => 'select',
					'name'    => esc_html__( 'Icon', 'clasify-classified-listing' ),
					'id'      => $this->prefix . 'features_icon',
					'options' => array(
						''                    => '',
						'fa fa-shower'        => esc_html__( 'fa fa-shower', 'clasify-classified-listing' ),
						'fa fa-bars'          => esc_html__( 'fa fa-bars', 'clasify-classified-listing' ),
						'fa fa-bath'          => esc_html__( 'fa fa-bath', 'clasify-classified-listing' ),
						'fa fa-bed'           => esc_html__( 'fa fa-bed', 'clasify-classified-listing' ),
						'fa fa-beer'          => esc_html__( 'fa fa-beer', 'clasify-classified-listing' ),
						'fa fa-bicycle'       => esc_html__( 'fa fa-bicycle', 'clasify-classified-listing' ),
						'fa fa-biking'        => esc_html__( 'fa fa-biking', 'clasify-classified-listing' ),
						'fa fa-bus'           => esc_html__( 'fa fa-bus', 'clasify-classified-listing' ),
						'fa fa-space-shuttle' => esc_html__( 'fa fa-space-shuttle', 'clasify-classified-listing' ),
						'fa fa-taxi'          => esc_html__( 'fa fa-taxi', 'clasify-classified-listing' ),
						'fa fa-television'    => esc_html__( 'fa fa-television', 'clasify-classified-listing' ),
						'fa fa-university'    => esc_html__( 'fa fa-university ', 'clasify-classified-listing' ),
						'fa fa-user-secret'   => esc_html__( 'fa fa-user-secret ', 'clasify-classified-listing' ),
						'fa fa-users'         => esc_html__( 'fa fa-users ', 'clasify-classified-listing' ),
						'fa fa-ambulance'     => esc_html__( 'fa fa-ambulance ', 'clasify-classified-listing' ),
						'fa fa-bicycle'       => esc_html__( 'fa fa-bicycle ', 'clasify-classified-listing' ),
						'fa fa-subway'        => esc_html__( 'fa fa-subway ', 'clasify-classified-listing' ),
						'fa fa-plane'         => esc_html__( 'fa fa-plane ', 'clasify-classified-listing' ),
					),
					'columns' => '4',
				),
				array(
					'type'    => 'text',
					'name'    => esc_html__( 'Name', 'clasify-classified-listing' ),
					'id'      => $this->prefix . 'features_name',
					'columns' => '8',
				),
			),
		);
		$field_data[ $this->prefix . 'amenities' ]   = array(
			'id'         => $this->prefix . 'amenities',
			'name'       => esc_html__( 'Amenities', 'clasify-classified-listing' ),
			'type'       => 'group',
			'clone'      => true,
			'columns'    => 12,
			'sort_clone' => true,
			'fields'     => array(
				array(
					'type'    => 'text',
					'name'    => esc_html__( 'Name', 'clasify-classified-listing' ),
					'id'      => $this->prefix . 'amenity_name',
					'columns' => '8',
				),
				array(
					'type'    => 'select',
					'name'    => esc_html__( 'Select', 'clasify-classified-listing' ),
					'id'      => $this->prefix . 'amenity_status',
					'options' => array(
						''    => '',
						'Yes' => esc_html__( 'Yes', 'clasify-classified-listing' ),
						'No'  => esc_html__( 'No', 'clasify-classified-listing' ),
					),
					'columns' => '4',
				),
			),
		);
		$field_data[ $this->prefix . 'floor' ]       = array(
			'id'         => $this->prefix . 'floor',
			'name'       => esc_html__( 'Floor Plan', 'clasify-classified-listing' ),
			'type'       => 'group',
			'columns'    => 12,
			'sort_clone' => true,
			'clone'      => true,
			'fields'     => array(
				array(
					'type'    => 'text',
					'name'    => esc_html__( 'Floor Name', 'clasify-classified-listing' ),
					'id'      => $this->prefix . 'floor_name',
					'columns' => '3',
				),
				array(
					'type'    => 'text',
					'name'    => esc_html__( 'Beds', 'clasify-classified-listing' ),
					'id'      => $this->prefix . 'floor_beds',
					'columns' => '3',
				),
				array(
					'type'    => 'text',
					'name'    => esc_html__( 'Baths', 'clasify-classified-listing' ),
					'id'      => $this->prefix . 'floor_baths',
					'columns' => '3',
				),
				array(
					'type'    => 'text',
					'name'    => esc_html__( 'Area (sqft)', 'clasify-classified-listing' ),
					'id'      => $this->prefix . 'floor_area',
					'columns' => '3',
				),
				array(
					'type' => 'image',
					'name' => esc_html__( 'Blueprint', 'clasify-classified-listing' ),
					'id'   => $this->prefix . 'floor_blueprint',
				),
			),
		);
		$field_data[ $this->prefix . 'video' ]       = array(
			'id'      => $this->prefix . 'video',
			'name'    => esc_html__( 'Video', 'clasify-classified-listing' ),
			'type'    => 'group',
			'columns' => 12,
			'fields'  => array(
				array(
					'type'    => 'url',
					'name'    => esc_html__( 'Url', 'clasify-classified-listing' ),
					'id'      => $this->prefix . 'video_url',
					'columns' => '12',
				),
				array(
					'type' => 'image',
					'name' => esc_html__( 'Video Placeholder', 'clasify-classified-listing' ),
					'id'   => $this->prefix . 'video_img',
				),
			),
		);
		$field_data[ $this->prefix . 'maps_fields' ] = array(
			'id'      => $this->prefix . 'maps_fields',
			'name'    => esc_html__( 'Map', 'clasify-classified-listing' ),
			'type'    => 'group',
			'columns' => 12,
			'fields'  => array(
				array(
					'type'     => 'maps',
					'name_lat' => esc_html__( 'Lat', 'clasify-classified-listing' ),
					'name_lon' => esc_html__( 'Lon', 'clasify-classified-listing' ),
					'id'       => $this->prefix . 'map_address',
					'columns'  => '6',
				),
			),
		);

		$fields = get_option( 'cl_custom_meta_settings' );

		if(!empty($fields)){
			foreach($fields as $field){
				$field_name = str_replace(' ', '', $field['field_label']);
				$field_label = $field['field_label'];
				$field_default_value = $field['field_default_value'];
				$field_type = $field['field_type'];
				
				$field_data[ $this->prefix . $field_name ]     = array(
					'type'    => $field_type,
					'name'    => $field_label,
					'id'      => $this->prefix . $field_name,
					'std'      => $field_default_value,
					'columns' => 3,
				);
			}
		}
		return $field_data;
	}

	
	protected function get_custom_fields() {
		$meta_fields = array();
		$meta_data   = apply_filters( 'cl_meta_boxes', array() );
		foreach ( $meta_data as $meta_data_val ) {
			foreach ( $meta_data_val['fields'] as $field_dat ) {
				$meta_fields[ $field_dat['id'] ] = $field_dat;
			}
		}
		return $meta_fields;
	}

	protected function get_fields( $group_id ) {
		if ( $group_id !== 'description' ) {
			$add_opt = get_option( 'cl_add_builder_setting', array() );
			if ( isset( $add_opt ) && ! empty( $add_opt ) ) {
				$add_opt   = json_decode( $add_opt );
				$meta_data = isset( $add_opt ) && ! empty( $add_opt ) ? $add_opt : '';
				if ( isset( $meta_data->enabled ) && ! empty( $meta_data->enabled ) ) {
					$set_data = array();
					foreach ( $meta_data->enabled->$group_id as $data ) {
						if ( array_key_exists( $data, $this->preset_data() ) ) {
							$set_data[] = $this->preset_data()[ $data ];
						} elseif ( array_key_exists( $data, $this->get_custom_fields() ) ) {
							// $set_data[] = $this->get_custom_fields()[$data];
						}
					}
					return apply_filters( $this->prefix . 'append_' . $group_id . '_fields', $set_data );
				}
			} else {
				return apply_filters( $this->prefix . 'append_preset_fields', $this->preset_data() );
			}
		}
	}
}
