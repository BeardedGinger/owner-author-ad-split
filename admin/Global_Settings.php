<?php
/**
 * Adds the global setting for adding a site adsense ID that is the default
 * for ads displayed on the site
 *
 * @since      1.0.0
 * @package    adsense-owner-author-split
 * @subpackage adsense-owner-author-split/admin
 * @author     Josh Mallard <josh@limecuda.com>
 */

namespace GingerBeard\Adsense_Owner_Author_Split\Admin\Global_Settings;

class Global_Settings extends \Genesis_Admin_Boxes {

	/**
	 * Instance of this class
	 *
	 * @since     1.0.0
	 */
	protected static $instance;

	/**
	 * Used for getting an instance of this class
	 *
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Create menu and page for our Settings page
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		define( 'GINGERBEARD_ADSENSE_SPLIT', 'gingerbeard_adsense_settings_field' );

		$page_id = 'gingerbeard-adsense-split';

		$menu_ops = array(
			'submenu' => array(
				'parent_slug'  => 'genesis',
				'page_title'   => __( 'Adsense Owner/Author Split', 'adsense_owner_author_split' ),
				'menu_title'   => __( 'Adsense Split', 'adsense_owner_author_split' )
			)
		);

		$page_ops = array();

		$settings_field = GINGERBEARD_ADSENSE_SPLIT;

		$default_settings = apply_filters(
			'gingerbeard_adsense_global_settings',
			array(
				'show_content_ads'                  => 1,
				'owner_above_adsense_code'          => '',
				'owner_above_weight'                => '10',
				'owner_below_adsense_code'          => '',
				'owner_below_weight'                => '10',
				'owner_shortcode_adsense_code'      => '',
				'owner_shortcode_weight'            => '10',
			)
		);

		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitizer_filters' ) );

	}

	/**
	 * Sanitize our options
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function sanitizer_filters() {

		// Sanitize the adsense fields
		$new_fields = array(
			'owner_above_adsense_code',
			'owner_below_adsense_code',
			'owner_shortcode_adsense_code'
		);

		genesis_add_option_filter( 'unfiltered_html', $this->settings_field, $new_fields );

		// Sanitize the number fields
		$numbers = array(
			'owner_above_weight',
			'owner_below_weight',
			'owner_shortcode_weight'
		);

		genesis_add_option_filter( 'absint', $this->settings_field, $numbers );

		// Sanitize the boolean
		$true_false = array(
			'show_content_ads'
		);

		genesis_add_option_filter( 'one_zero', $this->settings_field, $true_false );
	}

	/**
 	 * Register meta boxes on the SEO Settings page.
 	 *
 	 * @since 1.0.0
 	 */
	function metaboxes() {

		add_meta_box( 'aoas_default_settings', __( 'Defaults', 'adsense_owner_author_split' ), array( $this, 'default_settings' ), $this->pagehook, 'main' );
		add_meta_box( 'aoas_above_ad_settings', __( 'Above-Content Ad', 'adsense_owner_author_split' ), array( $this, 'above_ad' ), $this->pagehook, 'main' );
		add_meta_box( 'aoas_below_ad_settings', __( 'Below-Content Ad', 'adsense_owner_author_split' ), array( $this, 'below_ad' ), $this->pagehook, 'main' );
		add_meta_box( 'aoas_shortcode_ad_settings', __( 'Shortcode Ad', 'adsense_owner_author_split' ), array( $this, 'shortcode_ad' ), $this->pagehook, 'main' );
	}

	/**
	 * Build the fields for the default settings metabox
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function default_settings() { ?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<p class="description"><?php _e( 'By default ads show automatically on all posts. Alternatively, check this box and ads will be hidden by default and then can be enabled on a per-post basis.', 'adsense_owner_author_split' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"></th>
					<td>
						<p>
							<label for="<?php $this->field_id( 'hide_content_ads' ); ?>"><input type="checkbox" name="<?php $this->field_name( 'hide_content_ads' ); ?>" id="<?php $this->field_id( 'hide_content_ads' ); ?>" value="1" <?php checked( $this->get_field_value( 'hide_content_ads' ) ); ?> /><?php _e( 'Hide Content Ads by Default', 'adsense_owner_author_split' ); ?></label>
						</p>
					</td>
				</tr>

			</tbody>
		</table>

	<?php
	}

	/**
	 * Build the fields for the above ads metabox
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function above_ad() { ?>

		<table class="form-table">
			<tbody>
				<script>
					function outputUpdate(input, weight) {
						document.querySelector(input).value = weight;
					}
				</script>

				<tr valign="top">
					<th scope="row"><label for="owner_above_adsense_code"><?php _e( 'Above-Content Adsense Code', 'adsense_owner_author_split' ); ?></label></th>
					<td>
						<p><textarea name="<?php echo $this->get_field_name( 'owner_above_adsense_code' ); ?>" class="regular-text" id="owner_above_adsense_code" cols="78" rows="8"><?php echo esc_attr( $this->get_field_value( 'owner_above_adsense_code' ) ); ?></textarea></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="owner_above_weight"><?php _e( 'Weight', 'adsense_owner_author_split' ); ?></label></th>
					<td>
						<p><input type="range" min="0" max="10" step="1" name="<?php echo $this->get_field_name( 'owner_above_weight' ); ?>" class="regular-text" id="owner_above_weight" value="<?php echo esc_attr( $this->get_field_value( 'owner_above_weight' ) ); ?>" oninput="outputUpdate('#owner_above_weight_output', value)" /></p>
						<p class="description">Show the Owner's ad above the content for every <strong><output for="owner_above_weight" id="owner_above_weight_output"><?php echo esc_attr( $this->get_field_value( 'owner_above_weight' ) ); ?></output></strong> out of 10 page loads</p>
					</td>
				</tr>

			</tbody>
		</table>

	<?php
	}

	/**
	 * Build the fields for the below ad metabox
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function below_ad() { ?>

		<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row"><label for="owner_below_adsense_code"><?php _e( 'Below-Content Adsense Code', 'adsense_owner_author_split' ); ?></label></th>
					<td>
						<p><textarea name="<?php echo $this->get_field_name( 'owner_below_adsense_code' ); ?>" class="regular-text" id="owner_above_adsense_code" cols="78" rows="8"><?php echo esc_attr( $this->get_field_value( 'owner_below_adsense_code' ) ); ?></textarea></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="owner_below_weight"><?php _e( 'Weight', 'adsense_owner_author_split' ); ?></label></th>
					<td>
						<p><input type="range" min="0" max="10" step="1" name="<?php echo $this->get_field_name( 'owner_below_weight' ); ?>" class="regular-text" id="owner_below_weight" value="<?php echo esc_attr( $this->get_field_value( 'owner_below_weight' ) ); ?>" oninput="outputUpdate('#owner_below_weight_output', value)" /></p>
						<p class="description">Show the Owner's ad below the content for every <strong><output for="owner_below_weight" id="owner_below_weight_output"><?php echo esc_attr( $this->get_field_value( 'owner_below_weight' ) ); ?></output></strong> out of 10 page loads</p>
					</td>
				</tr>

			</tbody>
		</table>

	<?php
	}

	/**
	 * Build the fields for the shortcode ad metabox
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function shortcode_ad() { ?>

		<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row"><label for="owner_shortcode_adsense_code"><?php _e( 'Shortcode Adsense Code', 'adsense_owner_author_split' ); ?></label></th>
					<td>
						<p><textarea name="<?php echo $this->get_field_name( 'owner_shortcode_adsense_code' ); ?>" class="regular-text" id="owner_shortcode_adsense_code" cols="78" rows="8"><?php echo esc_attr( $this->get_field_value( 'owner_shortcode_adsense_code' ) ); ?></textarea></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="owner_shortcode_weight"><?php _e( 'Weight', 'adsense_owner_author_split' ); ?></label></th>
					<td>
						<p><input type="range" min="0" max="10" step="1" name="<?php echo $this->get_field_name( 'owner_shortcode_weight' ); ?>" class="regular-text" id="owner_shortcode_weight" value="<?php echo esc_attr( $this->get_field_value( 'owner_shortcode_weight' ) ); ?>" oninput="outputUpdate('#owner_shortcode_weight_output', value)" /></p>
						<p class="description">Show the Owner's ad in the shortcode for every <strong><output for="owner_shortcode_weight" id="owner_shortcode_weight_output"><?php echo esc_attr( $this->get_field_value( 'owner_shortcode_weight' ) ); ?></output></strong> out of 10 page loads</p>
					</td>
				</tr>

			</tbody>
		</table>

	<?php
	}

}