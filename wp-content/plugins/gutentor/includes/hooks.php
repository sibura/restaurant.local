<?php

/**
 * The Gutentor theme hooks callback functionality of the plugin.
 *
 * @link       https://www.gutentor.com/
 * @since      1.0.0
 *
 * @package    Gutentor
 */

/**
 * The Gutentor theme hooks callback functionality of the plugin.
 *
 * Since Gutentor theme is hooks base theme, this file is main callback to add/remove/edit the functionality of the Gutentor Plugin
 *
 * @package    Gutentor
 * @author     Gutentor <info@gutentor.com>
 */
class Gutentor_Hooks {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( ) {}

	/**
	 * Main Gutentor_Hooks Instance
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @return object $instance Gutentor_Hooks Instance
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been ran previously
		if ( null === $instance ) {
			$instance = new Gutentor_Hooks;
			$instance->plugin_name = GUTENTOR_PLUGIN_NAME;
			$instance->version = GUTENTOR_VERSION;
		}

		// Always return the instance
		return $instance;
	}

	/**
	 * Callback functions for customize_register,
	 * Add Panel Section control
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param object $wp_customize
	 * @return void
	 */
	function customize_register( $wp_customize ) {

		$defaults = gutentor_get_default_options();


		/*adding sections for google map api*/
		$wp_customize->add_section( 'gutentor-theme-options', array(
			'priority'          => 100,
			'capability'        => 'edit_theme_options',
			'title'             => esc_html__( 'Gutentor Options', 'gutentor' ),
		) );

		/*Google map api*/
		$wp_customize->add_setting( 'gutentor_map_api', array(
			'type'             => 'option',
			'default'			=> $defaults['gutentor_map_api'],
			'sanitize_callback' => 'sanitize_text_field'
		) );
		$wp_customize->add_control( 'gutentor_map_api', array(
			'label'		        => esc_html__( 'Google Map API Key', 'gutentor' ),
			'section'           => 'gutentor-theme-options',
			'settings'          => 'gutentor_map_api',
			'type'	  	        => 'text'
		) );

		/*gutentor_dynamic_style_location*/
		$wp_customize->add_setting( 'gutentor_dynamic_style_location', array(
			'type'             => 'option',
			'default'			=> $defaults['gutentor_dynamic_style_location'],
			'sanitize_callback' => 'sanitize_text_field'
		) );


		$wp_customize->add_control( 'gutentor_dynamic_style_location', array(
			'choices'  	    => array(
				'default'   => esc_html__( 'Default', 'gutentor' ),
				'head'      => esc_html__( 'Head' , 'gutentor' ),
				'file'      => esc_html__( 'File' , 'gutentor' ),
			),
			'label'		    => esc_html__( 'Dynamic CSS Options', 'gutentor' ),
			'section'       => 'gutentor-theme-options',
			'settings'      => 'gutentor_dynamic_style_location',
			'type'	  	    => 'select'
		) );

		/*gutentor_font_awesome_version*/
		$wp_customize->add_setting( 'gutentor_font_awesome_version', array(
			'type'             => 'option',
			'default'			=> $defaults['gutentor_font_awesome_version'],
			'sanitize_callback' => 'sanitize_text_field'
		) );


		$wp_customize->add_control( 'gutentor_font_awesome_version', array(
			'choices'  	    => array(
				'5'   => esc_html__( 'Font Awesome 5', 'gutentor' ),
				'4'      => esc_html__( 'Font Awesome 4' , 'gutentor' ),
			),
			'label'		    => esc_html__( 'Font Awesome Version', 'gutentor' ),
			'section'       => 'gutentor-theme-options',
			'settings'      => 'gutentor_font_awesome_version',
			'type'	  	    => 'select'
		) );

		if( gutentor_get_theme_support()){
			$args = array(
				'numberposts' => 200,
				'post_type'   => 'wp_block'
			);

			$choices = array();
			$choices[0]= esc_html__( 'None', 'gutentor' );
			$lastposts = get_posts( $args );
			if ( $lastposts ) {
				foreach ( $lastposts as $post ) :
					$choices[absint($post->ID)] = esc_attr( $post->post_title );
				endforeach;
				wp_reset_postdata();
			}

			/*gutentor_header_template*/
			$wp_customize->add_setting( 'gutentor_header_template', array(
				'type'             => 'option',
				'default'			=> $defaults['gutentor_header_template'],
				'sanitize_callback' => 'sanitize_text_field'
			) );


			$wp_customize->add_control( 'gutentor_header_template', array(
				'choices'  	    => $choices,
				'label'		    => esc_html__( 'Header Template', 'gutentor' ),
				'section'       => 'gutentor-theme-options',
				'settings'      => 'gutentor_header_template',
				'type'	  	    => 'select'
			) );

			/*gutentor_footer_template*/
			$wp_customize->add_setting( 'gutentor_footer_template', array(
				'type'             => 'option',
				'default'			=> $defaults['gutentor_footer_template'],
				'sanitize_callback' => 'sanitize_text_field'
			) );


			$wp_customize->add_control( 'gutentor_footer_template', array(
				'choices'  	    => $choices,
				'label'		    => esc_html__( 'Header Template', 'gutentor' ),
				'section'       => 'gutentor-theme-options',
				'settings'      => 'gutentor_footer_template',
				'type'	  	    => 'select'
			) );
		}

	}

	/**
	 * Callback functions for block_categories,
	 * Adding Block Categories
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array $categories
	 * @return array
	 */
	public function add_block_categories( $categories ) {

		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'gutentor',
					'title' => __('Gutentor', 'gutentor'),
				),
			)
		);
	}

	/**
	 * Callback functions for init,
	 * Register Settings for Google Maps Block
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param null
	 * @return void
	 */
	public function register_gmap_settings() {

		register_setting(
			'gutentor_map_api',
			'gutentor_map_api',
			array(
				'type'              => 'string',
				'description'       => __( 'Google Map API key for the Google Maps Gutenberg Block.', 'gutentor' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => ''
			)
		);
		register_setting(
			'gutentor_dynamic_style_location',
			'gutentor_dynamic_style_location',
			array(
				'type'              => 'string',
				'description'       => __( 'Dynamic CSS options.', 'gutentor' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => ''
			)
		);
		register_setting(
			'gutentor_font_awesome_version',
			'gutentor_font_awesome_version',
			array(
				'type'              => 'string',
				'description'       => __( 'Font Awesome Version', 'gutentor' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => '5'
			)
		);
		register_setting(
			'gutentor_header_template',
			'gutentor_header_template',
			array(
				'type'              => 'string',
				'description'       => __( 'Header Template.', 'gutentor' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => ''
			)
		);
		register_setting(
			'gutentor_footer_template',
			'gutentor_footer_template',
			array(
				'type'              => 'string',
				'description'       => __( 'Footer Template.', 'gutentor' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => ''
			)
		);
	}

	/**
	 * Callback functions for enqueue_block_assets,
	 * Enqueue Gutenberg block assets for both frontend + backend.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param null
	 * @return void
	 */
	function block_assets() { // phpcs:ignore

		if( !is_admin()){

			/*Slick Slider Styles*/
			wp_enqueue_style(
				'slick',
				GUTENTOR_URL . '/assets/library/slick/slick' . GUTENTOR_SCRIPT_PREFIX . '.css',
				array(),
				'1.7.1'
			);
			wp_enqueue_style(
				'slick-theme',
				GUTENTOR_URL . '/assets/library/slick/slick-theme' . GUTENTOR_SCRIPT_PREFIX . '.css',
				array(),
				'1.7.1'
			);


			/*Animate CSS*/
			wp_enqueue_style(
				'animate',
				GUTENTOR_URL . '/assets/library/animatecss/animate' . GUTENTOR_SCRIPT_PREFIX . '.css',
				array(),
				'3.7.2'
			);
			

			// Scripts.
			wp_enqueue_script(
				'countUp', // Handle.
				GUTENTOR_URL . '/assets/library/countUp/countUp' . GUTENTOR_SCRIPT_PREFIX . '.js',
				array('jquery'), // Dependencies, defined above.
				'1.9.3', // Version: File modification time.
				true // Enqueue the script in the footer.
			);

			// Wow.
			wp_enqueue_script(
				'wow', // Handle.
				GUTENTOR_URL . '/assets/library/wow/wow' . GUTENTOR_SCRIPT_PREFIX . '.js',
				array('jquery'), // Dependencies, defined above.
				'1.2.1', // Version: File modification time.
				true // Enqueue the script in the footer.
			);

			//Waypoint js
			wp_enqueue_script(
				'waypoints', // Handle.
				GUTENTOR_URL . '/assets/library/waypoints/jquery.waypoints' . GUTENTOR_SCRIPT_PREFIX . '.js',
				array('jquery'), // Dependencies, defined above.
				'4.0.1', // Version: File modification time.
				true // Enqueue the script in the footer.
			);

			//Easy Pie Chart Js
			wp_enqueue_script(
				'jquery-easypiechart', // Handle.
				GUTENTOR_URL . '/assets/library/jquery-easypiechart/jquery.easypiechart' . GUTENTOR_SCRIPT_PREFIX . '.js',
				array('jquery'), // Dependencies, defined above.
				'2.1.7', // Version: File modification time.
				true // Enqueue the script in the footer.
			);

			//Slick Slider Js
			wp_enqueue_script(
				'slick', // Handle.
				GUTENTOR_URL . '/assets/library/slick/slick' . GUTENTOR_SCRIPT_PREFIX . '.js',
				array('jquery'), // Dependencies, defined above.
				'1.7.1', // Version: File modification time.
				true // Enqueue the script in the footer.
			);


			wp_enqueue_script('masonry');

			if ( has_block( 'gutentor/google-map' ) ) {

				// Get the API key
				if ( gutentor_get_options( 'gutentor_map_api' ) ) {
					$apikey =  gutentor_get_options('gutentor_map_api');
				} else {
					$apikey = false;
				}

				// Don't output anything if there is no API key
				if ( null === $apikey || empty( $apikey ) ) {
					return;
				}

				wp_enqueue_script(
					'gutentor-google-maps',
					GUTENTOR_URL . '/assets/js/google-map-loader' . GUTENTOR_SCRIPT_PREFIX . '.js',
					array('jquery'), // Dependencies, defined above.
					'1.0.0',
					true
				);

				wp_enqueue_script(
					'google-maps',
					'https://maps.googleapis.com/maps/api/js?key=' . $apikey . '&libraries=places&callback=initMapScript',
					array( 'gutentor-google-maps' ),
					'1.0.0',
					true
				);
			}

		}

        //Isotope Js
        wp_enqueue_script(
            'isotope', // Handle.
	        GUTENTOR_URL . '/assets/library/isotope/isotope.pkgd' . GUTENTOR_SCRIPT_PREFIX . '.js',
            array('jquery'), // Dependencies, defined above.
            '3.0.6', // Version: File modification time.
            true // Enqueue the script in the footer.
        );

        /*Magnific Popup Styles*/
        wp_enqueue_style(
            'magnific-popup',
            GUTENTOR_URL . '/assets/library/magnific-popup/magnific-popup' . GUTENTOR_SCRIPT_PREFIX . '.css',
            array(),
            '1.8.0'
        );

		/* Wpness Grid Styles*/
		wp_enqueue_style(
			'wpness-grid',
			GUTENTOR_URL . '/assets/library/wpness-grid/wpness-grid' . GUTENTOR_SCRIPT_PREFIX . '.css',
			array(),
			'1.0.0'
		);

		// Styles.
		if( 4 == gutentor_get_options('gutentor_font_awesome_version')){
			wp_enqueue_style(
				'fontawesome', // Handle.
				GUTENTOR_URL . '/assets/library/font-awesome-4.7.0/css/font-awesome' . GUTENTOR_SCRIPT_PREFIX . '.css',
				array(),
				'4'
			);
		}
		else{
			wp_enqueue_style(
				'fontawesome', // Handle.
				GUTENTOR_URL . '/assets/library/fontawesome/css/all' . GUTENTOR_SCRIPT_PREFIX . '.css',
				array(),
				'5'
			);
		}


		wp_enqueue_style(
			'gutentor-css', // Handle.
			GUTENTOR_URL . '/dist/blocks.style.build.css',
			array('wp-editor'), // Dependency to include the CSS after it.
			GUTENTOR_VERSION // Version: File modification time.
		);

        //magnify popup  Js
        wp_enqueue_script(
            'magnific-popup', // Handle.
	        GUTENTOR_URL . '/assets/library/magnific-popup/jquery.magnific-popup' . GUTENTOR_SCRIPT_PREFIX . '.js',
            array('jquery'), // Dependencies, defined above.
            '1.1.0', // Version: File modification time.
            true // Enqueue the script in the footer.
        );

		wp_enqueue_script(
			'gutentor-block', // Handle.
			GUTENTOR_URL . '/assets/js/gutentor' . GUTENTOR_SCRIPT_PREFIX . '.js',
			array('jquery'), // Dependencies, defined above.
			GUTENTOR_VERSION, // Version: File modification time.
			true // Enqueue the script in the footer.
		);

	}

	/**
	 * Callback functions for enqueue_block_editor_assets,
	 * Enqueue Gutenberg block assets for backend only.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param null
	 * @return void
	 */
	public function block_editor_assets() { // phpcs:ignore

		// Scripts.
		wp_enqueue_script(
			'gutentor-js', // Handle.
			GUTENTOR_URL . '/dist/blocks.build.js',//Block.build.js: We register the block here. Built with Webpack.
			array('lodash', 'wp-api', 'wp-i18n', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-editor', 'wp-edit-post', 'wp-element', 'wp-keycodes', 'wp-plugins', 'wp-rich-text' ,'wp-viewport', ), // Dependencies, defined above.
			GUTENTOR_VERSION, // Version: File modification time.
			true // Enqueue the script in the footer.
		);
		wp_set_script_translations( 'gutentor-js', 'gutentor' );

		wp_localize_script( 'gutentor-js', 'gutentor', array(
			'mapsAPI' => '',
			'dirUrl' => GUTENTOR_URL,
			'defaultImage' => GUTENTOR_URL.'assets/img/default-image.jpg',
			'gutentorSvg' => GUTENTOR_URL.'assets/img/gutentor.svg',
			'gutentorWhiteSvg' => GUTENTOR_URL.'assets/img/gutentor-white-logo.svg',
			'fontAwesomeVersion' => gutentor_get_options('gutentor_font_awesome_version'),
		) );

		// Scripts.
		wp_enqueue_script(
			'gutentor-editor-block-js', // Handle.
			GUTENTOR_URL . '/assets/js/block-editor' . GUTENTOR_SCRIPT_PREFIX . '.js',
			array('jquery','magnific-popup'), // Dependencies, defined above.
			GUTENTOR_VERSION, // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		// Styles.
		wp_enqueue_style(
			'gutentor-editor-css', // Handle.
			GUTENTOR_URL . '/dist/blocks.editor.build.css',
			array('wp-edit-blocks'), // Dependency to include the CSS after it.
			GUTENTOR_VERSION // Version: File modification time.
		);
	}

	/**
	 * Callback functions for body_class,
	 * Adding Body Class.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array $classes
	 * @return array
	 */
	function add_body_class( $classes ) {

		$classes[] = 'gutentor-active';
		return $classes;
	}

	/**
	 * Callback functions for body_class,
	 * Adding Admin Body Class.
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string $classes
	 * @return string
	 */
	function add_admin_body_class( $classes ) {
		// Wrong: No space in the beginning/end.
		$classes .= ' gutentor-active';

		return $classes;
	}

    /**
     * Adding Section Classes
     *
     * @param {array} output
     * @param {object} props
     * @return {array}
     */
     function add_section_classes( $output, $attributes ){

         $local_data                  = '';
         $blockComponentBGType        = (isset($attributes['blockComponentBGType'])) ? $attributes['blockComponentBGType'] : '';
         $blockComponentEnableOverlay = (isset($attributes['blockComponentEnableOverlay'])) ? $attributes['blockComponentEnableOverlay'] : '';

         /* Bg classes */
         $bg_class   = GutentorBackgroundOptionsCSSClasses($blockComponentBGType);
         $local_data = gutentor_concat_space($local_data, $bg_class);

         /*Overlay classes*/
         $overlay    = $blockComponentEnableOverlay ? 'has-gutentor-overlay' : '';
         $local_data = gutentor_concat_space($local_data, $overlay);

         /*Shape Top select classes*/
         $blockShapeTopSelect      = ($attributes['blockShapeTopSelect']) ? $attributes['blockShapeTopSelect'] : false;
         $blockShapeTopSelectClass = $blockShapeTopSelect ? 'has-gutentor-block-shape-top' : '';
         $local_data               = gutentor_concat_space($local_data, $blockShapeTopSelectClass);

         /*Shape Bottom select classes*/
         $blockShapeBottomSelect      = ($attributes['blockShapeBottomSelect']) ? $attributes['blockShapeBottomSelect'] : false;
         $blockShapeBottomSelectClass = $blockShapeBottomSelect ? 'has-gutentor-block-shape-bottom' : '';
         $local_data                  = gutentor_concat_space($local_data, $blockShapeBottomSelectClass);

         $local_data = gutentor_concat_space($output, $local_data);

        return $local_data;

    }

    /**
     * Advanced Block Shape Before Container
     * @param {string} $output
     * @return {object} $attributes
     */
    function addAdvancedBlockShapeTop($output, $attributes) {

        $blockShapeTopSelect = ($attributes['blockShapeTopSelect']) ? $attributes['blockShapeTopSelect'] : false;
        if (!$blockShapeTopSelect) {
            return $output;
        }
        $shape_data = '<div class="gutentor-block-shape-top"><span>' . $blockShapeTopSelect . '</span></div>';
        return gutentor_concat_space($output, $shape_data);
    }

    /**
     * Advanced Block Shape Before Container
     * @param {string} $output
     * @return {object} $attributes
     */
    function addAdvancedBlockShapeBottom($output, $attributes) {

        $blockShapeBottomSelect = ($attributes['blockShapeBottomSelect']) ? $attributes['blockShapeBottomSelect'] : false;
        if (!$blockShapeBottomSelect) {
            return $output;
        }
        $shape_data = '<div class="gutentor-block-shape-bottom"><span>' . $blockShapeBottomSelect . '</span></div>';
        return gutentor_concat_space($output, $shape_data);
    }


    /**
     * Adding Block Header
     *
     * @param {array} output
     * @param {object} props
     * @return {array}
     */
     function add_block_save_header( $output, $attributes ){

         if(!apply_filters('gutentor_save_block_header_data_enable',true,$attributes)){
            return $output;
         }
         $blockHeader  = '';
        $blockHeader = '<div classs="'.apply_filters('gutentor_save_block_header_class', 'gutentor-block-header', $attributes).'">';
        $blockHeader .= apply_filters( 'gutentor_save_block_header_data','', $attributes  );
        $blockHeader .= '</div>';
        return $output.$blockHeader;
    }

    /**
     * Adding Block Header
     *
     * @param {array} output
     * @param {object} props
     * @return {array}
     */
     function add_column_class( $output, $attributes ){

         if(!apply_filters('gutentor_edit_enable_column',true,$attributes)){
            return $output;
         }
         $local_data               = '';
         $blockItemsColumn_desktop = (isset($attributes['blockItemsColumn']['desktop'])) ? $attributes['blockItemsColumn']['desktop'] : '';
         $local_data               = gutentor_concat_space($local_data, $blockItemsColumn_desktop);
         $blockItemsColumn_tablet  = (isset($attributes['blockItemsColumn']['tablet'])) ? $attributes['blockItemsColumn']['tablet'] : '';
         $local_data               = gutentor_concat_space($local_data, $blockItemsColumn_tablet);
         $blockItemsColumn_mobile  = (isset($attributes['blockItemsColumn']['mobile'])) ? $attributes['blockItemsColumn']['mobile'] : '';
         $local_data               = gutentor_concat_space($local_data, $blockItemsColumn_mobile);
         return gutentor_concat_space($output, $local_data);
    }

    /**
     * Add Button Attributes
     *
     * @param {object} output
     * @param {string} buttonLink
     * @param {object} buttonLinkOptions
     * @return {object}
     */
    function addButtonLinkAttr( $output,$buttonLink, $buttonLinkOptions ){

        $target     = $buttonLinkOptions['openInNewTab'] ? '_blank' : '';
        $rel        = ($buttonLinkOptions['rel']) ? $buttonLinkOptions['rel'] : '';
        $a_href     = ($buttonLink) ? 'href="' . $buttonLink . '"' : '';
        $a_target   = ($target) ? 'target="' . $target . '" ' : '';
        $local_data = gutentor_concat_space($a_href, $a_target);
        $a_rel      = ($rel) ? 'rel="' . $rel . '" ' : '';
        $local_data = gutentor_concat_space($local_data, $a_rel);
        return gutentor_concat_space($output, $local_data);
    }

    /**
     * Callback functions for body_class,
     * Adding Admin Body Class.
     *
     * @since    1.0.0
     * @access   public
     *
     * @param string $classes
     * @return string
     */
    function gutentor_heading_title( $data,$attributes ){

        $output                        = '';
        $block_title_tag               = '';
        $block_title                   = '';
        $section_title_align           = '';
        $section_title_animation       = '';
        $section_title_animation_class = '';
	    $block_enable_design_title     = '';
	    $block_design_title            = '';
        if ( isset( $attributes['blockComponentTitleAlign'] ) ) {
            $section_title_align =  ($attributes['blockComponentTitleAlign']) ? $attributes['blockComponentTitleAlign'] : '';
        }
        if ( isset( $attributes['blockComponentTitleAnimation'] ) ) {
            $section_title_animation =  ($attributes['blockComponentTitleAnimation']) ? $attributes['blockComponentTitleAnimation'] : '';
            $section_title_animation_class =  ($attributes['blockComponentTitleAnimation']['Animation'] && 'none' != $attributes['blockComponentTitleAnimation']['Animation']) ? 'wow animated '.$attributes['blockComponentTitleAnimation']['Animation'] : '';
        }
        if ( isset( $attributes['blockComponentTitleTag'] ) ) {
            $block_title_tag =  ($attributes['blockComponentTitleTag']) ? $attributes['blockComponentTitleTag'] : '';
        }
        if ( isset( $attributes['blockComponentTitle'] ) ) {
            $block_title =  ($attributes['blockComponentTitle']) ? $attributes['blockComponentTitle'] : '';
        }
	    if ( isset( $attributes['blockComponentTitleDesignEnable'] )) {
		    $block_enable_design_title =  ($attributes['blockComponentTitleDesignEnable']) ? 'enable-title-design' : '';
	    }
	    if ( isset( $attributes['blockComponentTitleDesignEnable'] ) && isset( $attributes['blockComponentTitleSeperatorPosition'] )) {
		    $block_design_title =  ($attributes['blockComponentTitleDesignEnable'] && $attributes['blockComponentTitleSeperatorPosition']) ? $attributes['blockComponentTitleSeperatorPosition'] : 'seperator-bottom';
	    }

        $blockComponentTitleEnable = isset($attributes['blockComponentTitleEnable']) ? $attributes['blockComponentTitleEnable'] : false;
       if( $blockComponentTitleEnable ) {
        $output .= '<div class="gutentor-section-title '.gutentor_concat_space($block_enable_design_title,$block_design_title).' '.gutentor_concat_space($section_title_align,$section_title_animation_class). ' "  '.GutentorAnimationOptionsDataAttr($section_title_animation).'>' . "\n";
        $output .= '<'.$block_title_tag.' class="gutentor-title">' . "\n";
        $output .=  $block_title;
        $output .= '</'.$block_title_tag.'>' . "\n";
        $output .= '</div>' . "\n";
       }
        return $data.$output;
    }


    /**
     * Adding Class
     *
     * @param {array} output
     * @param {object} props
     * @return {array}
     */
    function addingBlogStyleOptionsClass( $output, $attributes ){


        if( 'gutentor/blog-post' !== $attributes['gutentorBlockName']){
            return $output;
        }
       $blog_style_class =  $attributes['blockBlogStyle'] ? $attributes['blockBlogStyle'] : '';
        return gutentor_concat_space($output, $blog_style_class);
    }



    /**
     * Remove Column Class in Blog post
     *
     * @param {array} output
     * @param {object} attributes
     * @return string
     */
    function remove_column_class_blog_post($output, $attributes) {

        if ('gutentor/blog-post' !== $attributes['gutentorBlockName']) {
            return $output;
        }
        if ($attributes['blockBlogStyle'] === 'blog-list') {
            return false;
        }
        return $output;
    }

    /**
     * Adding Section Animation Class
     *
     * @param {array} output
     * @param {object} props
     * @return {array}
     */
    function add_animation_class($output, $attributes) {

        $blockComponentAnimation =  $attributes['blockComponentAnimation'] ? $attributes['blockComponentAnimation'] : '';
        $animation_class = ($blockComponentAnimation && $attributes['blockComponentAnimation']['Animation'] && 'none' != $attributes['blockComponentAnimation']['Animation']) ? gutentor_concat_space('wow animated ', $attributes['blockComponentAnimation']['Animation']): '';
        return gutentor_concat_space($output, $animation_class);
    }

    /**
     * Adding Link to Post Thumbnails
     *
     * @param {array} output
     * @param {object} props
     * @return {array}
     */
    function add_link_to_post_thumbnails($output,$url, $attributes) {
        $output_wrap = '';
        $target = '';
        if(empty($output) || $output == null){
            return $output;
        }
        if(!$attributes['gutentorBlogPostImageLink']){
            return $output;
        }
        if($attributes['gutentorBlogPostImageLinkNewTab']){
            $target = 'target="_blank"';
        }
        $output_wrap .= '<a class="gutentor-single-item-image-link" href="'.$url.'" '.$target.'>';
        $output_wrap .= $output;
        $output_wrap .= '</a>';
        return $output_wrap;

    }

    /**
     * Adding Item Wrap Animation Class
     *
     * @param {array} output
     * @param {object} props
     * @return {array}
     */
    function add_Item_wrap_animation_class($output, $attributes) {

        $blockItemsWrapAnimation = isset($attributes['blockItemsWrapAnimation']) ? $attributes['blockItemsWrapAnimation'] : '';
        $animation_class = ($blockItemsWrapAnimation && $attributes['blockItemsWrapAnimation']['Animation'] && 'none' != $attributes['blockItemsWrapAnimation']['Animation']) ? gutentor_concat_space('wow animated ', $attributes['blockItemsWrapAnimation']['Animation']): '';
        return gutentor_concat_space($output, $animation_class);
    }

	/**
	 * Get value of gutentor_dynamic_style_location
	 *
	 * @param {string} $gutentor_dynamic_style_location
	 * @return string
	 */
    function get_dynamic_style_location( $gutentor_dynamic_style_location ){
	    if ( gutentor_get_options( 'gutentor_dynamic_style_location' ) ) {
		    $gutentor_dynamic_style_location =  gutentor_get_options('gutentor_dynamic_style_location');
	    }
	    return $gutentor_dynamic_style_location;
    }

    /**
     * Create Page Template
     * @param {string} $templates
     * @return string $templates
     */
    function gutentor_add_page_template ($templates) {
        $templates['template-gutentor-full-width.php'] = esc_html__('Gutentor Full Width','gutentor');
        $templates['template-gutentor-canvas.php'] = esc_html__('Gutentor Canvas','gutentor');
        return $templates;
    }

    /**
     * Redirect Custom Page Template
     * @param {string} $templates
     * @return string $templates
     */
    function gutentor_redirect_page_template ($template) {
        $post = get_post();
        $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        if ('template-gutentor-full-width.php' == basename ($page_template)) {
            $template = GUTENTOR_PATH . '/page-templates/template-gutentor-full-width.php';
            return $template;
        }
        elseif('template-gutentor-canvas.php' == basename ($page_template)) {
            $template = GUTENTOR_PATH . '/page-templates/template-gutentor-canvas.php';
            return $template;
        }
        return $template;
    }

	/**
	 * Image Option css
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array $data
	 * @param array $attributes
	 * @return array | boolean
	 */
	function image_option_css($data,$attributes) {

		$block_list = array('gutentor/blog-post');
		$block_list = apply_filters('gutentor_image_option_css_access_block',$block_list);

		if(!in_array($attributes['gutentorBlockName'] , $block_list)){
			return $data;
		}
		$local_dynamic_css            = array();
		$local_dynamic_css['all']     = '';
		$local_dynamic_css['tablet']  = '';
		$local_dynamic_css['desktop'] = '';

		/*Image overlay css*/
		$img_overlay_color_enable = $attributes['blockImageBoxImageOverlayColor']['enable'] ? $attributes['blockImageBoxImageOverlayColor']['enable'] : '';
		$img_overlay_color_normal = ($attributes['blockImageBoxImageOverlayColor'] && $attributes['blockImageBoxImageOverlayColor']['normal'] && isset($attributes['blockImageBoxImageOverlayColor']['normal']['rgb'])) ? gutentor_rgb_string($attributes['blockImageBoxImageOverlayColor']['normal']['rgb']) : '';
		$img_overlay_color_hover = ($attributes['blockImageBoxImageOverlayColor'] && $attributes['blockImageBoxImageOverlayColor']['hover'] && isset($attributes['blockImageBoxImageOverlayColor']['hover']['rgb'])) ? gutentor_rgb_string($attributes['blockImageBoxImageOverlayColor']['hover']['rgb']) : '';

		$local_dynamic_css['all'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-image-box .overlay{
                    '.gutentor_generate_css('background',($img_overlay_color_enable && $img_overlay_color_normal) ? $img_overlay_color_normal : null ) . '
            }';
		$local_dynamic_css['all'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item:hover .gutentor-single-item-image-box .overlay{
                    '.gutentor_generate_css('background',($img_overlay_color_enable && $img_overlay_color_hover) ? $img_overlay_color_hover : null ) . '
            }';
		$blockFullImageEnable      = (isset($attributes['blockFullImageEnable']) && $attributes['blockFullImageEnable']) ? $attributes['blockFullImageEnable'] : '';
		if ($blockFullImageEnable) {

			$blockSingleItemBoxPadding = (isset($attributes['blockSingleItemBoxPadding']) && $attributes['blockSingleItemBoxPadding']) ? $attributes['blockSingleItemBoxPadding'] : '';
			$local_dynamic_css['all'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-image-box{
                    '. GutentorBoxSingleDeviceNegativeSpacing( 'margin-top', $blockSingleItemBoxPadding, 'mobileTop' ). '
                    '. GutentorBoxSingleDeviceNegativeSpacing( 'margin-right', $blockSingleItemBoxPadding, 'mobileRight' ). '
                    '. GutentorBoxSingleDeviceNegativeSpacing( 'margin-left', $blockSingleItemBoxPadding, 'mobileLeft' ). '
            }';

			$local_dynamic_css['tablet'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-image-box{
                    '. GutentorBoxSingleDeviceNegativeSpacing( 'margin-top', $blockSingleItemBoxPadding, 'tabletTop' ). '
                    '. GutentorBoxSingleDeviceNegativeSpacing( 'margin-right', $blockSingleItemBoxPadding, 'tabletRight' ). '
                    '. GutentorBoxSingleDeviceNegativeSpacing( 'margin-left', $blockSingleItemBoxPadding, 'tabletLeft' ). ' 
            }';

			$local_dynamic_css['desktop'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-image-box{
                    '. GutentorBoxSingleDeviceNegativeSpacing( 'margin-top', $blockSingleItemBoxPadding, 'desktopTop' ). '
                    '. GutentorBoxSingleDeviceNegativeSpacing( 'margin-right', $blockSingleItemBoxPadding, 'desktopRight' ). '
                    '. GutentorBoxSingleDeviceNegativeSpacing( 'margin-left', $blockSingleItemBoxPadding, 'desktopLeft' ). '
            }';

		}
		$output = array_merge_recursive($data, $local_dynamic_css);
		return $output;
	}

	/**
	 * Repeater Item css
	 * repeater_item_css
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param array $data
	 * @param array $attributes
	 * @return array | boolean
	 */
	function repeater_item_css( $data, $attributes ) {

		$block_list = array('gutentor/blog-post');
		$block_list = apply_filters('gutentor_repeater_style_access_block',$block_list);

		if(!in_array($attributes['gutentorBlockName'] , $block_list)){
			return $data;
		}
		$local_dynamic_css            = array();
		$local_dynamic_css['all']     = '';
		$local_dynamic_css['tablet']  = '';
		$local_dynamic_css['desktop'] = '';

		/*Single Item Title */
		if($attributes['blockSingleItemTitleEnable']) {
			$title_color_enable = ($attributes['blockSingleItemTitleColor']['enable']) ? $attributes['blockSingleItemTitleColor']['enable'] : '';
			$title_margin       = isset($attributes['blockSingleItemTitleMargin']) ? $attributes['blockSingleItemTitleMargin'] : '';
			$title_padding      = isset($attributes['blockSingleItemTitlePadding']) ? $attributes['blockSingleItemTitlePadding'] : '';
			$title_typography   = isset($attributes['blockSingleItemTitleTypography']) ? $attributes['blockSingleItemTitleTypography'] : '';

			$local_dynamic_css['all'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-title,
             #section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-title a{
                     ' . gutentor_generate_css('color', ($title_color_enable && $attributes['blockSingleItemTitleColor']['normal']) ? $attributes['blockSingleItemTitleColor']['normal']['hex'] : null) . '
                     ' . gutentor_typography_options_css($title_typography) . '
                     ' . gutentor_box_four_device_options_css('margin', $title_margin) . '
                     ' . gutentor_box_four_device_options_css('padding', $title_padding) . '
            }';

			$local_dynamic_css['all'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item:hover .gutentor-single-item-title,
             #section-' . $attributes['blockID'] . ' .gutentor-single-item:hover .gutentor-single-item-title a{
                     ' . gutentor_generate_css('color', ($title_color_enable && $attributes['blockSingleItemTitleColor']['hover']) ? $attributes['blockSingleItemTitleColor']['hover']['hex'] : null) . '
            }';

			$local_dynamic_css['tablet'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-title,
            #section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-title a{
                 ' . gutentor_typography_options_responsive_css($title_typography, 'tablet') . '
                 ' . gutentor_box_four_device_options_css('margin', $title_margin, 'tablet') . '
                 ' . gutentor_box_four_device_options_css('padding', $title_padding, 'tablet') . '
            }';

			$local_dynamic_css['desktop'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-title,
            #section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-title a{
                 ' . gutentor_typography_options_responsive_css($title_typography, 'desktop') . '
                 ' . gutentor_box_four_device_options_css('margin', $title_margin, 'desktop') . '
                 ' . gutentor_box_four_device_options_css('padding', $title_padding, 'desktop') . '
            }';
		}

		/*Single Item Desc */
		if($attributes['blockSingleItemDescriptionEnable']) {
			$desc_color_enable = $attributes['blockSingleItemDescriptionColor']['enable'] ? $attributes['blockSingleItemDescriptionColor']['enable'] : '';
			$desc_margin       = isset($attributes['blockSingleItemDescriptionMargin']) ? $attributes['blockSingleItemDescriptionMargin'] : '';
			$desc_padding      = isset($attributes['blockSingleItemDescriptionPadding']) ? $attributes['blockSingleItemDescriptionPadding'] : '';
			$desc_typography   = isset($attributes['blockSingleItemDescriptionTypography']) ? $attributes['blockSingleItemDescriptionTypography'] : '';

			$local_dynamic_css['all'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-desc{
             ' . gutentor_generate_css('color', ($desc_color_enable && $attributes['blockSingleItemDescriptionColor']['normal']) ? $attributes['blockSingleItemDescriptionColor']['normal']['hex'] : null) . '
             ' . gutentor_typography_options_css($desc_typography) . '
             ' . gutentor_box_four_device_options_css('margin', $desc_margin) . '
             ' . gutentor_box_four_device_options_css('padding', $desc_padding) . '
            }';

			$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item:hover .gutentor-single-item-desc{
                ' . gutentor_generate_css('color', ($desc_color_enable && $attributes['blockSingleItemDescriptionColor']['hover']) ? $attributes['blockSingleItemDescriptionColor']['hover']['hex'] : null) . '
            }';
			$local_dynamic_css['tablet']  .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-desc{
                  ' . gutentor_typography_options_responsive_css($desc_typography, 'tablet') . '
                  ' . gutentor_box_four_device_options_css('margin', $desc_margin, 'tablet') . '
                  ' . gutentor_box_four_device_options_css('padding', $desc_padding, 'tablet') . '
            }';
			$local_dynamic_css['desktop'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item .gutentor-single-item-desc{
                  ' . gutentor_typography_options_responsive_css($desc_typography, 'desktop') . '
                  ' . gutentor_box_four_device_options_css('margin', $desc_margin, 'desktop') . '
                  ' . gutentor_box_four_device_options_css('padding', $desc_padding, 'desktop') . '
            }';
		}

		/*single Item Button*/
        $button_css                      = array();
        if($attributes['blockSingleItemButtonEnable']){
			$button                      = array();
			$button['blockID']           = $attributes['blockID'];
			$button['buttonColor']       = $attributes['blockSingleItemButtonColor'];
			$button['buttonTextColor']   = $attributes['blockSingleItemButtonTextColor'];
			$button['buttonMargin']      = $attributes['blockSingleItemButtonMargin'];
			$button['buttonPadding']     = $attributes['blockSingleItemButtonPadding'];
			$button['buttonIconOptions'] = $attributes['blockSingleItemButtonIconOptions'];
			$button['buttonIconMargin']  = $attributes['blockSingleItemButtonIconMargin'];
			$button['buttonIconPadding'] = $attributes['blockSingleItemButtonIconPadding'];
			$button['buttonBorder']      = $attributes['blockSingleItemButtonBorder'];
			$button['buttonBoxShadow']   = $attributes['blockSingleItemButtonBoxShadow'];
			$button['buttonTypography']  = $attributes['blockSingleItemButtonTypography'];
			$button['buttonClass']       = 'gutentor-single-item-button';
			$button_css                  = GutentorButtonCss($button);

        }

		/* Single Item Box padding/margin */
		$single_item_Box_margin  = isset($attributes['blockSingleItemBoxMargin']) ? $attributes['blockSingleItemBoxMargin'] : '';
		$single_item_Box_padding = isset($attributes['blockSingleItemBoxPadding']) ? $attributes['blockSingleItemBoxPadding'] : '';
		$single_item_box_border = isset($attributes['blockSingleItemBoxBorder']) ? $attributes['blockSingleItemBoxBorder'] : '';
		$single_item_box_shadow = isset($attributes['blockSingleItemBoxShadowOptions']) ? $attributes['blockSingleItemBoxShadowOptions'] : '';
		$single_item_BoxBg_Enable   = isset($attributes['blockSingleItemBoxColor']['enable']) ? $attributes['blockSingleItemBoxColor']['enable'] : '';
		$single_item_BoxBg_color   = isset($attributes['blockSingleItemBoxColor']['normal']) ? $attributes['blockSingleItemBoxColor']['normal'] : '';

		$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item{
            ' . gutentor_generate_css('background', ($single_item_BoxBg_Enable && $single_item_BoxBg_color && isset($attributes['blockSingleItemBoxColor']['normal']['rgb'])) ? gutentor_rgb_string($attributes['blockSingleItemBoxColor']['normal']['rgb']) : null) . '
            ' . gutentor_box_four_device_options_css('margin', $single_item_Box_margin) . '
            ' . gutentor_box_four_device_options_css('padding', $single_item_Box_padding) . '
            '.gutentor_border_css($single_item_box_border).'
            '.gutentor_border_shadow_css($single_item_box_shadow).'
        }';

		$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item:hover{
            ' . gutentor_generate_css('background', ($single_item_BoxBg_Enable && $single_item_BoxBg_color && isset($attributes['blockSingleItemBoxColor']['hover']['rgb'])) ? gutentor_rgb_string($attributes['blockSingleItemBoxColor']['hover']['rgb']) : null) . '
        }';

		$local_dynamic_css['tablet']  .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item{
            ' . gutentor_box_four_device_options_css('margin', $single_item_Box_margin, 'tablet') . '
            ' . gutentor_box_four_device_options_css('padding', $single_item_Box_padding, 'tablet') . '
        }';

		$local_dynamic_css['desktop'] .= '#section-' . $attributes['blockID'] . ' .gutentor-single-item{
            ' . gutentor_box_four_device_options_css('margin', $single_item_Box_margin, 'desktop') . '
            ' . gutentor_box_four_device_options_css('padding', $single_item_Box_padding, 'desktop') . '
        }';

		$output = array_merge_recursive($data, $local_dynamic_css);
		$output = array_merge_recursive($output, $button_css);
		return $output;
	}

	/**
	 * Header Template
	 * @param {string} $templates
	 * @return mixed
	 */
	function gutentor_header () {
		if( !gutentor_get_theme_support()){
			return false;
		}
		if ( !gutentor_get_options( 'gutentor_header_template' ) ) {
			return false;

		}

		$args = array(
			'p' => absint(gutentor_get_options( 'gutentor_header_template' )),
			'post_type'      => 'wp_block',
		);
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			echo "<div id='gutentor-site-header'>";
			while ( $query->have_posts() ) {
				$query->the_post();
				the_content();
			}

			wp_reset_postdata();
		}
	}

	/**
	 * Footer Template
	 * @param {string} $templates
	 * @return mixed
	 */
	function gutentor_footer () {
		if( !gutentor_get_theme_support()){
			return false;
		}
		if ( !gutentor_get_options( 'gutentor_footer_template' ) ) {
			return false;

		}

		$args = array(
			'p' => absint(gutentor_get_options( 'gutentor_footer_template' )),
			'post_type'      => 'wp_block',
		);
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			echo "<div id='gutentor-site-footer'>";
			while ( $query->have_posts() ) {
				$query->the_post();
				the_content();
			}
			wp_reset_postdata();
		}
	}
}

/**
 * Begins execution of the hooks.
 *
 * @since    1.0.0
 */
function gutentor_hooks( ) {
	return Gutentor_Hooks::instance();
}