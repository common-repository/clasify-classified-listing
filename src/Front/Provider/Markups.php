<?php
namespace Clasify\Classified\Front\Provider;

use Clasify\Classified\Traitval\Traitval;
use Clasify\Classified\Front\Provider\ListingProvider;
use Clasify\Classified\Admin\MetaBoxes\Components\Groups;

class Markups {

	use Traitval;

	/**
	 * listing_title callback function for listing_title action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_title() {
		echo CCP()->front->listing_provider->get_listing_title();
	}

	/**
	 * listing_content callback function for listing_content action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_content() {
		 echo CCP()->front->listing_provider->get_listing_content();
	}

	/**
	 * listing_excerpt callback function for listing_excerpt action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_excerpt() {
		 echo CCP()->front->listing_provider->get_listing_excerpt();
	}
	/**
	 * listing_author callback function for listing_author action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_author() {
		echo CCP()->front->listing_provider->get_listing_author();
	}
	/**
	 * get_listing_view_count callback function for get_listing_view_count action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_views() {
		echo CCP()->front->listing_provider->get_listing_views();
	}


	/**
	 * listing_price callback function for listing_price action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_price() {
		echo CCP()->front->listing_provider->get_listing_pricing();
	}

	/**
	 * listing_category callback function for listing_category action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_category() {
		echo CCP()->front->listing_provider->get_listing_category();
	}

	/**
	 * listing_status callback function for listing_category action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_status() {
		echo CCP()->front->listing_provider->get_listing_status();
	}

	public function listing_condition() {
		echo CCP()->front->listing_provider->get_listing_conditions();
	}

	/**
	 * listing_favourite callback function for listing_favourite action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_favourite() {
		echo CCP()->front->listing_provider->get_listing_favourite();
	}

	/**
	 * listing_share callback function for listing_share action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_share() {
		$s_link_facebook = 'https://www.facebook.com/share.php?u=' . get_the_permalink() . '&title=' . urlencode( get_the_title() );
		$s_link_twitter  = 'http://twitter.com/share?text=' . urlencode( get_the_title() ) . '&url=' . get_the_permalink();
		$s_link_linkedin = 'http://linkedin.com/shareArticle?mini=true&url=' . get_the_permalink() . '&title=' . urlencode( get_the_title() );
		$s_link_telegram = 'https://t.me/share/url?url=' . get_the_permalink() . '&text=' . urlencode( get_the_title() );
		$s_link_vk       = 'http://vk.com/share.php?url=' . get_the_permalink();

		$s_links = array(
			'facebook' => 'facebook-f',
			'twitter'  => 'twitter',
			'linkedin' => 'linkedin-in',
			'telegram' => 'telegram-plane',
			'vk'       => 'vk',
		);

		$dropdown_html  = '';
		$dropdown_html .= '<ul class="s-dropdown-list">';
		foreach ( $s_links as $s_link => $s_link_i ) {
			$dropdown_html .= "<li><a target=\"_blank\" data-balloon-nofocus data-balloon-pos=\"up\" aria-label=\"Share on {$s_link}\" href=\"{${'s_link_' .$s_link}}\"><i class=\"fab fa-{$s_link_i}\"></i></a></li>";
		}
		$dropdown_html .= '</ul>';
		$output         = '<div class="selio_style listing-share"><a href="javascript:void(0)" data-balloon-nofocus data-balloon-pos="up" aria-label="Share property" class="listing-share prt_saveed_12lk"><i class="fas fa-share"></i></a>' . $dropdown_html . '</div>';

		echo apply_filters( 'cl_listing_social_share', $output );
	}

	/**
	 * listing_compare callback function for listing_compare action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_compare() {
		echo CCP()->front->listing_provider->get_listing_compare();
	}

	/**
	 * listing_view callback function for listing_view action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_view() {
		$view = '<span class="selio_style"><a href="' . esc_url( get_permalink() ) . '" data-balloon-nofocus data-balloon-pos="up" aria-label="View property"><div class="prt_saveed_12lk"><i class="fas fa-arrow-right"></i></div></a></span>';
		echo apply_filters( 'cl_listing_view_hook', $view );
	}

	/**
	 * listing_features callback function for listing_features action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_features() {
		// -- Options will come from listing_admin
		$variable = get_the_terms( get_the_ID(), 'listing_features' );
		$class    = null;
		if ( is_single() ) {
			$class = 'single';
		} else {
			$class = 'archive';
		}
		if ( ! empty( $variable ) && is_array( $variable ) ) {

			echo "<div class=\"list-fx-features {$class}\">";
			foreach ( $variable as $value ) {
				echo '<div class="listing-card-info-icon">' . esc_html( $value->name ) . '</div>';
			}
			echo '</div>';
		}
	}

	/**
	 * listing_location callback function for listing_location action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_location() {
		echo CCP()->front->listing_provider->get_listing_location();
	}

	public function listing_address() {
		echo CCP()->front->listing_provider->get_listing_address();
	}
	/**
	 * listing_meta_features callback function for listing_meta_features action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_meta_features() {
		$beds = get_post_meta( get_the_id(), 'clasify_classified_plugin_beds', true );
		$bath = get_post_meta( get_the_id(), 'clasify_classified_plugin_bath', true );
		$area = get_post_meta( get_the_id(), 'clasify_classified_plugin_area', true );
		if ( $beds == 1 ) {
			$beds_txt = __( 'Bed', 'clasify-classified-listing' );
		} else {
			$beds_txt = __( 'Beds', 'clasify-classified-listing' );
		}
		if ( $bath == 1 ) {
			$bath_txt = __( 'Bath', 'clasify-classified-listing' );
		} else {
			$bath_txt = __( 'Baths', 'clasify-classified-listing' );
		}
		?>
		<div class="price-features-wrapper">
			<div class="list-fx-features">
			<div class="listing-card-info-icon">
			<div class="inc-fleat-icon">
			<img src="<?php echo CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/img/bed.svg'; ?>" width="13" alt="<?php esc_attr( 'bed', 'clasify-classified-listing' ); ?>">
			</div><?php echo esc_html( $beds . ' ' . $beds_txt ); ?></div>
			<div class="listing-card-info-icon">
			<div class="inc-fleat-icon">
			<img src="<?php echo CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/img/bathtub.svg'; ?>" width="13" alt="<?php esc_attr( 'bath', 'clasify-classified-listing' ); ?>">
			</div><?php echo esc_html( $bath . ' ' . $bath_txt ); ?></div>
			<div class="listing-card-info-icon">
			<div class="inc-fleat-icon">
			<img src="<?php echo CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/img/move.svg'; ?>" width="13" alt="<?php esc_attr( 'sqft', 'clasify-classified-listing' ); ?>">
			</div><?php echo esc_html( $area ) . ' ' . esc_html__( 'sqft', 'clasify-classified-listing' ); ?> </div>
			</div>
		</div>
		<?php
	}

	/**
	 * listing_postdate callback function for listing_postdate action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_publishdate() {
		$date = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . esc_html__( ' ago', 'clasify-classified-listing' );
		echo '<div class="foot-location listing-publish-date"><i class="fas fa-clock"></i> ' . esc_html( $date ) . '</div>';
	}

	/**
	 * listing_postdate callback function for listing_postdate action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_viewcount() {
		$count = get_post_meta( get_the_id(), 'listing_views_count', true );
		$count = ( $count == null ? '0' : $count );
		if ( $count > 0 ) {
			echo '<div class="foot-location listing-viewcount"><i class="fas fa-eye"></i> ' . esc_html__( 'Viewed:', 'clasify-classified-listing' ) . ' ' . clasify_classified_plugin_kmb( $count ) . '</div>';
		}
	}

	/**
	 * listing_layout
	 *
	 * @return void
	 * since 1.0.0
	 */
	public function listing_layout() {
		// -- Options will come from listing_admin
		$options = array(
			'grid' => array(
				'name' => 'Grid',
				'type' => 'layout',
				'icon' => 'fas fa-th-large',
			),
			'list' => array(
				'name' => 'List',
				'type' => 'layout',
				'icon' => 'fas fa-th-list',
			),
			'map'  => array(
				'name' => 'Map',
				'type' => 'layout',
				'icon' => 'fas fa-map-marked-alt',
			),
		);

		$layout_options = CCP()->front->listing_provider->get_gen_ted_link( $options );

		foreach ( $layout_options as $layout_option ) {
			echo '<div class="list-inline-item"><a href="' . esc_url( $layout_option['link'] ) . '" class="sorter ' . esc_attr( $layout_option['active'] ) . ' ' . esc_attr( $layout_option['default'] ) . '"><i class="' . esc_attr( $layout_option['icon'] ) . '"></i> <span>' . esc_html( $layout_option['name'] ) . '</span></a></div>';
		}
	}

	/**
	 * listing_sorter
	 *
	 * @return void
	 * since 1.0.0
	 */

	public function listing_sorter() {
		// -- Options will come from listing_admin
		$options         = CCP()->front->listing_provider->cl_sorter_options_data();
		$sorting_options = CCP()->front->listing_provider->get_gen_ted_link( $options );
		$get_key         = array_keys( $sorting_options );
		$key             = array_search( 'active', array_column( $sorting_options, 'active' ) );

		echo '<a class="btn-filter dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="selection">' . esc_html( $sorting_options[ $get_key[ $key ] ]['name'] ) . '</span></a><div class="dropdown-menu">';
		foreach ( $sorting_options as $sorting_option ) {
			echo '<a class="dropdown-item" href="' . esc_url( $sorting_option['link'] ) . '"><i class="' . esc_attr( $sorting_option['icon'] ) . '"></i> ' . esc_html( $sorting_option['name'] ) . '</a>';
		}
		echo '</div>';
	}



	/**
	 * listing_ratings callback function for listing_ratings action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_ratings() {
		 echo CCP()->front->listing_provider->get_listing_ratings();
	}

	/**
	 * listing_abuse callback function for listing_abuse action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function listing_abuse() {
		$listing_abuse = apply_filters( 'listing_abuse_text', esc_html( 'Report Abuse', 'clasify-classified-listing' ) );
		echo '<span class="selio_style"><a href="javascript:void(0)" data-id="' . get_the_ID() . '" data-balloon-nofocus data-balloon-pos="up" aria-label="' . esc_attr( $listing_abuse ) . '" class="listing_abuse prt_saveed_12lk"><i class="fas fa-ban"></i></a></span>';
		echo '<div id="listing_abuse_dialog" class="listing_abuse_dialog hidden" style="max-width:800px">
        <h6 class="listing_abuse_title">Write your Complain</h6>
        <form id="listing_abuse_dialog_form">
        <p><textarea placeholder="Your complain*" rows="4" cols="50" name="listing_abuse_dialog_text"></textarea></p>
        <input type="hidden" name="listing_abuse_dialog_id" value="' . get_the_ID() . '" >
        <input type="hidden" name="action" value="listing_abuse_dialog_action" >
        <p><div class="listing_abuse_dialog_return"></div></p>
        <p><input class="submit-btn btn" type="submit" value="Submit"  data-id="' . get_the_ID() . '"></p>
        </form>
        </div>';
	}

	/**
	 * cl_compare_listing_html callback function for cl_compare_listing_html action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function cl_compare_listing_html() {
		$comp_item      = array();
		$compare_fields = array();
		if ( isset( $_COOKIE['compare_listing_data'] ) ) {
			$comp_item = explode( ',', cl_sanitization( $_COOKIE['compare_listing_data'] ) );
			$comp_item = array_filter( $comp_item );
		};
		$provider       = CCP()->front->listing_provider;
		$compare_fields = $provider->cl_compare_fields_data();

		echo '<div class="container"><div class="row"><div class="col"><div class="compare_item_thumb"></div><h2 class="text-center compare_item_title">'.esc_html__('Name').'</h2><div class="compare_item_info"><ul>';
		foreach ( $compare_fields as $key => $value ) {
			echo '<li class="heading">' . esc_html( $key ) . '</li>';
		}
		echo '</ul></div></div>';
		foreach ( $comp_item as $id ) {
			echo '<div class="col"><div class="compare_item_thumb">';
			if ( has_post_thumbnail( $id ) ) {
				$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
				echo '<img src="' . esc_html( get_the_post_thumbnail_url( $id, 'thumbnail' ) ) . '" alt="' . esc_attr( $alt ) . '">';
			} else {
				echo '<img src="' . CLASIFY_CLASSIFIED_PLUGIN_ASSETS . '/img/placeholder_light.png' . '" alt="' . esc_attr__( 'Placeholder', 'clasify-classified-plugin' ) . '">';
			}
			echo '<h2 class="text-center compare_item_title"><a href="'.get_the_permalink($id).'">'.get_the_title($id).'</a></h2></div><div class="compare_item_info"><ul>';
			foreach ( $compare_fields as $key => $value ) {
				if ( ! is_array( $provider->get_meta_data( $value, $id ) ) ) {
					if($value == 'clasify_classified_plugin_pricing'){
						echo "<li>".CCP()->common->formatting->cl_currency_filter( CCP()->common->formatting->cl_format_amount( $provider->get_meta_data($value,$id) ) )."</li>";
					}else{
						echo "<li>{$provider->get_meta_data($value,$id)}</li>";
					}
				} else {
					echo '<li class="non-comp">'.esc_html__('Non Comparable Data','clasify-classified-plugin').'</li>';
				}
			}
			echo '</ul></div></div>';
		}
		echo '</div></div>';
	}

	public function cl_register_user_html( $args = array() ) {
		$reg_redirect_url    = '';
		$login_redirect_page = get_page_link( cl_admin_get_option( 'login_redirect_page' ) );
		$error               = array();
		$alert               = array();
		if ( isset( $args ) && ! empty( $args ) ) {
			$reg_redirect_url = isset( $args['redirect'] ) ? $args['redirect'] : '';
		}
		if ( isset( $_POST['submit'] ) ) {
			$first_name    = cl_sanitization( $_POST['first_name'] );
			$last_name     = cl_sanitization( $_POST['last_name'] );
			$username      = cl_sanitization( $_POST['username'] );
			$email         = cl_sanitization( $_POST['email'] );
			$password      = cl_sanitization( $_POST['password'] );
			$conf_password = cl_sanitization( $_POST['conf_password'] );

			if ( strpos( $username, ' ' ) !== false ) {
				$error['error_msg'] = esc_html__( 'Username has Space', 'reveal-listing' );
			}

			if ( empty( $username ) ) {
				$error['error_msg'] = esc_html__( 'Needed Username must', 'reveal-listing' );
			}

			if ( username_exists( $username ) ) {
				$error['error_msg'] = esc_html__( 'Username already exists', 'reveal-listing' );
			}

			if ( ! is_email( $email ) ) {
				$error['error_msg'] = esc_html__( 'Email has no valid value', 'reveal-listing' );
			}

			if ( email_exists( $email ) ) {
				$error['error_msg'] = esc_html__( 'Email already exists', 'reveal-listing' );
			}

			if ( strcmp( $password, $conf_password ) !== 0 ) {
				$error['error_msg'] = esc_html__( "Password didn't match", 'reveal-listing' );
			}

			if ( count( $error ) == 0 ) {
				$alert['class'] = 'success';
				$alert['msg']   = 'Successfully Registered.';

				$userdata = array(
					'user_login'   => $username,
					'user_pass'    => $password,
					'first_name'   => $first_name,
					'last_name'    => $last_name,
					'user_email'   => $email,
					'display_name' => $first_name . $last_name,
					'role'         => 'listing_user',
				);

				$user_id = wp_insert_user( $userdata );
			} else {
				$alert['class'] = 'danger';
				$alert['msg']   = $error['error_msg'];
			}
		}
		?>
		<form action="#" method="post" id="cl-register-user-form" class="cl-register-user-form">
			<div class="container">
				<div class="row">
					<div class="col-md-6 form-group">
						<label for="first_name"><?php esc_html_e( 'First Name', 'clasify-classified-listing' ); ?></label>
						<input type="text" name="first_name" id="first_name" class="input form-control" value="" placeholder="<?php esc_attr_e( 'First Name', 'clasify-classified-listing' ); ?>" />
					</div>
					<div class="col-md-6 form-group">
						<label for="last_name"><?php esc_html_e( 'Last Name', 'clasify-classified-listing' ); ?></label>
						<input type="text" name="last_name" id="last_name" class="input form-control" value="" placeholder="<?php esc_attr_e( 'Last Name', 'clasify-classified-listing' ); ?>" />
					</div>
					<div class="col-md-6 form-group">
						<label for="username"><?php esc_html_e( 'Username', 'clasify-classified-listing' ); ?></label>
						<input required type="text" name="username" id="username" class="input form-control" value="" placeholder="<?php esc_attr_e( 'Username', 'clasify-classified-listing' ); ?>"/>
					</div>
					<div class="col-md-6 form-group">
						<label for="email"><?php esc_html_e( 'E-mail', 'clasify-classified-listing' ); ?></label>
						<input required type="text" name="email" id="email" class="input form-control" value="" placeholder="<?php esc_attr_e( 'E-mail', 'clasify-classified-listing' ); ?>"/>
					</div>
					<div class="col-md-6 form-group">
						<label for="password"><?php esc_html_e( 'Password', 'clasify-classified-listing' ); ?></label>
						<input required type="password" name="password" id="password" class="input form-control" value="" placeholder="<?php esc_attr_e( 'Password', 'clasify-classified-listing' ); ?>"/>
					</div>
					<div class="col-md-6 form-group">
						<label for="conf_password"><?php esc_html_e( 'Confirm Password', 'clasify-classified-listing' ); ?></label>
						<input required type="password" name="conf_password" id="conf_password" class="input form-control" value="" placeholder="<?php esc_attr_e( 'Confirm Password', 'clasify-classified-listing' ); ?>"/>
					</div>
					<?php if ( ! empty( $alert ) ) { ?>
						<div class="col-md-12">
							<div class="alert alert-<?php echo esc_attr( $alert['class'] ); ?>">
								<?php echo esc_html( $alert['msg'] ); ?>
							</div>
						</div>
					<?php } ?>
					<div class="col-md-12 form-group">
						<p><?php esc_html_e( 'Already have an account!', 'clasify-classified-listing' ); ?> <a href="<?php echo esc_url( $login_redirect_page ); ?>"><?php esc_html_e( 'Login', 'clasify-classified-listing' ); ?></a></p>
						<button type="submit" name="submit"><?php esc_html_e( 'Register', 'clasify-classified-listing' ); ?></button>
					</div>
				</div>
			</div>
		</form>
		<?php
	}

	public function cl_update_user_html( $args = array() ) {
		if ( ! is_user_logged_in() ) {
			echo '<p>Please <a href="' . get_page_link( cl_admin_get_option( 'login_redirect_page' ) ) . '">Login</a></p>';
		} else {
			global $current_user;
			$user_id = $current_user->ID;
			$error   = array();
			$alert   = array();
			if ( isset( $_POST['submit'] ) ) {
				$first_name    = cl_sanitization( $_POST['first_name'] );
				$last_name     = cl_sanitization( $_POST['last_name'] );
				$email         = cl_sanitization( $_POST['email'] );
				$password      = cl_sanitization( $_POST['password'] );
				$conf_password = cl_sanitization( $_POST['conf_password'] );

				if ( strcmp( $password, $conf_password ) !== 0 ) {
					$error['error_msg'] = esc_html__( "Password didn't match", 'reveal-listing' );
				}

				if ( count( $error ) == 0 ) {

					$userdata = array(
						'ID'         => $user_id,
						'first_name' => $first_name,
						'last_name'  => $last_name,
						'user_email' => $email,
					);
					// Update user information
					$user_update = wp_update_user( $userdata );
					// Update user password
					wp_set_password( $password, $user_id );
					// Check if theres any error else return success
					if ( ! is_wp_error( $user_update ) ) {
						$alert['class'] = 'success';
						$alert['msg']   = 'Profile Successfully Updated.';
					}
				} else {
					$alert['class'] = 'danger';
					$alert['msg']   = $error['error_msg'];
				}
			}
			?>
			<form action="#" method="post" id="cl-update-user-form" class="cl-update-user-form">
				<div class="container">
					<div class="row">
						<div class="col-md-6 form-group">
							<label for="first_name"><?php esc_html_e( 'First Name', 'clasify-classified-listing' ); ?></label>
							<input required type="text" name="first_name" id="first_name" class="input form-control" value="<?php echo esc_attr( get_user_meta( $user_id, 'first_name', true ) ); ?>" />
						</div>
						<div class="col-md-6 form-group">
							<label for="last_name"><?php esc_html_e( 'Last Name', 'clasify-classified-listing' ); ?></label>
							<input required type="text" name="last_name" id="last_name" class="input form-control" value="<?php echo esc_attr( get_user_meta( $user_id, 'last_name', true ) ); ?>" />
						</div>
						<div class="col-md-12 form-group">
							<label for="username"><?php esc_html_e( 'Username', 'clasify-classified-listing' ); ?></label>
							<input disabled required type="text" name="username" id="username" class="input form-control" value="<?php echo esc_attr( $current_user->data->user_login ); ?>" />
						</div>
						<div class="col-md-6 form-group">
							<label for="email"><?php esc_html_e( 'E-mail', 'clasify-classified-listing' ); ?></label>
							<input required type="text" name="email" id="email" class="input form-control" value="<?php echo esc_attr( $current_user->data->user_email ); ?>" />
						</div>
						<div class="col-md-6 form-group">
							<label for="password"><?php esc_html_e( 'Password', 'clasify-classified-listing' ); ?></label>
							<input required placeholder="<?php esc_attr_e( 'Password', 'clasify-classified-listing' ); ?>" type="password" name="password" id="password" class="input form-control" value="" />
						</div>
						<div class="col-md-6 form-group">
							<label for="conf_password"><?php esc_html_e( 'Confirm Password', 'clasify-classified-listing' ); ?></label>
							<input required placeholder="<?php esc_attr_e( 'confirm your password', 'clasify-classified-listing' ); ?>" type="password" name="conf_password" id="conf_password" class="input form-control" value="" />
						</div>
						<?php if ( ! empty( $alert ) ) { ?>
							<div class="col-md-12">
								<div class="alert alert-<?php echo esc_attr( $alert['class'] ); ?>">
									<?php echo esc_html( $alert['msg'] ); ?>
								</div>
							</div>
						<?php } ?>
						<div class="col-md-12 form-group">
							<button type="submit" name="submit"><?php esc_html_e( 'Submit', 'clasify-classified-listing' ); ?></button>
						</div>
					</div>
				</div>
			</form>
			<?php
		}
	}

	public function cl_admin_login_html( $args = array() ) {
		$login_redirect_url = '';
		$register_user_page = get_page_link( cl_admin_get_option( 'register_user_page' ) );
		if ( isset( $args ) && ! empty( $args ) ) {
			$login_redirect_url = isset( $args['redirect'] ) ? $args['redirect'] : '';
		}

		$defaults          = array(
			'echo'           => true,
			'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $login_redirect_url,   // Default 'redirect' value takes the user back to the request URI.
			'form_id'        => 'loginform',
			'label_username' => __( 'Username or Email' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_sign_in'  => __( 'Login' ),
			'label_sign_up'  => __( 'Register' ),
			'id_username'    => 'user_login',
			'id_password'    => 'user_pass',
			'id_remember'    => 'rememberme',
			'id_submit'      => 'wp-submit',
			'id_signup'      => 'wp-submit',
			'remember'       => true,
			'value_username' => '',
			'value_remember' => false,                                                       // Set 'value_remember' to true to default the "Remember me" checkbox to checked.
		);
		$args              = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
		$login_form_top    = apply_filters( 'login_form_top', '', $args );
		$login_form_middle = apply_filters( 'login_form_middle', '', $args );
		$login_form_bottom = apply_filters( 'login_form_bottom', '', $args );
		$form              = '
            <form name="' . esc_attr( $args['form_id'] ) . '" id="' . esc_attr( $args['form_id'] ) . '" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post">
                ' . $login_form_top . '
                <p class="login-username form-group">
                    <label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
                    <input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input form-control" placeholder="' . esc_html( $args['label_username'] ) . '" value="' . esc_attr( $args['value_username'] ) . '" size="20" />
                </p>
                <p class="login-password form-group">
                    <label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
                    <input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="input form-control" placeholder="' . esc_html( $args['label_password'] ) . '" value="" size="20" />
                </p>
                ' . $login_form_middle . '
                ' . ( $args['remember'] ? '<p class="cl-login-remember login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '
                <p>Don\'t have an account! <a href="' . esc_url( $register_user_page ) . '">' . esc_attr( $args['label_sign_up'] ) . '</a></p>
                <p class="login-submit">
                    <input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button button-primary" value="' . esc_attr( $args['label_sign_in'] ) . '" />
                    <input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
                </p>
                ' . $login_form_bottom . '
            </form>';

		if ( $args['echo'] ) {
			printf( $form );
		} else {
			return $form;
		}
	}

	public function get_texo_list( $param ) {
		$get_terms    = get_terms(
			array(
				'taxonomy'   => $param,
				'hide_empty' => false,
			)
		);
		$taxo_options = array( '' => 'Select' );
		foreach ( $get_terms as $get_term ) {
			$taxo_options[ $get_term->slug ] = $get_term->name;
		}
		return $taxo_options;
	}

	/**
	 * cl_dashboard_listing_html callback function for cl_dashboard_listing_html action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function cl_dashboard_listing_html() {
		if ( ! is_user_logged_in() ) {
			echo '<p>Please <a href="' . get_page_link( cl_admin_get_option( 'login_redirect_page' ) ) . '">Login</a> to Edit Listing</p>';
		} else {
			cl_get_template( 'dashboard/dashboard.php' );
		}
	}
	/**
	 * cl_edit_listing_html callback function for cl_edit_listing_html action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function cl_edit_listing_html() {
		if ( ! is_user_logged_in() ) {
			echo '<p>' . esc_html__( 'Please ', 'clasify-classified-listing' ) . '<a href="' . get_page_link( cl_admin_get_option( 'login_redirect_page' ) ) . '">' . esc_html__( 'Login ', 'clasify-classified-listing' ) . '</a> ' . esc_html__( 'to Edit Listing', 'clasify-classified-listing' ) . '</p>';
		} else {
			global $current_user;
			$cl_edit_listing_var = get_query_var( 'cl_edit_listing_var' );
			if ( empty( $cl_edit_listing_var ) ) {
				echo esc_html__( 'To edit a Listing, Please go to dashboard and click on edit button to edit that Listing.', 'clasify-classified-listing' );
			} else {
				$current_post     = get_post( $cl_edit_listing_var );
				$this->get_fields = apply_filters( 'cl_meta_boxes', Groups::getInstance()->generate_groups() );
				$field_type       = array();
				$field_arr        = array();
				$meta_inputs      = array();
				foreach ( $this->get_fields as $fields ) {
					foreach ( $fields['fields'] as $field ) {
						$field_arr[ $fields['id'] ][] = $field['id'];
						$field_type[ $field['id'] ]   = $field['type'];
						$field_data[ $field['id'] ]   = $field;
					}
				}
				$add_fields = array(
					'enabled'  => $field_arr,
					'disabled' => array(),
				);

				$add_settings = get_option( 'cl_add_builder_setting', array() );
				if ( empty( $add_settings ) || $add_settings == 'null' ) {
					$add_settings          = $add_fields;
					$add_settings_enabled  = $add_fields['enabled'];
					$add_settings_disabled = $add_fields['disabled'];
				} else {
					$add_settings          = json_decode( $add_settings, true );
					$add_settings_enabled  = isset( $add_settings['enabled'] ) ? $add_settings['enabled'] : array();
					$add_settings_disabled = isset( $add_settings['disabled'] ) ? $add_settings['disabled'] : array();
				}

				$post_id = '';
				if ( isset( $_POST['cl_add_l_submit'] ) && $_POST['cl_add_l_submit'] ) {

					if ( ! wp_verify_nonce( $_REQUEST['submit-listing'], 'listing-add-listing' ) ) {
						return true;
					}
					$sbmt_arr = cl_sanitization( $_POST );
					foreach ( $sbmt_arr as $key => $value ) {
						if ( array_key_exists( $key, $field_type ) ) {
							$meta_inputs[ $key ] = $value;
						}
					}
					// -------- Set empty value -------- //
					$empty_fields = array_diff_key( $field_type, $meta_inputs );
					foreach ( $empty_fields as $empty_key => $empty_value ) {
						$meta_inputs[ $empty_key ] = '';
					}

					$title              = cl_sanitization( $_POST['title'] );
					$description        = cl_sanitization( $_POST['description'] );
					$listings_category  = isset( $_POST['listings_category'] ) ? cl_sanitization( $_POST['listings_category'] ) : '';
					$listing_location   = isset( $_POST['listing_location'] ) ? cl_sanitization( $_POST['listing_location'] ) : '';
					$listing_status     = isset( $_POST['listing_status'] ) ? cl_sanitization( $_POST['listing_status'] ) : '';
					$listing_conditions = isset( $_POST['listing_conditions'] ) ? cl_sanitization( $_POST['listing_conditions'] ) : '';
					$listing_features   = isset( $_POST['listing_features'] ) ? cl_sanitization( $_POST['listing_features'] ) : '';

					$post_arr = array(
						'ID'           => $cl_edit_listing_var,
						'post_title'   => $title,
						'post_content' => $description,
						'post_type'    => $this->cl_cpt,
						'post_status'  => 'publish',
						'post_author'  => get_current_user_id(),
						'tax_input'    => array(
							'listings_category'  => $listings_category,
							'listing_location'   => $listing_location,
							'listing_status'     => $listing_status,
							'listing_conditions' => $listing_conditions,
							'listing_features'   => $listing_features,
						),
						'meta_input'   => $meta_inputs,
					);

					wp_update_post( $post_arr );
					if ( isset( $_POST['featured_image'] ) && ! empty( $_POST['featured_image'] ) ) {
						set_post_thumbnail( $cl_edit_listing_var, cl_sanitization( $_POST['featured_image'][0] ) );
					}
				}

				function get_the_terms_list( $post_id, $param ) {
					$get_the_terms = get_the_terms( $post_id, $param );
					$term_options  = array( '' => 'Select' );

					if ( is_array( $get_the_terms ) || ! empty( $get_the_terms ) ) {
						foreach ( $get_the_terms as $get_the_term ) {
							$term_options[ $get_the_term->slug ] = $get_the_term->name;
						}
					}

					return $term_options;
				}
				?>
				<section class="gray-simple">
					<div class="container">
						<div class="row">
							<!-- Submit Form -->
							<div class="col-lg-12 col-md-12">
								<div class="submit-page edit">
									<form action="#" method="post" id="listing-submit-form" class="listing-submit-form" enctype="multipart/form-data">
									<?php
									echo '<div class="row">';

									cl_get_template(
										'dashboard/edit/featured_image.php',
										array(
											'id'         => 'featured_image',
											'field_data' => array(
												'id'    => 'featured_image',
												'name'  => 'Featured Image',
												'id'    => 'featured_image_id',
												'value' => array( get_post_thumbnail_id( $cl_edit_listing_var ) ),
											),
										)
									);
										cl_get_template(
											'dashboard/edit/text.php',
											array(
												'id' => 'title',
												'field_data' => array(
													'required' => 'required',
													'id'   => 'title',
													'name' => 'Title',
													'value' => $current_post->post_title,
												),
											)
										);
										cl_get_template(
											'dashboard/edit/textarea.php',
											array(
												'id' => 'description',
												'field_data' => array(
													'id'   => 'description',
													'name' => 'Description',
													'value' => $current_post->post_content,
												),
											)
										);

										cl_get_template(
											'dashboard/edit/select.php',
											array(
												'id' => 'listings_category',
												'field_data' => array(
													'id'   => 'listings_category',
													'name' => 'Property Types',
													'options' => $this->get_texo_list( 'listings_category' ),
													'value' => get_the_terms_list(
														$current_post->ID,
														'listings_category'
													),
												),
											)
										);
										cl_get_template(
											'dashboard/edit/select.php',
											array(
												'id' => 'listing_location',
												'field_data' => array(
													'id'   => 'listing_location',
													'name' => 'Listing Locations',
													'options' => $this->get_texo_list( 'listing_location' ),
													'value' => get_the_terms_list(
														$current_post->ID,
														'listing_location'
													),
												),
											)
										);
										cl_get_template(
											'dashboard/edit/select.php',
											array(
												'id' => 'listing_status',
												'field_data' => array(
													'id'   => 'listing_status',
													'name' => 'Listing Status',
													'options' => $this->get_texo_list( 'listing_status' ),
													'value' => get_the_terms_list(
														$current_post->ID,
														'listing_status'
													),
												),
											)
										);
										cl_get_template(
											'dashboard/edit/select.php',
											array(
												'id' => 'listing_conditions',
												'field_data' => array(
													'id'   => 'listing_conditions',
													'name' => 'Listing Conditions',
													'options' => $this->get_texo_list( 'listing_conditions' ),
													'value' => get_the_terms_list(
														$current_post->ID,
														'listing_conditions'
													),
												),
											)
										);
										cl_get_template(
											'dashboard/edit/checkbox.php',
											array(
												'id' => 'listing_features',
												'field_data' => array(
													'id'   => 'listing_features',
													'name' => 'Listing Features',
													'options' => $this->get_texo_list( 'listing_features' ),
													'value' => get_the_terms_list(
														$current_post->ID,
														'listing_features'
													),
												),
											)
										);

									foreach ( $add_settings_enabled as $add_settings_enabled_key => $enabled_value ) {
										echo '<div class="col-md-12"><h4 class="cl_add_inp_heading">' . str_replace( '_', ' ', $add_settings_enabled_key ) . '</h4><div class="row">';
										foreach ( $enabled_value as $en_value ) {
											$meta_data                   = get_post_meta( $cl_edit_listing_var, $en_value, true );
											$attr['id']                  = $en_value;
											$attr['field_data']          = $field_data[ $en_value ];
											$attr['field_data']['value'] = $meta_data;
											cl_get_template( "dashboard/edit/{$field_type[$en_value]}.php", $attr );
										}
										echo '</div></div>';
									}

										echo '</div>';
										echo apply_filters( 'cl_edit_listing_form_after_filter', $post_id );
									?>
										<div class="form-group">
										<?php
										wp_nonce_field( 'listing-add-listing', 'submit-listing' );
										if ( $current_user->roles[0] == 'subscriber' ) {
											?>
												<p><?php echo esc_html( 'Please upgrade demouser role to submit post.' ); ?></p>
												<button disabled title="Upgrade user role to post" class="btn btn-theme-light-2 rounded"><?php _e( 'Submit &#38; Preview', 'clasify-classified-listing' ); ?></button>
											<?php } else { ?>
												<input class="btn btn-theme-light-2 rounded" type="submit" name="cl_add_l_submit" value="<?php _e( 'Submit &#38; Preview', 'clasify-classified-listing' ); ?>">
											<?php }; ?>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</section>
				<?php
			}
		}
	}

	/**
	 * cl_add_listing_html callback function for cl_add_listing_html action
	 *
	 * @return void
	 *
	 * since 1.0.0
	 */

	public function cl_add_listing_html() {
		 ob_start();
		if ( ! is_user_logged_in() ) {
			$login_url = '<a href="' . get_page_link( cl_admin_get_option( 'login_redirect_page' ) ) . '">'.__('Login','clasify-classified-listing').'</a>';
			echo '<p>'. sprintf(__('Please %s to Add Listing','clasify-classified-listing'),$login_url).'</p>';
		} else {
			global $current_user;
			$this->get_fields = apply_filters( 'cl_meta_boxes', Groups::getInstance()->generate_groups() );
			$field_type       = array();
			$field_arr        = array();
			$meta_inputs      = array();
			foreach ( $this->get_fields as $fields ) {
				foreach ( $fields['fields'] as $field ) {
					$field_arr[ $fields['id'] ][] = $field['id'];
					$field_type[ $field['id'] ]   = $field['type'];
					$field_data[ $field['id'] ]   = $field;
				}
			}
			$add_fields = array(
				'enabled'  => $field_arr,
				'disabled' => array(),
			);

			$add_settings = get_option( 'cl_add_builder_setting', array() );
			if ( empty( $add_settings ) || $add_settings == 'null' ) {
				$add_settings          = $add_fields;
				$add_settings_enabled  = $add_fields['enabled'];
				$add_settings_disabled = $add_fields['disabled'];
			} else {
				$add_settings          = json_decode( $add_settings, true );
				$add_settings_enabled  = isset( $add_settings['enabled'] ) ? $add_settings['enabled'] : array();
				$add_settings_disabled = isset( $add_settings['disabled'] ) ? $add_settings['disabled'] : array();
			}

			$post_id = '';
			if ( isset( $_POST['cl_add_l_submit'] ) && $_POST['cl_add_l_submit'] ) {

				if ( ! wp_verify_nonce( $_REQUEST['submit-listing'], 'listing-add-listing' ) ) {
					return true;
				}

				foreach ( $_POST as $key => $value ) {
					if ( array_key_exists( $key, $field_type ) ) {
						$meta_inputs[ $key ] = $value;
					}
				}

				$title       = cl_sanitization( $_POST['title'] );
				$description = cl_sanitization( $_POST['description'] );

				$listings_category  = isset( $_POST['listings_category'] ) ? cl_sanitization( $_POST['listings_category'] ) : '';
				$listing_location   = isset( $_POST['listing_location'] ) ? cl_sanitization( $_POST['listing_location'] ) : '';
				$listing_status     = isset( $_POST['listing_status'] ) ? cl_sanitization( $_POST['listing_status'] ) : '';
				$listing_conditions = isset( $_POST['listing_conditions'] ) ? cl_sanitization( $_POST['listing_conditions'] ) : '';
				$listing_features   = isset( $_POST['listing_features'] ) ? cl_sanitization( $_POST['listing_features'] ) : '';

				$add_listing_status =  cl_admin_get_option( 'add_listing_status' );

				$post_arr = array(
					'post_title'   => $title,
					'post_content' => $description,
					'post_type'    => $this->cl_cpt,
					'post_status'  => $add_listing_status,
					'post_author'  => get_current_user_id(),
					'tax_input'    => array(
						'listings_category'  => $listings_category,
						'listing_location'   => $listing_location,
						'listing_status'     => $listing_status,
						'listing_conditions' => $listing_conditions,
						'listing_features'   => $listing_features,
					),
					'meta_input'   => $meta_inputs,
				);

				$post_id = wp_insert_post( $post_arr );
				if ( isset( $_POST['featured_image'] ) && ! empty( $_POST['featured_image'] ) ) {
					set_post_thumbnail( $post_id, cl_sanitization( $_POST['featured_image'][0] ) );
				}

				$add_listing_redirect_url =  cl_admin_get_option( 'add_listing_redirect_url' );
				if(isset( $add_listing_redirect_url ) && ! empty( $add_listing_redirect_url ) ){
					$add_listing_redirect_url = $add_listing_redirect_url;
				}else{
					$add_listing_redirect_url = get_page_link( cl_admin_get_option( 'dashboard_page' ) ).'?dashboard=listings';
				}
				?>
				<script type="text/javascript">
					setTimeout(function () {
			  			window.location = '<?php echo esc_url($add_listing_redirect_url);?>';
					}, 1000);
				</script>
				<?php
			}
			$user = wp_get_current_user();
			if ( $user->roles == 'listing_user' ) {
				if ( ! apply_filters( 'cl_add_listing_form_check_package', '' ) ) {
					return false;
				}
			}

			?>
			<section class="gray-simple">
				<div class="container">
					<div class="row">
						<!-- Submit Form -->
						<div class="col-lg-12 col-md-12">
							<div class="submit-page add">
								<form action="#" method="post" id="listing-submit-form" class="listing-submit-form" enctype="multipart/form-data">
									<?php
									echo '<div class="row">';
									echo apply_filters( 'cl_add_listing_form_before_filter', '' );
									cl_get_template(
										'dashboard/add/featured_image.php',
										array(
											'id'         => 'featured_image',
											'field_data' => array(
												'id'   => 'featured_image',
												'name' => 'Featured Image',
												'id'   => 'featured_image_id',
											),
										)
									);
									cl_get_template(
										'dashboard/add/text.php',
										array(
											'id'         => 'title',
											'field_data' => array(
												'required' => 'required',
												'id'       => 'title',
												'name'     => 'Title',
											),
										)
									);
									cl_get_template(
										'dashboard/add/textarea.php',
										array(
											'id'         => 'description',
											'field_data' => array(
												'id'   => 'description',
												'name' => 'Description',
											),
										)
									);

									cl_get_template(
										'dashboard/add/select.php',
										array(
											'id'         => 'listings_category',
											'field_data' => array(
												'id'      => 'listings_category',
												'name'    => 'Property Types',
												'options' => $this->get_texo_list( 'listings_category' ),
											),
										)
									);
									cl_get_template(
										'dashboard/add/select.php',
										array(
											'id'         => 'listing_location',
											'field_data' => array(
												'id'      => 'listing_location',
												'name'    => 'Listing Locations',
												'options' => $this->get_texo_list( 'listing_location' ),
											),
										)
									);
									cl_get_template(
										'dashboard/add/select.php',
										array(
											'id'         => 'listing_status',
											'field_data' => array(
												'id'      => 'listing_status',
												'name'    => 'Listing Status',
												'options' => $this->get_texo_list( 'listing_status' ),
											),
										)
									);
									cl_get_template(
										'dashboard/add/select.php',
										array(
											'id'         => 'listing_conditions',
											'field_data' => array(
												'id'      => 'listing_conditions',
												'name'    => 'Listing Conditions',
												'options' => $this->get_texo_list( 'listing_conditions' ),
											),
										)
									);
									cl_get_template(
										'dashboard/add/checkbox.php',
										array(
											'id'         => 'listing_features',
											'field_data' => array(
												'id'      => 'listing_features',
												'name'    => 'Listing Features',
												'options' => $this->get_texo_list( 'listing_features' ),
											),
										)
									);

									foreach ( $add_settings_enabled as $add_settings_enabled_key => $enabled_value ) {
										echo '<div class="col-md-12"><h4 class="cl_add_inp_heading">' . str_replace( '_', ' ', $add_settings_enabled_key ) . '</h4><div class="row">';
										foreach ( $enabled_value as $en_value ) {
											$attr['id']         = $en_value;
											$attr['field_data'] = $field_data[ $en_value ];
											cl_get_template( "dashboard/add/{$field_type[$en_value]}.php", $attr );
										}
										echo '</div></div>';
									}
									echo '</div>';
									echo apply_filters( 'cl_add_listing_form_after_filter', $post_id );
									?>
									<div class="form-group">
										<?php
										wp_nonce_field( 'listing-add-listing', 'submit-listing' );
										if ( $current_user->roles[0] == 'subscriber' ) {
											?>
											<p><?php echo esc_html( 'Please upgrade demouser role to submit post.' ); ?></p>
											<button disabled title="Upgrade user role to post" class="btn btn-theme-light-2 rounded"><?php _e( 'Submit &#38; Preview', 'clasify-classified-listing' ); ?></button>
										<?php } else { ?>
											<input class="btn btn-theme-light-2 rounded" type="submit" name="cl_add_l_submit" value="<?php _e( 'Submit &#38; Preview', 'clasify-classified-listing' ); ?>">
										<?php }; ?>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</section>
			<?php
		}
		return ob_get_contents();
	}

	public function thumbnail_wrapper_open() {
		$attrs   = apply_filters( 'thumbnail_wrapper_attr', array( 'class' => 'featured_slick_gallery-slide' ) );
		$attrstr = $this->create_attrs( $attrs );
		$wrapper = sprintf( '<div %1s>', $attrstr );
		return apply_filters( 'thumbnail_wrapper_open_html', $wrapper );
	}
	public function thumbnail_wrapper_close() {
		 return apply_filters( 'thumbnail_wrapper_close_html', '</div>' );
	}

	public function rightmeta_wrapper_open() {
		$attrs   = apply_filters( 'righmeta_wrapper_attr', array( 'class' => 'action_content' ) );
		$attrstr = $this->create_attrs( $attrs );
		$wrapper = sprintf( '<div %1s>', $attrstr );
		return apply_filters( 'rightmeta_wrapper_open_html', $wrapper );
	}

	public function leftmeta_wrapper_open() {
		$attrs   = apply_filters( 'leftmeta_wrapper_attr', array( 'class' => 'info_content' ) );
		$attrstr = $this->create_attrs( $attrs );
		$wrapper = sprintf( '<div %1s>', $attrstr );
		return apply_filters( 'leftmeta_wrapper_open_html', $wrapper );
	}

	public function section_wrapper( $section ) {
		if ( $section == 'metasection' || $section == 'extrasection' || $section == 'templatesection' ) {
			return apply_filters( $section . '_wrapper_html', '<div class="cl_listing_content pt-4">' );
		} else {
			return apply_filters( $section . '_wrapper_html', '' );
		}

		return '';
	}

	public function section_wrapper_close( $section ) {
		if ( $section == 'metasection' || $section == 'extrasection' || $section == 'templatesection' ) {
			return apply_filters( $section . '_wrapper_close_html', '</div>' );
		} else {
			return apply_filters( $section . '_wrapper_close_html', '' );
		}
		return '';
	}

	public function meta_wrapper_close() {
		return apply_filters( 'meta_wrapper_close_html', '</div>' );
	}

	private function create_attrs( $attrs ) {
		$str = '';
		if ( is_array( $attrs ) && ! empty( $attrs ) ) {
			foreach ( $attrs as $attr => $value ) {
				$str .= $attr . ' = "' . $value . '"';
			}
			return $str;
		} else {
			return '';
		}
	}
}
