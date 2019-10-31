<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Block_Base' ) ) {

	/**
	 * Base Class For Gutentor for common functions
	 * @package Gutentor
	 * @since 1.0.1
	 *
	 */
	class Gutentor_Block_Base{

		/**
		 * Prevent some functions to called many times
		 * @access private
		 * @since 1.0.1
		 * @var integer
		 */
		private static $counter = 0;

	    /**
		 * Run Block
		 *
		 * @access public
		 * @since 1.0.1
		 * @return void
		 */
		public function run(){

			if( method_exists( $this, 'load_dependencies' ) ){
				$this->load_dependencies();
			}
			add_action( 'init', array( $this, 'register_and_render' ) );

			if( method_exists( $this, 'add_dynamic_css' ) ){
				add_filter( 'gutentor_dynamic_css', array( $this, 'add_dynamic_css' ), 10, 2 );
			}

			if( self::$counter === 0 ){

				add_filter( 'gutentor_common_attr_default_val', array( $this, 'add_repeater_common_default_val' ));
				self::$counter++;
			}
		}

		/**
		 * Register this Block
		 * Callback will aut called by this function register_block_type
		 *
		 * @access public
		 * @since 1.0.1
		 * @return void
		 */
		public function register_and_render(){

			$args = array();

			if( method_exists( $this, 'render_callback' ) ){
				$args = array(
					'render_callback' => array( $this, 'render_callback' ),
				);
				if( method_exists( $this, 'get_attrs' ) ){
					$attributes = array_merge_recursive( $this->get_attrs(), $this->get_common_attrs() );
				}
				else{
					$attributes = $this->get_common_attrs();
				}
				$args['attributes'] = $attributes;
			}
			if(function_exists('register_block_type')){
				register_block_type( 'gutentor/'.$this->block_name, $args );
			}

		}

		/**
		 * Common Attributes
		 * It includes Advanced Attributes
		 *
		 * @since      1.0.0
		 * @package    Gutentor
		 * @author     Gutentor <info@gutentor.com>
		 */
		public function get_common_attrs(){
			$common_attrs = array(

				/*column*/
				'blockItemsColumn' => array(
					'type'    => 'object',
					'default' => array(
						'desktop' => 'grid-md-4',
						'tablet'     => 'grid-sm-4',
						'mobile'     =>  'grid-xs-12',
					)
				),
				'blockSectionHtmlTag' => array(
					'type'    => 'string',
					'default' =>'section'
				),

				/*Advanced Attr*/
				'blockComponentAnimation' => array(
					'type' => 'object',
					'default' => array(
						'Animation' => 'none',
						'Delay'     => '',
						'Speed'     => '',
						'Iteration' => '',
					)
				),
				'blockComponentBGType' => array(
					'type' => 'string',
				),
				'blockComponentBGImage' => array(
					'type' => 'string',
				),
				'blockComponentBGVideo' => array(
					'type' => 'object',
				),
				'blockComponentBGColor' => array(
					'type' => 'object',
				),
				'blockComponentBGImageSize' => array(
					'type' => 'string',
				),
				'blockComponentBGImagePosition' => array(
					'type' => 'string',
				),
				'blockComponentBGImageRepeat' => array(
					'type' => 'string',
				),
				'blockComponentBGImageAttachment' => array(
					'type' => 'string',
				),
				'blockComponentBGVideoLoop' => array(
					'type' => 'object',
					'default'=> true
				),
				'blockComponentBGVideoMuted' => array(
					'type' => 'boolean',
					'default'=> true
				),
				'blockComponentEnableOverlay' => array(
					'type' => 'boolean',
					'default'=> true
				),
				'blockComponentOverlayColor' => array(
					'type' => 'string',
					'default'=> ''
				),
				'blockComponentBoxBorder' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentMargin' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentPadding' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentBoxShadowOptions' => array(
					'type' => 'object',
					'default' => array(
						'boxShadowColor'    => '',
						'boxShadowX'        => '',
						'boxShadowY'        => '',
						'boxShadowBlur'     => '',
						'boxShadowSpread'   => '',
						'boxShadowPosition' => '',
					)
				),

				/*adv shape*/
				'blockShapeTopSelect' => array(
					'type' => 'string',
					'default' => '',
				),
				'blockShapeTopSelectEnableColor' => array(
					'type' => 'boolean',
					'default' => '',
				),
				'blockShapeTopSelectColor' => array(
					'type' => 'object',
					'default' => '',
				),
				'blockShapeTopHeight' => array(
					'type' => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					)
				),
				'blockShapeTopWidth' => array(
					'type' => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					)
				),
				'blockShapeTopPosition' => array(
					'type' => 'boolean',
					'default' => '',
				),
				'blockShapeBottomSelect' => array(
					'type' => 'string',
					'default' => '',
				),
				'blockShapeBottomSelectEnableColor' => array(
					'type' => 'boolean',
					'default' => '',
				),
				'blockShapeBottomSelectColor' => array(
					'type' => 'object',
					'default' => '',
				),
				'blockShapeBottomHeight' => array(
					'type' => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					)
				),
				'blockShapeBottomWidth' => array(
					'type' => 'object',
					'default' => array(
						'type'    => 'px',
						'desktop' => '',
						'tablet'  => '',
						'mobile'  => '',
					)
				),
				'blockShapeBottomPosition' => array(
					'type' => 'boolean',
					'default' => '',
				),

				/* block title*/
				'blockComponentTitleEnable'			=> array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockComponentTitle'           => array(
					'type' => 'string',
					'default' => __('Block Title','gutentor'),
				),
				'blockComponentTitleTag'           => array(
					'type' => 'string',
					'default' => 'h2'
				),
				'blockComponentTitleAlign'           => array(
					'type' => 'string',
					'default' => 'text-center'
				),
				'blockComponentTitleColorEnable'           => array(
					'type' => 'boolean',
					'default' => true
				),
				'blockComponentTitleColor'           => array(
					'type' => 'object',
					'default'=> array(
						'hex' => '#111111',
					)
				),
				'blockComponentTitleTypography'           => array(
					'type' => 'object',
					'default' => array(
						'fontType'       => 'default',
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
					)
				),
				'blockComponentTitleMargin' => array(
					'type' => 'object',
					'default' => array(
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

					)
				),
				'blockComponentTitlePadding' => array(
					'type' => 'object',
					'default' => array(
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

					)
				),
				'blockComponentTitleAnimation' => array(
					'type' => 'object',
					'default' => array(
						'Animation' => 'none',
						'Delay'     => '',
						'Speed'     => '',
						'Iteration' => '',
					)
				),
				'blockComponentTitleDesignEnable' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockComponentTitleSeperatorPosition'  => array(
					'type' => 'string',
					'default' => 'seperator-bottom',
				),

				/* block sub title*/
				'blockComponentSubTitleEnable' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockComponentSubTitle'  => array(
					'type' => 'string',
					'default' => __('Block Title','gutentor'),
				),
				'blockComponentSubTitleTag'           => array(
					'type' => 'string',
					'default' => 'p'
				),
				'blockComponentSubTitleAlign'           => array(
					'type' => 'string',
					'default' => 'text-center'
				),
				'blockComponentSubTitleColorEnable'           => array(
					'type' => 'boolean',
					'default' => true
				),
				'blockComponentSubTitleColor'           => array(
					'type' => 'object',
					'default'=> array(
						'hex' => '#111111',
					)
				),
				'blockComponentSubTitleTypography' => array(
					'type' => 'object',
					'default' => array(
						'fontType'       => 'default',
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

					)
				),
				'blockComponentSubTitleMargin' => array(
					'type' => 'object',
					'default' => array(
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

					)
				),
				'blockComponentSubTitlePadding' => array(
					'type' => 'object',
					'default' => array(
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

					)
				),
				'blockComponentSubTitleAnimation' => array(
					'type' => 'object',
					'default' => array(
						'Animation'       => 'px',
						'Delay'     => '',
						'Speed'     => '',
						'Iteration'     => '',
					)
				),
				'blockComponentSubTitleDesignEnable' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockComponentSubTitleSeperatorPosition'  => array(
					'type' => 'string',
					'default' => 'seperator-bottom',
				),

				/* primary button */
				'blockComponentPrimaryButtonEnable'    => array(
					'type' => 'boolean',
					'default' => false,
				),
				'blockComponentPrimaryButtonLinkOptions' => array(
					'type' => 'object',
					'default' => array(
						'openInNewTab' => false,
						'rel' => '',
					),
				),
				'blockComponentPrimaryButtonColor'    => array(
					'type' => 'object',
					'default' => array(
						'enable' => true,
						'normal' => array(
							'hex' => '#275cf6',
							'rgb' => array(
								'r' => '39',
								'g' => '92',
								'b' => '246',
								'a' => '1',
							)
						),
						'hover' => array(
							'hex' => '#1949d4',
							'rgb' => array(
								'r' => '25',
								'g' => '73',
								'b' => '212',
								'a' => '1',
							)
						),
					)
				),
				'blockComponentPrimaryButtonTextColor'    => array(
					'type' => 'object',
					'default' => array(
						'enable' => true,
						'normal' => array(
							'hex' => '#fff',
						),
						'hover'=>'',
					)
				),
				'blockComponentPrimaryButtonMargin'    => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentPrimaryButtonPadding'    => array(
					'type' => 'object',
					'default' => array(
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
					),
				),
				'blockComponentPrimaryButtonIconOptions' => array(
					'type' => 'object',
					'default' => array(
						'position' => 'hide',
						'size' => '',
					)
				),
				'blockComponentPrimaryButtonIconMargin' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentPrimaryButtonIconPadding' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentPrimaryButtonBorder' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentPrimaryButtonBoxShadow'    => array(
					'type' => 'object',
					'default' => array(
						'boxShadowColor' => '',
						'boxShadowX' => '',
						'boxShadowY' => '',
						'boxShadowBlur' => '',
						'boxShadowSpread' => '',
						'boxShadowPosition' => '',
					)
				),
				'blockComponentPrimaryButtonTypography'    => array(
					'type' => 'object',
					'default' => array(
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

					)
				),
				'blockComponentPrimaryButtonIcon' => array(
					'type' => 'object',
					'default' => array(
						'label'             => 'fa-book',
						'value'             => 'fas fa-book',
						'code'             => 'f108',
					)
				),
				'blockComponentPrimaryButtonText' => array(
					'type' => 'string',
					'default'=>   __('View More')
				),
				'blockComponentPrimaryButtonLink' => array(
					'type' => 'string',
					'default'=>   __('#')
				),

				//Secondary Button
				'blockComponentSecondaryButtonLinkOptions' => array(
					'type' => 'object',
					'default' => array(
						'openInNewTab' => false,
						'rel' => '',
					),
				),
				'blockComponentSecondaryButtonColor'    => array(
					'type' => 'object',
					'default' => array(
						'enable' => true,
						'normal' => array(
							'hex' => '#275cf6',
							'rgb' => array(
								'r' => '39',
								'g' => '92',
								'b' => '246',
								'a' => '1',
							)
						),
						'hover' => array(
							'hex' => '#1949d4',
							'rgb' => array(
								'r' => '25',
								'g' => '73',
								'b' => '212',
								'a' => '1',
							)
						),
					)
				),
				'blockComponentSecondaryButtonTextColor'    => array(
					'type' => 'object',
					'default' => array(
						'enable' => true,
						'normal' => array(
							'hex' => '#fff',
						),
						'hover'=>'',
					)
				),
				'blockComponentSecondaryButtonMargin'    => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentSecondaryButtonPadding'    => array(
					'type' => 'object',
					'default' => array(
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
					),
				),
				'blockComponentSecondaryButtonIconOptions' => array(
					'type' => 'object',
					'default' => array(
						'position' => 'hide',
						'size' => '',
					)
				),
				'blockComponentSecondaryButtonIconMargin' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentSecondaryButtonIconPadding' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentSecondaryButtonBorder' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockComponentSecondaryButtonBoxShadow'    => array(
					'type' => 'object',
					'default' => array(
						'boxShadowColor' => '',
						'boxShadowX' => '',
						'boxShadowY' => '',
						'boxShadowBlur' => '',
						'boxShadowSpread' => '',
						'boxShadowPosition' => '',
					)
				),
				'blockComponentSecondaryButtonTypography'    => array(
					'type' => 'object',
					'default' => array(
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

					)
				),
				'blockComponentSecondaryButtonIcon' => array(
					'type' => 'object',
					'default' => array(
						'label'             => 'fa-book',
						'value'             => 'fas fa-book',
						'code'             => 'f108',
					)
				),
				'blockComponentSecondaryButtonText' => array(
					'type' => 'string',
					'default'=>   __('View More')
				),
				'blockComponentSecondaryButtonLink' => array(
					'type' => 'string',
					'default'=>   __('#')
				),


				/*carousel attr*/
				'blockItemsCarouselEnable' => array(
					'type' => 'boolean',
					'default'=> false
				),
				'blockItemsCarouselDots' => array(
					'type' => 'boolean',
					'default'=> false
				),
				'blockItemsCarouselDotsTablet' => array(
					'type' => 'boolean',
					'default'=> false
				),
				'blockItemsCarouselDotsMobile' => array(
					'type' => 'boolean',
					'default'=> false
				),
				'blockItemsCarouselDotsColor' => array(
					'type'   => 'object',
					'default'=> array(
						'enable' => false,
						'normal' => '',
						'hover'  => ''
					),
				),
				'blockItemsCarouselDotsButtonBorder' => array(
					'type'   => 'object',
					'default'=> array(
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
					),
				),
				'blockItemsCarouselDotsButtonHeight' => array(
					'type' => 'object',
				),
				'blockItemsCarouselDotsButtonWidth' => array(
					'type' => 'object',
				),
				'blockItemsCarouselArrows' => array(
					'type' => 'boolean',
					'default'=> true
				),
				'blockItemsCarouselArrowsTablet' => array(
					'type' => 'boolean',
					'default'=> true
				),
				'blockItemsCarouselArrowsMobile' => array(
					'type' => 'boolean',
					'default'=> true
				),
				'blockItemsCarouselArrowsBgColor' => array(
					'type'   => 'object',
					'default'=> array(
						'enable' => false,
						'normal' => '',
						'hover'  => ''
					),
				),
				'blockItemsCarouselArrowsTextColor' => array(
					'type'   => 'object',
					'default'=> array(
						'enable' => false,
						'normal' => '',
						'hover'  => ''
					),
				),
				'blockItemsCarouselInfinite' => array(
					'type' => 'boolean',
					'default'=> false
				),
				'blockItemsCarouselFade' => array(
					'type' => 'boolean',
					'default'=> false
				),
				'blockItemsCarouselAutoPlay' => array(
					'type' => 'boolean',
					'default'=> false
				),
				'blockItemsCarouselSlideSpeed' => array(
					'type' => 'number',
					'default'=> 300
				),
				'blockItemsCarouselCenterMode' => array(
					'type' => 'boolean',
					'default'=> false
				),
				'blockItemsCarouselCenterPadding' => array(
					'type' => 'number',
					'default'=> 60
				),
				'blockItemsCarouselAutoPlaySpeed' => array(
					'type' => 'number',
					'default'=> 1200
				),
				'blockItemsCarouselResponsiveSlideItem' => array(
					'type'   => 'object',
					'default'=> array(
						'desktop' => '4',
						'tablet'=> '3',
						'mobile' => '2',
					),
				),
				'blockItemsCarouselResponsiveSlideScroll' => array(
					'type'   => 'object',
					'default'=> array(
						'desktop' => '4',
						'tablet'  => '3',
						'mobile'  => '2',
					),
				),

				'blockItemsCarouselNextArrow' => array(
					'type'   => 'object',
					'default'=> array(
						'label' => 'fa-angle-right',
						'value'=> 'fas fa-angle-right',
						'code' => 'f105',
					),
				),
				'blockItemsCarouselPrevArrow' => array(
					'type'   => 'object',
					'default'=> array(
						'label' => 'fa-angle-left',
						'value'=> 'fas fa-angle-left',
						'code' => 'f104',
					),
				),
				'blockItemsCarouselButtonIconSize' => array(
					'type' => 'number',
					'default'=> 16
				),
				'blockItemsCarouselArrowButtonHeight' => array(
					'type'   => 'object',
					'default'=> array(
						'desktop' => '40',
						'tablet'  => '30',
						'mobile'  => '20',
					),
				),
				'blockItemsCarouselArrowButtonWidth' => array(
					'type'   => 'object',
					'default'=> array(
						'desktop' => '40',
						'tablet'  => '30',
						'mobile'  => '20',
					),
				),
				'blockItemsCarouselArrowButtonBorder' => array(
					'type'   => 'object',
					'default'=> array(
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
					),
				),

				/*Image Options attr*/
				'blockImageBoxImageOverlayColor' => array(
					'type'    => 'object',
					'default' => array(
						'enable' => false,
						'normal' => '',
						'hover'  => '',
					),
				),
				'blockFullImageEnable' => array(
					'type'    => 'boolean',
					'default'=>false
				),
				'blockEnableImageBoxWidth' => array(
					'type'    => 'boolean',
					'default'=>false
				),
				'blockImageBoxWidth' => array(
					'type'    => 'number',
					'default'=>''
				),
				'blockEnableImageBoxHeight' => array(
					'type'    => 'boolean',
					'default'=>false
				),
				'blockImageBoxHeight' => array(
					'type'    => 'number',
					'default'=>''
				),
				'blockEnableImageBoxDisplayOptions' => array(
					'type'    => 'boolean',
					'default' => false
				),
				'blockImageBoxDisplayOptions' => array(
					'type'    => 'string',
					'default' =>'normal-image'
				),
				'blockImageBoxBackgroundImageOptions' => array(
					'type'    => 'object',
					'default' => array(

						'backgroundImage' => '',
						'desktopHeight' => '',
						'tabletHeight'  => '',
						'mobileHeight'  => '',

						'backgroundSize'       => '',
						'backgroundPosition'   => '',
						'backgroundRepeat'     => '',
						'backgroundAttachment' => '',
					),
				),
				'blockEnableImageBoxBorder' => array(
					'type'    => 'boolean',
					'default' => false
				),
				'blockImageBoxBorder' => array(
					'type'    => 'object',
					'default' => array(
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
					),
				),

				/*item Wrap*/
				'blockItemsWrapMargin' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockItemsWrapPadding' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockItemsWrapAnimation' => array(
					'type' => 'object',
					'default' => array(
						'Animation' => 'none',
						'Delay'     => '',
						'Speed'     => '',
						'Iteration' => '',
					)
				),
			);

			return apply_filters('gutentor_get_common_attrs', $common_attrs );
		}
		/**
		 * Repeater Attributes
		 *
		 * @access public
		 * @since 1.0.1
		 * @return array
		 */
		public function get_common_repeater_attrs(){

			return array(

				/*single item title*/
				'blockSingleItemTitleEnable' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockSingleItemTitleTag'           => array(
					'type' => 'string',
					'default' => 'h3'
				),
				'blockSingleItemTitleColor'           => array(
					'type' => 'object',
					'default'=> array(
						'enable' => 'false',
						'hex' => '#111111',
					)
				),
				'blockSingleItemTitleTypography'           => array(
					'type' => 'object',
					'default' => array(
						'fontType'   => 'default',
						'systemFont' => '',
						'googleFont' => '',
						'customFont' => '',

						'desktopFontSize' => '',
						'tabletFontSize'  => '',
						'mobileFontSize'  => '',

						'fontWeight'     => '',
						'textTransform'  => '',
						'fontStyle'      => '',
						'textDecoration' => '',

						'desktopLineHeight' => '',
						'tabletLineHeight'  => '',
						'mobileLineHeight'  => '',

						'desktopLetterSpacing' => '',
						'tabletLetterSpacing'  => '',
						'mobileLetterSpacing'  => '',
					)
				),
				'blockSingleItemTitleMargin' => array(
					'type' => 'object',
					'default' => array(
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

					)
				),
				'blockSingleItemTitlePadding' => array(
					'type' => 'object',
					'default' => array(
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

					)
				),

				/* single item description*/
				'blockSingleItemDescriptionEnable' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'blockSingleItemDescriptionTag'           => array(
					'type' => 'string',
					'default' => 'p'
				),
				'blockSingleItemDescriptionColor'           => array(
					'type' => 'object',
					'default'=> array(
						'enable' => 'false',
						'hex' => '#111111',
					)
				),
				'blockSingleItemDescriptionTypography'           => array(
					'type' => 'object',
					'default' => array(
						'fontType'   => 'default',
						'systemFont' => '',
						'googleFont' => '',
						'customFont' => '',

						'desktopFontSize' => '',
						'tabletFontSize'  => '',
						'mobileFontSize'  => '',

						'fontWeight'     => '',
						'textTransform'  => '',
						'fontStyle'      => '',
						'textDecoration' => '',

						'desktopLineHeight' => '',
						'tabletLineHeight'  => '',
						'mobileLineHeight'  => '',

						'desktopLetterSpacing' => '',
						'tabletLetterSpacing'  => '',
						'mobileLetterSpacing'  => '',
					)
				),
				'blockSingleItemDescriptionMargin' => array(
					'type' => 'object',
					'default' => array(
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

					)
				),
				'blockSingleItemDescriptionPadding' => array(
					'type' => 'object',
					'default' => array(
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

					)
				),

				/*single item button*/
				'blockSingleItemButtonEnable' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'blockSingleItemButtonLinkOptions' => array(
					'type' => 'object',
					'default' => array(
						'openInNewTab' => false,
						'rel'          => '',
					),
				),
				'blockSingleItemButtonColor'    => array(
					'type' => 'object',
					'default' => array(
						'enable' => true,
						'normal' => array(
							'hex' => '#275cf6',
							'rgb' => array(
								'r' => '39',
								'g' => '92',
								'b' => '246',
								'a' => '1',
							)
						),
						'hover' => array(
							'hex' => '#1949d4',
							'rgb' => array(
								'r' => '25',
								'g' => '73',
								'b' => '212',
								'a' => '1',
							)
						),
					)
				),
				'blockSingleItemButtonTextColor'    => array(
					'type' => 'object',
					'default' => array(
						'enable' => true,
						'normal' => array(
							'hex' => '#fff',
						),
						'hover'=>'',
					)
				),
				'blockSingleItemButtonMargin'    => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockSingleItemButtonPadding'    => array(
					'type' => 'object',
					'default' => array(
						'type'          => 'px',
						'desktopTop'    => '10',
						'desktopRight'  => '15',
						'desktopBottom' => '10',
						'desktopLeft'   => '15',
						'tabletTop'     => '10',
						'tabletRight'   => '15',
						'tabletBottom'  => '10',
						'tabletLeft'    => '15',
						'mobileTop'     => '10',
						'mobileRight'   => '15',
						'mobileBottom'  => '10',
						'mobileLeft'    => '15',
					),
				),
				'blockSingleItemButtonIconOptions' => array(
					'type' => 'object',
					'default' => array(
						'position' => 'hide',
						'size'     => '',
					)
				),
				'blockSingleItemButtonIconMargin' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockSingleItemButtonIconPadding' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockSingleItemButtonBorder' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockSingleItemButtonBoxShadow'    => array(
					'type' => 'object',
					'default' => array(
						'boxShadowColor'    => '',
						'boxShadowX'        => '',
						'boxShadowY'        => '',
						'boxShadowBlur'     => '',
						'boxShadowSpread'   => '',
						'boxShadowPosition' => '',
					)
				),
				'blockSingleItemButtonTypography'    => array(
					'type' => 'object',
					'default' => array(
						'fontType'             => 'system',
						'systemFont'           => '',
						'googleFont'           => '',
						'customFont'           => '',

						'desktopFontSize'      => '14',
						'tabletFontSize'       => '14',
						'mobileFontSize'       => '14',

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
					)
				),

				/* single item box title*/
				'blockSingleItemBoxColor' => array(
					'type' => 'object',
					'default' => array(
						'enable'       => true,
						'normal'     => '',
						'hover'     => '',
					)
				),
				'blockSingleItemBoxBorder' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
				'blockSingleItemBoxShadowOptions'    => array(
					'type' => 'object',
					'default' => array(
						'boxShadowColor'    => '',
						'boxShadowX'        => '',
						'boxShadowY'        => '',
						'boxShadowBlur'     => '',
						'boxShadowSpread'   => '',
						'boxShadowPosition' => '',
					)
				),
				'blockSingleItemBoxMargin' => array(
					'type' => 'object',
					'default' => array(
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

					)
				),
				'blockSingleItemBoxPadding' => array(
					'type' => 'object',
					'default' => array(
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
					)
				),
			);
		}

		/**
		 * Repeater Default Values
		 *
		 * @access public
		 * @since 1.0.1
		 * @return array
		 */
		public function repeater_common_default_val(){

			$gutentor_repeater_attr_default_val = [

				/*single item title*/
				'blockSingleItemTitleEnable'           => true,
				'blockSingleItemTitleTag'              => 'h3',
				'blockSingleItemTitleColor'            => [
					'enable' => false,
					'normal' => [
						'hex' => '#111111',
					],
					'hover' => '',
				],
				'blockSingleItemTitleTypography'       => [

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
				'blockSingleItemTitleMargin'           => [

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
				'blockSingleItemTitlePadding'          => [

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

				/*single item description*/
				'blockSingleItemDescriptionEnable'     => true,
				'blockSingleItemDescriptionTag'        => 'p',
				'blockSingleItemDescriptionColor'      => [
					'enable' => false,
					'normal' => '',
					'hover' => '',
				],
				'blockSingleItemDescriptionTypography' => [

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
				'blockSingleItemDescriptionMargin'     => [

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
				'blockSingleItemDescriptionPadding'    => [

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

				/*single item button*/
				'blockSingleItemButtonEnable'      => false,
				'blockSingleItemButtonLinkOptions' => [
					'openInNewTab' => false,
					'rel'          => '',
				],
				'blockSingleItemButtonColor'       => [
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
					'hover'  => [
						'hex' => '#1949d4',
						'rgb' => [
							'r' => '25',
							'g' => '73',
							'b' => '212',
							'a' => '1',
						]
					],
				],
				'blockSingleItemButtonTextColor'   => [
					'enable' => true,
					'normal' => [
						'hex' => '#fff',
					],
					'hover'  => '',
				],
				'blockSingleItemButtonMargin'      => [
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
				'blockSingleItemButtonPadding'     => [
					'type'          => 'px',
					'desktopTop'    => '10',
					'desktopRight'  => '15',
					'desktopBottom' => '10',
					'desktopLeft'   => '15',
					'tabletTop'     => '10',
					'tabletRight'   => '15',
					'tabletBottom'  => '10',
					'tabletLeft'    => '15',
					'mobileTop'     => '10',
					'mobileRight'   => '15',
					'mobileBottom'  => '10',
					'mobileLeft'    => '15',
				],
				'blockSingleItemButtonIconOptions' => [

					'position' => 'hide',
					'size'     => '',
				],
				'blockSingleItemButtonIconMargin'  => [
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
				'blockSingleItemButtonIconPadding' => [
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
				'blockSingleItemButtonBorder'      => [
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
				'blockSingleItemButtonBoxShadow'   => [
					'boxShadowColor'    => '',
					'boxShadowX'        => '',
					'boxShadowY'        => '',
					'boxShadowBlur'     => '',
					'boxShadowSpread'   => '',
					'boxShadowPosition' => '',
				],
				'blockSingleItemButtonTypography'  => [
					'fontType'   => 'system',
					'systemFont' => '',
					'googleFont' => '',
					'customFont' => '',

					'desktopFontSize' => '14',
					'tabletFontSize'  => '14',
					'mobileFontSize'  => '14',

					'fontWeight'     => '',
					'textTransform'  => 'normal',
					'fontStyle'      => '',
					'textDecoration' => '',

					'desktopLineHeight' => '',
					'tabletLineHeight'  => '',
					'mobileLineHeight'  => '',

					'desktopLetterSpacing' => '',
					'tabletLetterSpacing'  => '',
					'mobileLetterSpacing'  => ''
				],

				/*single item box*/
				'blockSingleItemBoxColor'         => [
					'enable' => true,
					'normal' => '',
					'hover'  => '',
				],
				'blockSingleItemBoxBorder'        => [
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
				'blockSingleItemBoxShadowOptions' => [
					'boxShadowColor'    => '',
					'boxShadowX'        => '',
					'boxShadowY'        => '',
					'boxShadowBlur'     => '',
					'boxShadowSpread'   => '',
					'boxShadowPosition' => '',
				],
				'blockSingleItemBoxMargin'        => [
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
				'blockSingleItemBoxPadding'       => [
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
			];

			return $gutentor_repeater_attr_default_val;
		}
		/**
		 * Repeater Attributes
		 *
		 * @access public
		 * @since 1.0.1
		 * @return array
		 */

		public function add_repeater_common_default_val( $attr ){

			return array_merge_recursive($attr, $this->repeater_common_default_val() );
		}
	}
}