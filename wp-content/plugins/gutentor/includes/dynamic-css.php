<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Dynamic_CSS' )):

	/**
	 * Create Dynamic CSS
	 * @package Gutentor
	 * @since 1.0.0
	 *
	 */
	class Gutentor_Dynamic_CSS {

		/**
		 * $all_google_fonts
		 *
		 * @var array
		 * @access public
		 * @since 1.0.0
		 *
		 */
		public $all_google_fonts = array();

		/**
		 * Main Instance
		 *
		 * Insures that only one instance of Gutentor_Dynamic_CSS exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return object
		 */
		public static function instance() {

			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new Gutentor_Dynamic_CSS;
			}

			// Always return the instance
			return $instance;
		}

		/**
		 * Run functionality with hooks
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public function run() {
			add_action( 'render_block', array( $this, 'remove_block_css' ), 9999,2 );
			add_filter( 'wp_head', 	array( $this, 'dynamic_css' ),99 );
			add_action( 'admin_bar_init', array( $this, 'add_edit_dynamic_css_file' ), 9999 );
			add_action( 'wp_enqueue_scripts', array( $this, 'dynamic_css_enqueue' ), 9999 );

			add_filter( 'wp_head', 	array( $this, 'enqueue_google_fonts' ),100 );
			add_filter( 'admin_head', 	array( $this, 'admin_enqueue_google_fonts' ) ,100);

		}

		/**
		 * Set all_google_fonts
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public function google_block_typography_prep($block){
			/*<<<<<<<<<=Google Typography Preparation*/
			if ( is_array( $block ) && isset( $block['attrs'] ) ){
				$typography_data = array_filter( $block['attrs'], function ($key) {
					return strpos($key, 'Typography');
				}, ARRAY_FILTER_USE_KEY );

				foreach ( $typography_data as $key => $typography ){
					if( is_array( $typography) && isset( $typography['fontType']) && 'google' == $typography['fontType'] ){
						$this->all_google_fonts[] = array(
							'family' => $typography['googleFont'],
							'font-weight' => $typography['fontWeight']
						);;
					}
				}
			}
			/*Google Typography Preparation=>>>>>>>>*/
		}

		/**
		 * add google font on admin
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void|boolean
		 */
		public function admin_enqueue_google_fonts(){
			global $pagenow;
			if (!is_admin()){
				return false;
			}

			if(  in_array( $pagenow, array( 'post.php', 'post-new.php' ) )) {
				global $post;
				$blocks = parse_blocks( $post->post_content );
				if ( ! is_array( $blocks ) || empty( $blocks ) ) {
					return false;
				}
				foreach ( $blocks as $i => $block ) {
					$this->google_block_typography_prep($block);

				}
				$this->enqueue_google_fonts();
			}
		}

		/**
		 * Remove style from Gutentor Blocks
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @param string $block_content
		 * @param array $block
		 * @return mixed
		 */
		public function remove_block_css( $block_content, $block ){

			if ( 'default' == apply_filters( 'gutentor_dynamic_style_location', 'head' ) ) {
				return $block_content;
			}

			if( !is_admin() && is_array($block) && isset( $block['blockName']) && strpos($block['blockName'], 'gutentor') !== false ){
				$block_content = preg_replace('~<style(.*?)</style>~Usi', "", $block_content);
			}
			return $block_content;
		}

		/**
		 * Add Googe Fonts
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @param string $block_content
		 * @param array $block
		 * @return Mixed
		 */
		public function enqueue_google_fonts() {

			/*font family wp_enqueue_style*/
			$all_google_fonts = apply_filters('gutentor_enqueue_google_fonts', $this->all_google_fonts );


			if ( empty( $all_google_fonts ) ) {
				return false;
			}

			$unique_google_fonts = array();
			if( !empty( $all_google_fonts )){
				foreach( $all_google_fonts as $single_google_font ){
					$font_family = str_replace( ' ', '+', $single_google_font['family'] );
					if( isset( $single_google_font['font-weight']) ){
						$unique_google_fonts[$font_family]['font-weight'][] = $single_google_font['font-weight'];
					}
				}
			}
			$google_font_family = '';
			if( !empty( $unique_google_fonts )){
				foreach( $unique_google_fonts as $font_family => $unique_google_font ){
					if( !empty( $font_family )){
						if ( $google_font_family ) {
							$google_font_family .= '|';
						}
						$google_font_family .= $font_family;
						if( isset( $unique_google_font['font-weight']) ){
							$unique_font_weights = array_unique( $unique_google_font['font-weight'] );
							if( !empty( $unique_font_weights )){
								$google_font_family .= ':' . join( ',', $unique_font_weights );
							}
							else{
								$google_font_family .= ':' . 'regular';
							}

						}
					}
				}
			}

			if ($google_font_family) {
				$google_font_family = str_replace( 'italic', 'i', $google_font_family );
				$fonts_url = add_query_arg(array(
					'family' => $google_font_family
				), '//fonts.googleapis.com/css');
				echo '<link id="gutentor-google-fonts" href="'.esc_url( $fonts_url ).'" rel="stylesheet" data-current-fonts="'.esc_attr(json_encode( $unique_google_fonts )).'" >';
			}
		}

		/**
		 * Minify CSS
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @param string $css
		 * @return mixed
		 */
		public function minify_css( $css = '' ) {

			// Return if no CSS
			if ( ! $css ) {
				return '';
			}

			// remove comments
			$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

			// Normalize whitespace
			$css = preg_replace( '/\s+/', ' ', $css );

			// Remove ; before }
			$css = preg_replace( '/;(?=\s*})/', '', $css );

			// Remove space after , : ; { } */ >
			$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

			// Remove space before , ; { }
			$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );

			// Strips leading 0 on decimal values (converts 0.5px into .5px)
			$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

			// Strips units if value is 0 (converts 0px to 0)
			$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

			/*Removing empty CSS Selector with PHP preg_replace*/
//			$css = preg_replace('/(?:[^\r\n,{}]+)(?:,(?=[^}]*{)|\s*{[\s]*})/', '', $css);

			// Trim
			$css = trim( $css );

			// Return minified CSS
			return $css;

		}

		/**
		 * Gutentor Common Attr Default Value
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		function get_block_common_default_attr() {
			$default_attr = [

				/*column*/
				'blockItemsColumn' => [
					'desktop' => 'grid-md-4',
					'tablet'     => 'grid-sm-4',
					'mobile'     =>  'grid-xs-12',
				],
				'blockSectionHtmlTag' => 'section',
				/*Advanced attr*/
				'blockComponentAnimation' => [
					'Animation' => 'none',
					'Delay'     => '',
					'Speed'     => '',
					'Iteration' => '',
				],
				'blockComponentBGType'  => '',
				'blockComponentBGImage'  => '',
				'blockComponentBGVideo'  => '',
				'blockComponentBGColor'  => '',
				'blockComponentBGImageSize'  => '',
				'blockComponentBGImagePosition'  => '',
				'blockComponentBGImageRepeat'  => '',
				'blockComponentBGImageAttachment'  => '',
				'blockComponentBGVideoLoop'  => true,
				'blockComponentBGVideoMuted'  => true,
				'blockComponentEnableOverlay'  => true,
				'blockComponentOverlayColor'  => '',
				'blockComponentBoxBorder' => [
					'borderStyle'        => 'none',
					'borderTop'          => '',
					'borderRight'        => '',
					'borderBottom'       => '',
					'borderLeft'         => '',
					'borderColorNormal'  => '',
					'borderColorHover'   => '',
					'borderRadiusType'   => 'px',
					'borderRadiusTop'    => '',
					'borderRadiusRight'  => '',
					'borderRadiusBottom' => '',
					'borderRadiusLeft'   => '',
				],
				'blockComponentMargin' => [
					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',
					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => 'px',
					'tabletLeft'    => '',
					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				],
				'blockComponentPadding' => [
					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',
					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => 'px',
					'tabletLeft'    => '',
					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				],
				'blockComponentBoxShadowOptions' => [
					'boxShadowColor'    => '',
					'boxShadowX'        => '',
					'boxShadowY'        => '',
					'boxShadowBlur'     => '',
					'boxShadowSpread'   => '',
					'boxShadowPosition' => '',
				],

				/*adv shape*/
				'blockShapeTopSelect' => '',
				'blockShapeTopSelectEnableColor' => '',
				'blockShapeTopSelectColor' => '',
				'blockShapeTopHeight' => [

					'type'    => 'px',
					'desktop' => '',
					'tablet'  => '',
					'mobile'  => '',
				],
				'blockShapeTopWidth' => [

					'type'    => 'px',
					'desktop' => '',
					'tablet'  => '',
					'mobile'  => '',
				],
				'blockShapeTopPosition' => '',
				'blockShapeBottomSelect' => '',
				'blockShapeBottomSelectEnableColor' => '',
				'blockShapeBottomSelectColor' => '',
				'blockShapeBottomHeight' => [
					'type'    => 'px',
					'desktop' => '',
					'tablet'  => '',
					'mobile'  => '',
				],
				'blockShapeBottomWidth' =>  [
					'type'    => 'px',
					'desktop' => '',
					'tablet'  => '',
					'mobile'  => '',
				],
				'blockShapeBottomPosition' => '',

				/*Block Title*/
				'blockComponentTitleEnable'          => true,
				'blockComponentTitle'                => __('Block Title', 'gutentor'),
				'blockComponentTitleTag'             => 'h2',
				'blockComponentTitleAlign'           => 'text-center',
				'blockComponentTitleColorEnable'     => true,
				'titleTag'                  => 'h3',
				'blockComponentTitleColor'           => [
					'hex' => '#111111',
				],
				'blockComponentTitleTypography'      => [

					'fontType'   => '',
					'systemFont' => '',
					'googleFont' => '',
					'customFont' => '',

					'desktopFontSize' => '',
					'tabletFontSize'  => '',
					'mobileFontSize'  => '',

					'fontWeight'        => '',
					'textTransform'     => '',
					'fontStyle'         => '',
					'textDecoration'    => '',
					'desktopLineHeight' => '',
					'tabletLineHeight'  => '',
					'mobileLineHeight'  => '',

					'desktopLetterSpacing' => '',
					'tabletLetterSpacing'  => '',
					'mobileLetterSpacing'  => '',

				],
				'blockComponentTitleMargin'      => [

					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',

					'tabletTop'    => '',
					'tabletRight'  => '',
					'tabletBottom' => '',
					'tabletLeft'   => '',

					'mobileTop'    => '',
					'mobileRight'  => '',
					'mobileBottom' => '',
					'mobileLeft'   => '',

				],
				'blockComponentTitlePadding'      => [

					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',

					'tabletTop'    => '',
					'tabletRight'  => '',
					'tabletBottom' => '',
					'tabletLeft'   => '',

					'mobileTop'    => '',
					'mobileRight'  => '',
					'mobileBottom' => '',
					'mobileLeft'   => '',
				],

				'blockComponentTitleAnimation' =>[
					'Animation' => 'px',
					'Delay'     => '',
					'Speed'     => '',
					'Iteration' => '',
				],
				'blockComponentTitleDesignEnable' =>true,
				'blockComponentTitleSeperatorPosition' => 'seperator-bottom',

				/*Block Sub title*/
				'blockComponentSubTitleEnable'          => false,
				'blockComponentSubTitle'                => __('Block Sub Title', 'gutentor'),
				'blockComponentSubTitleTag'             => 'p',
				'blockComponentSubTitleAlign'           => 'text-center',
				'blockComponentSubTitleColorEnable'     => true,
				'blockComponentSubTitleColor'           => [
					'hex' => '#111111',
				],
				'blockComponentSubTitleTypography'      => [

					'fontType'       => '',
					'systemFont'     => '',
					'googleFont'     => '',
					'customFont'     => '',

					'desktopFontSize'     => '',
					'tabletFontSize'     => '',
					'mobileFontSize'     => '',

					'fontWeight'     => '',
					'textTransform'  => '',
					'fontStyle'      => '',
					'textDecoration' => '',
					'desktopLineHeight'     => '',
					'tabletLineHeight'     => '',
					'mobileLineHeight'     => '',

					'desktopLetterSpacing'     => '',
					'tabletLetterSpacing'     => '',
					'mobileLetterSpacing'     => '',

				],
				'blockComponentSubTitleMargin'      => [

					'type'       => 'px',
					'desktopTop'     => '',
					'desktopRight'     => '',
					'desktopBottom'     => '',
					'desktopLeft'     => '',

					'tabletTop'     => '',
					'tabletRight'     => '',
					'tabletBottom'     => '',
					'tabletLeft'     => '',

					'mobileTop'     => '',
					'mobileRight'  => '',
					'mobileBottom'      => '',
					'mobileLeft' => '',

				],
				'blockComponentSubTitlePadding'      => [

					'type'       => 'px',
					'desktopTop'     => '',
					'desktopRight'     => '',
					'desktopBottom'     => '',
					'desktopLeft'     => '',

					'tabletTop'     => '',
					'tabletRight'     => '',
					'tabletBottom'     => '',
					'tabletLeft'     => '',

					'mobileTop'     => '',
					'mobileRight'  => '',
					'mobileBottom'      => '',
					'mobileLeft' => '',

				],
				'blockComponentSubTitleAnimation'      => [

					'Animation'       => 'px',
					'Delay'     => '',
					'Speed'     => '',
					'Iteration'     => '',

				],
				'blockComponentSubTitleDesignEnable' =>false,
				'blockComponentSubTitleSeperatorPosition' => 'seperator-bottom',

				/*primary button*/
				'blockComponentPrimaryButtonEnable' => false,
				'blockComponentPrimaryButtonLinkOptions' => [
					'openInNewTab' => false,
					'rel' => '',
				],
				'blockComponentPrimaryButtonColor' => [
					'enable' => true,
					'normal' => [
						'hex' => '#275cf6',
						'rgb' => array(
							'r' => '39',
							'g' => '92',
							'b' => '246',
							'a' => '1',
						)
					],
					'hover' => [
						'hex' => '#1949d4',
						'rgb' => [
							'r' => '25',
							'g' => '73',
							'b' => '212',
							'a' => '1',
						]
					],
				],
				'blockComponentPrimaryButtonTextColor' => [
					'enable' => true,
					'normal' => [
						'hex' => '#fff',
					],
					'hover' => '',
				],
				'blockComponentPrimaryButtonMargin'    => [
					'type' => 'px',
					'desktopTop' => '',
					'desktopRight' => '',
					'desktopBottom' => '',
					'desktopLeft' => '',
					'tabletTop' => '',
					'tabletRight' => '',
					'tabletBottom' => '',
					'tabletLeft' => '',
					'mobileTop' => '',
					'mobileRight' => '',
					'mobileBottom' => '',
					'mobileLeft' => '',
				],
				'blockComponentPrimaryButtonPadding'   => [
					'type' => 'px',
					'desktopTop' => '12',
					'desktopRight' => '25',
					'desktopBottom' => '12',
					'desktopLeft' => '25',
					'tabletTop' => '12',
					'tabletRight' => '25',
					'tabletBottom' => '12',
					'tabletLeft' => '25',
					'mobileTop' => '12',
					'mobileRight' => '25',
					'mobileBottom' => '12',
					'mobileLeft' => '25',
				],
				'blockComponentPrimaryButtonIconOptions'=> [

					'position' => 'hide',
					'size' => '',
				],
				'blockComponentPrimaryButtonIconMargin' => [
					'type' => 'px',
					'desktopTop' => '',
					'desktopRight' => '',
					'desktopBottom' => '',
					'desktopLeft' => '',
					'tabletTop' => '',
					'tabletRight' => '',
					'tabletBottom' => '',
					'tabletLeft' => '',
					'mobileTop' => '',
					'mobileRight' => '',
					'mobileBottom' => '',
					'mobileLeft' => '',
				],
				'blockComponentPrimaryButtonIconPadding' => [
					'type' => 'px',
					'desktopTop' => '',
					'desktopRight' => '',
					'desktopBottom' => '',
					'desktopLeft' => '',
					'tabletTop' => '',
					'tabletRight' => '',
					'tabletBottom' => '',
					'tabletLeft' => '',
					'mobileTop' => '',
					'mobileRight' => '',
					'mobileBottom' => '',
					'mobileLeft' => '',
				],
				'blockComponentPrimaryButtonBorder' => [
					'borderStyle'        => '',
					'borderTop'          => '',
					'borderRight'        => '',
					'borderBottom'       => '',
					'borderLeft'         => '',
					'borderColorNormal'  => '',
					'borderColorHover'   => '',
					'borderRadiusType'   => 'px',
					'borderRadiusTop'    => '3',
					'borderRadiusRight'  => '3',
					'borderRadiusBottom' => '3',
					'borderRadiusLeft'   => '3',

				],
				'blockComponentPrimaryButtonBoxShadow'  => [
					'boxShadowColor' => '',
					'boxShadowX' => '',
					'boxShadowY' => '',
					'boxShadowBlur' => '',
					'boxShadowSpread' => '',
					'boxShadowPosition' => '',
				],
				'blockComponentPrimaryButtonTypography'=> [
					'fontType'             => 'system',
					'systemFont'           => '',
					'googleFont'           => '',
					'customFont'           => '',

					'desktopFontSize'      => '16',
					'tabletFontSize'       => '16',
					'mobileFontSize'       => '16',

					'fontWeight'           => '',
					'textTransform'        =>'normal',
					'fontStyle'            => '',
					'textDecoration'      => '',

					'desktopLineHeight'    => '',
					'tabletLineHeight'     => '',
					'mobileLineHeight'     => '',

					'desktopLetterSpacing' => '',
					'tabletLetterSpacing'  => '',
					'mobileLetterSpacing'  => ''
				],
				'blockComponentPrimaryButtonIcon' => [

					'label'             => 'fa-book',
					'value'             => 'fas fa-book',
					'code'             => 'f108',
				],
				'blockComponentPrimaryButtonText'  => __('View More'),
				'blockComponentPrimaryButtonLink'  => __('#'),

				/*Secondary Button*/
				'blockComponentSecondaryButtonEnable' => false,
				'blockComponentSecondaryButtonLinkOptions' => [
					'openInNewTab' => false,
					'rel' => '',
				],
				'blockComponentSecondaryButtonColor' => [
					'enable' => true,
					'normal' => [
						'hex' => '#275cf6',
						'rgb' => array(
							'r' => '39',
							'g' => '92',
							'b' => '246',
							'a' => '1',
						)
					],
					'hover' => [
						'hex' => '#1949d4',
						'rgb' => [
							'r' => '25',
							'g' => '73',
							'b' => '212',
							'a' => '1',
						]
					],
				],
				'blockComponentSecondaryButtonTextColor' => [
					'enable' => true,
					'normal' => [
						'hex' => '#fff',
					],
					'hover' => '',
				],
				'blockComponentSecondaryButtonMargin'    => [
					'type' => 'px',
					'desktopTop' => '',
					'desktopRight' => '',
					'desktopBottom' => '',
					'desktopLeft' => '',
					'tabletTop' => '',
					'tabletRight' => '',
					'tabletBottom' => '',
					'tabletLeft' => '',
					'mobileTop' => '',
					'mobileRight' => '',
					'mobileBottom' => '',
					'mobileLeft' => '',
				],
				'blockComponentSecondaryButtonPadding'   => [
					'type' => 'px',
					'desktopTop' => '12',
					'desktopRight' => '25',
					'desktopBottom' => '12',
					'desktopLeft' => '25',
					'tabletTop' => '12',
					'tabletRight' => '25',
					'tabletBottom' => '12',
					'tabletLeft' => '25',
					'mobileTop' => '12',
					'mobileRight' => '25',
					'mobileBottom' => '12',
					'mobileLeft' => '25',
				],
				'blockComponentSecondaryButtonIconOptions'=> [
					'position' => 'hide',
					'size' => '',
				],
				'blockComponentSecondaryButtonIconMargin' => [
					'type' => 'px',
					'desktopTop' => '',
					'desktopRight' => '',
					'desktopBottom' => '',
					'desktopLeft' => '',
					'tabletTop' => '',
					'tabletRight' => '',
					'tabletBottom' => '',
					'tabletLeft' => '',
					'mobileTop' => '',
					'mobileRight' => '',
					'mobileBottom' => '',
					'mobileLeft' => '',
				],
				'blockComponentSecondaryButtonIconPadding' => [
					'type' => 'px',
					'desktopTop' => '',
					'desktopRight' => '',
					'desktopBottom' => '',
					'desktopLeft' => '',
					'tabletTop' => '',
					'tabletRight' => '',
					'tabletBottom' => '',
					'tabletLeft' => '',
					'mobileTop' => '',
					'mobileRight' => '',
					'mobileBottom' => '',
					'mobileLeft' => '',
				],
				'blockComponentSecondaryButtonBorder' => [
					'borderStyle' => '',
					'borderTop' => '',
					'borderRight' => '',
					'borderBottom' => '',
					'borderLeft' => '',
					'borderColorNormal' => '',
					'borderColorHover' => '',
					'borderRadiusType' => 'px',
					'borderRadiusTop' => '3',
					'borderRadiusRight' => '3',
					'borderRadiusBottom' => '3',
					'borderRadiusLeft' => '3',
				],
				'blockComponentSecondaryButtonBoxShadow'  => [
					'boxShadowColor' => '',
					'boxShadowX' => '',
					'boxShadowY' => '',
					'boxShadowBlur' => '',
					'boxShadowSpread' => '',
					'boxShadowPosition' => '',
				],
				'blockComponentSecondaryButtonTypography'=> [
					'fontType'             => 'system',
					'systemFont'           => '',
					'googleFont'           => '',
					'customFont'           => '',

					'desktopFontSize'      => '16',
					'tabletFontSize'       => '16',
					'mobileFontSize'       => '16',

					'fontWeight'           => '',
					'textTransform'        =>'normal',
					'fontStyle'            => '',
					'textDecoration'      => '',

					'desktopLineHeight'    => '',
					'tabletLineHeight'     => '',
					'mobileLineHeight'     => '',

					'desktopLetterSpacing' => '',
					'tabletLetterSpacing'  => '',
					'mobileLetterSpacing'  => ''
				],
				'blockComponentSecondaryButtonIcon' => [
					'label'             => 'fa-book',
					'value'             => 'fas fa-book',
					'code'             => 'f108',
				],
				'blockComponentSecondaryButtonText'  => __('View More','gutentor'),
				'blockComponentSecondaryButtonLink'  => __('#'),

				/*carousel attr*/
				'blockItemsCarouselEnable' => false,
				'blockItemsCarouselDots' => false,
				'blockItemsCarouselDotsTablet' => false,
				'blockItemsCarouselDotsMobile' => false,
				'blockItemsCarouselDotsColor' => [
					'enable' => false,
					'normal' => '',
					'hover'  => ''
				],
				'blockItemsCarouselDotsButtonBorder' => [
					'borderStyle'        => 'none',
					'borderTop'          => '',
					'borderRight'        => '',
					'borderBottom'       => '',
					'borderLeft'         => '',
					'borderColorNormal'  => '',
					'borderColorHover'   => '',
					'borderRadiusType'   => 'px',
					'borderRadiusTop'    => '',
					'borderRadiusRight'  => '',
					'borderRadiusBottom' => '',
					'borderRadiusLeft'   => ''
				],
				'blockItemsCarouselDotsButtonHeight' => [],
				'blockItemsCarouselDotsButtonWidth' => [],
				'blockItemsCarouselArrows' => true,
				'blockItemsCarouselArrowsTablet' => true,
				'blockItemsCarouselArrowsMobile' => true,
				'blockItemsCarouselArrowsBgColor' => [
					'enable' => false,
					'normal' => '',
					'hover'  => ''
				],
				'blockItemsCarouselArrowsTextColor' => [
					'enable' => false,
					'normal' => '',
					'hover'  => ''
				],
				'blockItemsCarouselInfinite' => false,
				'blockItemsCarouselFade' => false,
				'blockItemsCarouselAutoPlay' => false,
				'blockItemsCarouselSlideSpeed' => 300,
				'blockItemsCarouselCenterMode' => false,
				'blockItemsCarouselCenterPadding' => 60,
				'blockItemsCarouselAutoPlaySpeed' => 1200,
				'blockItemsCarouselResponsiveSlideItem' => [
					'desktop' => '4',
					'tablet'=> '3',
					'mobile' => '2',
				],
				'blockItemsCarouselResponsiveSlideScroll' => [
					'desktop' => '4',
					'tablet'=> '3',
					'mobile' => '2',
				],

				'blockItemsCarouselNextArrow' => [
					'label' => 'fa-angle-right',
					'value'=> 'fas fa-angle-right',
					'code' => 'f105',
				],
				'blockItemsCarouselPrevArrow' => [
					'label' => 'fa-angle-left',
					'value'=> 'fas fa-angle-left',
					'code' => 'f104',
				],
				'blockItemsCarouselButtonIconSize' => 16,
				'blockItemsCarouselArrowButtonHeight' => [
					'desktop' => '40',
					'tablet'  => '30',
					'mobile'  => '20',
				],
				'blockItemsCarouselArrowButtonWidth' => [
					'desktop' => '40',
					'tablet'  => '30',
					'mobile'  => '20',
				],
				'blockItemsCarouselArrowButtonBorder' => [
					'borderStyle'        => 'none',
					'borderTop'          => '',
					'borderRight'        => '',
					'borderBottom'       => '',
					'borderLeft'         => '',
					'borderColorNormal'  => '',
					'borderColorHover'   => '',
					'borderRadiusType'   => 'px',
					'borderRadiusTop'    => '',
					'borderRadiusRight'  => '',
					'borderRadiusBottom' => '',
					'borderRadiusLeft'   => ''
				],

				/*Image option attr*/
				'blockImageBoxImageOverlayColor' => [
					'enable' => false,
					'normal' => '',
					'hover'  => '',
				],
				'blockFullImageEnable' => false,
				'blockEnableImageBoxWidth' => false,
				'blockImageBoxWidth' =>'',
				'blockEnableImageBoxHeight' => false,
				'blockImageBoxHeight' =>'',
				'blockEnableImageBoxDisplayOptions' => false,
				'blockImageBoxDisplayOptions' => 'normal-image',
				'blockImageBoxBackgroundImageOptions' => [

					'backgroundImage' => '',

					'desktopHeight' => '',
					'tabletHeight'  => '',
					'mobileHeight'  => '',

					'backgroundSize'       => '',
					'backgroundPosition'   => '',
					'backgroundRepeat'     => '',
					'backgroundAttachment' => '',
				],
				'blockEnableImageBoxBorder' => false,
				'blockImageBoxBorder' => [
					'borderStyle'        => 'none',
					'borderTop'          => '',
					'borderRight'        => '',
					'borderBottom'       => '',
					'borderLeft'         => '',
					'borderColorNormal'  => '',
					'borderColorHover'   => '',
					'borderRadiusType'   => 'px',
					'borderRadiusTop'    => '',
					'borderRadiusRight'  => '',
					'borderRadiusBottom' => '',
					'borderRadiusLeft'   => '',
				],

				/*Item Wrap*/
				'blockItemsWrapMargin' => [
					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',
					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',
					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				],
				'blockItemsWrapPadding' => [
					'type'          => 'px',
					'desktopTop'    => '',
					'desktopRight'  => '',
					'desktopBottom' => '',
					'desktopLeft'   => '',
					'tabletTop'     => '',
					'tabletRight'   => '',
					'tabletBottom'  => '',
					'tabletLeft'    => '',
					'mobileTop'     => '',
					'mobileRight'   => '',
					'mobileBottom'  => '',
					'mobileLeft'    => '',
				],
				'blockItemsWrapAnimation' => [
					'Animation' => 'px',
					'Delay'     => '',
					'Speed'     => '',
					'Iteration' => '',
				],
			];
			$default_attr = apply_filters('gutentor_common_attr_default_val',$default_attr);
			return $default_attr;
		}

		/**
		 * Gutentor Common Dynamic Style
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 *
		 * @param string $attributes
		 * @return mixed
		 */
		function get_block_common_css( $attributes ) {

			$attr_default_val       = $this->get_block_common_default_attr();
			$attributes             = wp_parse_args($attributes, $attr_default_val);

			$local_dynamic_css            = array();
			$local_dynamic_css['all']     = '';
			$local_dynamic_css['tablet']  = '';
			$local_dynamic_css['desktop'] = '';

			$advBorder     = $attributes['blockComponentBoxBorder'] ? $attributes['blockComponentBoxBorder']  : false;
			$advBoxShadow     = $attributes['blockComponentBoxShadowOptions'] ? $attributes['blockComponentBoxShadowOptions']  : false;
			$adv_Margin     = $attributes['blockComponentMargin'] ? $attributes['blockComponentMargin']  : false;
			$adv_Padding     = $attributes['blockComponentPadding'] ? $attributes['blockComponentPadding']  : false;

			/* global css*/
			if($attributes['blockComponentEnableOverlay']){
				$bg_overlay_color = (($attributes['blockComponentOverlayColor']) && isset($attributes['blockComponentOverlayColor']['rgb']))  ? $attributes['blockComponentOverlayColor']['rgb'] : '';
				$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . '.has-gutentor-overlay::after {
                '.gutentor_generate_css('background-color',$bg_overlay_color ? gutentor_rgb_string($bg_overlay_color) :null).'
            }';
			}

			/* Common Advanced Css */
			$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . '{
            '.gutentor_advanced_background_css($attributes) . ' 
            '.gutentor_box_four_device_options_css('margin',$adv_Margin).'
            '.gutentor_box_four_device_options_css('padding',$adv_Padding).'
            '.gutentor_border_css($advBorder).'
            '.gutentor_border_shadow_css($advBoxShadow).'
        }';
			$local_dynamic_css['tablet']  .= '#section-' . $attributes['blockID'] . '{
            '.gutentor_box_four_device_options_css('margin',$adv_Margin,'tablet').'
            '.gutentor_box_four_device_options_css('padding',$adv_Padding,'tablet').'
        }';
			$local_dynamic_css['desktop'] .= '#section-' . $attributes['blockID'] . '{
          '.gutentor_box_four_device_options_css('margin',$adv_Margin,'desktop').'
          '.gutentor_box_four_device_options_css('padding',$adv_Padding,'desktop').'
        }';

			/*adv Top shape*/
			$blockShapeTopSelect            = $attributes['blockShapeTopSelect'] ? $attributes['blockShapeTopSelect'] : false;
			$blockShapeTopSelectEnableColor = $attributes['blockShapeTopSelectEnableColor'] ? $attributes['blockShapeTopSelectEnableColor'] : false;
			$blockShapeTopPosition          = $attributes['blockShapeTopPosition'] ? $attributes['blockShapeTopPosition'] : false;

			/*top shape height*/
			$blockShapeTopHeight            = $attributes['blockShapeTopHeight'] ? $attributes['blockShapeTopHeight'] : false;

			/* top shape width*/
			$blockShapeTopWidth             = $attributes['blockShapeTopWidth'] ? $attributes['blockShapeTopWidth'] : false;

			/*fill shape*/
			$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . ' .gutentor-block-shape-top svg path{
             '.gutentor_generate_css('fill',(($blockShapeTopSelect && $blockShapeTopSelectEnableColor && isset($attributes['blockShapeTopSelectColor']['rgb'])) ? gutentor_rgb_string($attributes['blockShapeTopSelectColor']['rgb']):null)).'
        }';

			/* top shape height and width*/
			$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . ' .gutentor-block-shape-top svg{
            ' . gutentor_responsive_height_width('height',$blockShapeTopHeight) . ' 
            ' . gutentor_responsive_height_width('width',$blockShapeTopWidth) . ' 
        }';

			/*top shape tablet height and width */
			$local_dynamic_css['tablet']     .= '#section-' . $attributes['blockID'] . ' .gutentor-block-shape-top svg{
            ' . gutentor_responsive_height_width('height',$blockShapeTopHeight,'tablet') . ' 
            ' . gutentor_responsive_height_width('width',$blockShapeTopWidth,'tablet') . ' 
        }';

			/*top shape desktop height and width */
			$local_dynamic_css['desktop']     .= '#section-' . $attributes['blockID'] . ' .gutentor-block-shape-top svg{
            ' . gutentor_responsive_height_width('height',$blockShapeTopHeight,'desktop') . ' 
            ' . gutentor_responsive_height_width('width',$blockShapeTopWidth,'desktop') . ' 
        }';


			/*adv Bottom shape*/
			$blockShapeBottomSelect             = $attributes['blockShapeBottomSelect'] ? $attributes['blockShapeBottomSelect'] : false;
			$blockShapeBottomSelectEnableColor  = $attributes['blockShapeBottomSelectEnableColor'] ? $attributes['blockShapeBottomSelectEnableColor'] : false;
			$blockShapeBottomPosition           = $attributes['blockShapeBottomPosition'] ? $attributes['blockShapeBottomPosition'] : false;

			/*bottom shape height*/
			$blockShapeBottomHeight            = $attributes['blockShapeBottomHeight'] ? $attributes['blockShapeBottomHeight'] : false;

			/*bottom shape width*/
			$blockShapeBottomWidth            = $attributes['blockShapeBottomWidth'] ? $attributes['blockShapeBottomWidth'] : false;

			/* fill shape*/
			$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . ' .gutentor-block-shape-bottom svg path{
             '.gutentor_generate_css('fill',($blockShapeBottomSelect && $blockShapeBottomSelectEnableColor && isset($attributes['blockShapeBottomSelectColor']['rgb'])) ? gutentor_rgb_string($attributes['blockShapeBottomSelectColor']['rgb']):null).'
        }';

			/* bottom shape height and width*/
			$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . ' .gutentor-block-shape-bottom svg{
            ' . gutentor_responsive_height_width('height',$blockShapeBottomHeight) . ' 
            ' . gutentor_responsive_height_width('width',$blockShapeBottomWidth) . '
        }';

			/* bottom shape tablet height and width*/
			$local_dynamic_css['tablet']     .= '#section-' . $attributes['blockID'] . ' .gutentor-block-shape-bottom svg{
            ' . gutentor_responsive_height_width('height',$blockShapeBottomHeight,'tablet') . ' 
            ' . gutentor_responsive_height_width('width',$blockShapeBottomWidth,'tablet') . '
        }';

			/* bottom shape desktop height and width*/
			$local_dynamic_css['desktop']     .= '#section-' . $attributes['blockID'] . ' .gutentor-block-shape-bottom svg{
            ' . gutentor_responsive_height_width('height',$blockShapeBottomHeight,'desktop') . ' 
            ' . gutentor_responsive_height_width('width',$blockShapeBottomWidth,'desktop') . '
        }';



			/*Item Wrap*/
			$item_wrap_Margin     = $attributes['blockItemsWrapMargin'] ? $attributes['blockItemsWrapMargin']  : false;
			$item_wrap_Padding     = $attributes['blockItemsWrapPadding'] ? $attributes['blockItemsWrapPadding']  : false;
			$local_dynamic_css['all']     .= '#section-' . $attributes['blockID'] . ' .gutentor-grid-item-wrap{
            '.gutentor_box_four_device_options_css('margin',$item_wrap_Margin).'
            '.gutentor_box_four_device_options_css('padding',$item_wrap_Padding).'
        }';
			$local_dynamic_css['tablet']  .= '#section-' . $attributes['blockID'] . ' .gutentor-grid-item-wrap{
            '.gutentor_box_four_device_options_css('margin',$item_wrap_Margin,'tablet').'
            '.gutentor_box_four_device_options_css('padding',$item_wrap_Padding,'tablet').'
        }';
			$local_dynamic_css['desktop'] .= '#section-' . $attributes['blockID'] . ' .gutentor-grid-item-wrap{
          '.gutentor_box_four_device_options_css('margin',$item_wrap_Margin,'desktop').'
          '.gutentor_box_four_device_options_css('padding',$item_wrap_Padding,'desktop').'
        }';

			/*Block Title*/
			$title_color_enable = $attributes['blockComponentTitleColorEnable'] ? $attributes['blockComponentTitleColorEnable']  : false;
			$title_typography = $attributes['blockComponentTitleTypography'] ? $attributes['blockComponentTitleTypography']  : false;
			$title_Margin     = $attributes['blockComponentTitleMargin'] ? $attributes['blockComponentTitleMargin']  : false;
			$title_Padding    = $attributes['blockComponentTitlePadding'] ? $attributes['blockComponentTitlePadding']  : false;
			$local_dynamic_css['all'] .= '#section-' . $attributes['blockID'] .' .gutentor-section-title .gutentor-title{
                 '.gutentor_generate_css('color',($title_color_enable && isset($attributes['blockComponentTitleColor'])) ? $attributes['blockComponentTitleColor']['hex']:null).'
                 '.gutentor_typography_options_css($title_typography).'
                 '.gutentor_box_four_device_options_css('margin',$title_Margin).'
                 '.gutentor_box_four_device_options_css('padding',$title_Padding).'
        }';
			$local_dynamic_css['tablet'] .= '#section-' . $attributes['blockID'] . ' .gutentor-section-title .gutentor-title{
              ' . gutentor_typography_options_responsive_css($title_typography,'tablet') . '
                  '.gutentor_box_four_device_options_css('margin',$title_Margin,'tablet').'
                 '.gutentor_box_four_device_options_css('padding',$title_Padding,'tablet').'
        }';
			$local_dynamic_css['desktop'] .= '#section-' . $attributes['blockID'] . ' .gutentor-section-title .gutentor-title{
              ' . gutentor_typography_options_responsive_css($title_typography, 'desktop') . '
               ' . gutentor_box_four_device_options_css('margin', $title_Margin, 'desktop') . '
               ' . gutentor_box_four_device_options_css('padding', $title_Padding, 'desktop') . '
        }';

			/*Imp hook for adding dynamic css*/
			$local_dynamic_css = apply_filters('gutentor_dynamic_css',$local_dynamic_css,$attributes);
			$output = gutentor_get_dynamic_css($local_dynamic_css);
			return $output;
		}

		/**
		 * Inner_blocks
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 *
		 * @param array $blocks
		 * @return mixed
		 */
		public function inner_blocks( $blocks ){
			$get_style = '';

			foreach ( $blocks as $i => $block ) {

				/*google typography*/
				$this->google_block_typography_prep($block);

				if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
					$get_style .= $this->inner_blocks( $block['innerBlocks'] );
				}
				if ( $block['blockName'] === 'core/block' && ! empty( $block['attrs']['ref'] ) ) {
					$reusable_block = get_post( $block['attrs']['ref'] );

					if ( ! $reusable_block || 'wp_block' !== $reusable_block->post_type ) {
						return '';
					}

					if ( 'publish' !== $reusable_block->post_status || ! empty( $reusable_block->post_password ) ) {
						return '';
					}

					$blocks = parse_blocks( $reusable_block->post_content );
					$get_style .= $this->inner_blocks( $blocks );
				}

				if ( is_array( $block ) && isset( $block['innerHTML'] ) ){
					if( 'gutentor/blog-post' == $block['blockName']){
						$get_style .= $this->get_block_common_css($block['attrs']);
					}
					elseif('gutentor/google-map' == $block['blockName']){
						$get_style .= $this->get_block_common_css($block['attrs']);
					}
					else{
						preg_match("'<style>(.*?)</style>'si", $block['innerHTML'], $match );
						if( isset( $match[1])){
							$get_style .= $match[1];
						}
					}
				}
			}
			return $get_style;
		}

		/**
		 * Single Stylesheet
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 *
		 * @param object $this_post
		 * @return mixed
		 */
		public function single_stylesheet( $this_post ) {

			$get_style = '';
			if(isset($this_post->ID)) {
				if ( has_blocks( $this_post->ID ) ) {
					if ( isset( $this_post->post_content ) ) {

						$blocks = parse_blocks( $this_post->post_content );
						if ( ! is_array( $blocks ) || empty( $blocks ) ) {
							return false;
						}
						$get_style = $this->inner_blocks( $blocks );
					}
				}
			}
			return $get_style;
		}

		/**
		 * css_prefix
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 *
		 * @return mixed
		 */
		public function css_prefix() {
			if( is_singular()){
				global  $post;
				if( has_blocks( $post->ID ) ){
					return $post->ID;
				}
			}
			return false;
		}

		/**
		 * get_global_dynamic_css
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 *
		 * @return mixed
		 */
		public function get_global_dynamic_css() {
			$getCSS = '';
			if( gutentor_get_theme_support()){
				$include = array();
				if ( gutentor_get_options( 'gutentor_header_template' ) ) {
					$header_template = gutentor_get_options( 'gutentor_header_template' );
					$include[] = $header_template;

				}
				if ( gutentor_get_options( 'gutentor_footer_template' ) ) {
					$footer_template = gutentor_get_options( 'gutentor_footer_template' );
					$include[] = $footer_template;
				}
				if( !empty( $include ) ){
					$lastposts = get_posts( array(
						'include'   => $include,
						'post_type' => 'wp_block',
					) );

					if ( $lastposts ) {
						foreach ( $lastposts as $post ) :
							setup_postdata( $post );
							$getCSS .= $this->single_stylesheet( $post );
						endforeach;
						wp_reset_postdata();
					}
				}
			}
			$output = gutentor_dynamic_css()->minify_css( $getCSS );
			return $output;
		}

		/**
		 * Get dynamic CSS
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @param array $dynamic_css
		 * 	$dynamic_css = array(
			'all'=>'css',
			'768'=>'css',
			);
		 * @return mixed
		 */
		public function get_singular_dynamic_css(){

			$getCSS = '';
			if ( is_singular() ) {
				global $post;
				$getCSS = $this->single_stylesheet( $post );

			}
			elseif ( is_archive() || is_home() || is_search() ) {
				global $wp_query;
				foreach ( $wp_query as $post ) {
					$getCSS .= $this->single_stylesheet( $post );
				}
			}

			$output = gutentor_dynamic_css()->minify_css( $getCSS );
			return $output;
		}

		/**
		 * Callback function for wp_head
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public static function dynamic_css( ) {

			$globalCSS = gutentor_dynamic_css()->get_global_dynamic_css();
			$singularCSS = gutentor_dynamic_css()->get_singular_dynamic_css();
			$combineCSS = '';
			$cssPrefix = gutentor_dynamic_css()->css_prefix();


			if ( 'default' == apply_filters( 'gutentor_dynamic_style_location', 'head' ) ) {
				return;
			}

			if ( 'file' == apply_filters( 'gutentor_dynamic_style_location', 'head' ) ) {

				global $wp_customize;
				$upload_dir = wp_upload_dir();

				if ( isset( $wp_customize ) || ! file_exists( $upload_dir['basedir'] .'/gutentor/global.css' ) ) {
					$combineCSS .= $globalCSS;
				}
				if ( isset( $wp_customize ) || ! file_exists( $upload_dir['basedir'] .'/gutentor/p-'.$cssPrefix.'.css'  ) ) {
					$combineCSS .= $singularCSS;
				}

				// Render CSS in the head
				if ( ! empty( $combineCSS ) ) {
					echo "<!-- Gutentor Dynamic CSS -->\n<style type=\"text/css\" id='gutentor-dynamic-css'>\n" . wp_strip_all_tags( wp_kses_post( $combineCSS ) ) . "\n</style>";
				}

			}
			else {
				$combineCSS = $globalCSS.$singularCSS;
				// Render CSS in the head
				if ( ! empty( $combineCSS ) ) {
					echo "<!-- Gutentor Dynamic CSS -->\n<style type=\"text/css\" id='gutentor-dynamic-css'>\n" . wp_strip_all_tags( wp_kses_post( $combineCSS ) ) . "\n</style>";
				}

			}

		}

		/**
		 * Callback function for admin_bar_init
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public static function add_edit_dynamic_css_file( ) {

			// If Custom File is not selected
			if ( 'file' != apply_filters( 'gutentor_dynamic_style_location', 'head' ) ){
				return false;
			}

			$globalCSS = gutentor_dynamic_css()->get_global_dynamic_css();
			$singularCSS = gutentor_dynamic_css()->get_singular_dynamic_css();

			$cssPrefix = gutentor_dynamic_css()->css_prefix();

			// We will probably need to load this file
			require_once( ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'file.php' );

			global $wp_filesystem;
			$upload_dir = wp_upload_dir();
			$dir = trailingslashit( $upload_dir['basedir'] ) . 'gutentor'. DIRECTORY_SEPARATOR;

			WP_Filesystem();
			$wp_filesystem->mkdir( $dir );
			$wp_filesystem->put_contents( $dir . 'global.css', $globalCSS, 0644 );
			if($cssPrefix ){
				$wp_filesystem->put_contents( $dir . 'p-'.$cssPrefix.'.css', $singularCSS, 0644 );
			}

		}

		/**
		 * Callback function for wp_enqueue_scripts
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @return void
		 */
		public static function dynamic_css_enqueue() {

			// If File is not selected
			if ( 'file' != apply_filters( 'gutentor_dynamic_style_location', 'head' ) ){
				return false;
			}

			global $wp_customize;
			$upload_dir = wp_upload_dir();

			$globalCSS = gutentor_dynamic_css()->get_global_dynamic_css();
			$singularCSS = gutentor_dynamic_css()->get_singular_dynamic_css();

			$cssPrefix = gutentor_dynamic_css()->css_prefix();

			// Render CSS from the custom file
			if ( ! isset( $wp_customize ) ) {

				if ( !empty( $globalCSS ) && file_exists( $upload_dir['basedir'] .'/gutentor/global.css' ) ) {
					wp_enqueue_style( 'gutentor-dynamic-common', trailingslashit( $upload_dir['baseurl'] ) . 'gutentor/global.css', false, null );
				}
				if ( !empty( $singularCSS ) && file_exists( $upload_dir['basedir'] .'/gutentor/p-'.$cssPrefix.'.css'  ) ) {
					wp_enqueue_style( 'gutentor-dynamic', trailingslashit( $upload_dir['baseurl'] ) . 'gutentor/p-'.$cssPrefix.'.css', false, null );
				}
			}
		}

	}
endif;

/**
 * Call Gutentor_Dynamic_CSS
 *
 * @since    1.0.0
 * @access   public
 *
 */
if( !function_exists( 'gutentor_dynamic_css')){

	function gutentor_dynamic_css() {

		return Gutentor_Dynamic_CSS::instance();
	}
	gutentor_dynamic_css()->run();
}