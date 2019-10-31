<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Gutentor_Advanced_Import_Server' ) ) {
	/**
	 * Advanced Import
	 * @package Gutentor
	 * @since 1.0.1
	 *
	 */
	class Gutentor_Advanced_Import_Server extends WP_Rest_Controller {

		/**
		 * Rest route namespace.
		 *
		 * @var Gutentor_Advanced_Import_Server
		 */
		public $namespace = 'gutentor-advanced-import/';

		/**
		 * Rest route version.
		 *
		 * @var Gutentor_Advanced_Import_Server
		 */
		public $version = 'v1';

		/**
		 * Initialize the class
		 */
		public function run() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		/**
		 * Register REST API route
		 */
		public function register_routes() {
			$namespace = $this->namespace . $this->version;

			register_rest_route(
				$namespace,
				'/fetch_templates',
				array(
					array(
						'methods'	=> \WP_REST_Server::READABLE,
						'callback'	=> array( $this, 'fetch_templates' ),
					),
				)
			);

			register_rest_route(
				$namespace,
				'/import_template',
				array(
					array(
						'methods'	=> \WP_REST_Server::READABLE,
						'callback'	=> array( $this, 'import_template' ),
						'args'		=> array(
							'url'	=> array(
								'type'        => 'string',
								'required'    => true,
								'description' => __( 'URL of the JSON file.', 'gutentor' ),
							),
						),
					),
				)
			);
		}

		/**
		 * Function to fetch templates.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function fetch_templates( \WP_REST_Request $request ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}

			$templates_list = array(

				array(
					'title'				=> __( 'About Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'about-block', 'about 1' ),
					'categories'		=> array( 'about' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/about-block/about-1/gutentor_about-block.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/about-block/about-1/about-block.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/about/#section-cb91908c-d5ea-4bc4-bbfd-1bc2525a40ea',
				),
				array(
					'title'				=> __( 'About Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'about-block', 'about 2' ),
					'categories'		=> array( 'about' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/about-block/about-2/gutentor_about-block.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/about-block/about-2/about-block.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/about/#section-b580929d-6990-4f60-b3fc-181705028917',
				),
				array(
					'title'				=> __( 'Accordion', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'accordion', 'accordion 1' ),
					'categories'		=> array( 'accordion' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/accordion/accordion-1/gutentor_accordion.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/accordion/accordion-1/accordion.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/accordion/#section-ae935ac7-a085-47a2-8676-eaf956284a03',
				),
				array(
					'title'				=> __( 'Accordion', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'accordion', 'accordion 2' ),
					'categories'		=> array( 'accordion' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/accordion/accordion-2/gutentor_accordion.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/accordion/accordion-2/accordion.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/accordion/#section-4f3f2a3d-739c-4a1f-bd2b-79b577d5af2c',
				),
				array(
					'title'				=> __( 'Author Profile', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'author-profile', 'author-profile 1' ),
					'categories'		=> array( 'author-profile' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/author-profile/author-profile-1/gutentor_author-profile.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/author-profile/author-profile-1/author-profile.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/author-profile/#section-b37afe24-2d7b-420b-9e94-efb3c3440a3c',
				),
				array(
					'title'				=> __( 'Call To Action', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'call-to-action', 'call-to-action 1' ),
					'categories'		=> array( 'call-to-action' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/call-to-action/call-to-action-1/gutentor_call-to-action.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/call-to-action/call-to-action-1/call-to-action.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/call-to-action/#section-35e69fbe-80f4-43df-bf4e-494a345f66c0',
				),
				array(
					'title'				=> __( 'Call To Action', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'call-to-action', 'call-to-action 2' ),
					'categories'		=> array( 'call-to-action' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/call-to-action/call-to-action-2/gutentor_call-to-action.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/call-to-action/call-to-action-2/call-to-action.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/call-to-action/#section-84e22d48-168a-4acb-90ab-7c1567cd25eb',
				),
				array(
					'title'				=> __( 'Count Down', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'count-down', 'count-down 1' ),
					'categories'		=> array( 'count-down' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/count-down/count-down-1/gutentor_count-down.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/count-down/count-down-1/count-down.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/count-down/#section-621045f5-3c1d-446b-8072-6adb8eb55b5a',
				),
				array(
					'title'				=> __( 'Count Down', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'count-down', 'count-down 2' ),
					'categories'		=> array( 'count-down' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/count-down/count-down-2/gutentor_count-down.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/count-down/count-down-2/count-down.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/count-down/#section-a9be75b8-de57-47d7-990e-9133a84c25ac',
				),
				array(
					'title'				=> __( 'Counter', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'counter', 'counter 1' ),
					'categories'		=> array( 'counter' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/counter/counter-1/gutentor_counter-box.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/counter/counter-1/counter.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/counter/#section-814c5e88-7dd3-4cef-a872-bff5103649da',
				),
				array(
					'title'				=> __( 'Counter', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'counter', 'counter 2' ),
					'categories'		=> array( 'counter' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/counter/counter-2/gutentor_counter-box.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/counter/counter-2/counter.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/counter/#section-dda6f0d9-eb19-4172-abaf-1ef4a120daca',
				),
				array(
					'title'				=> __( 'Featured Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'featured-block', 'featured-block 1' ),
					'categories'		=> array( 'featured-block' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/featured-block/featured-block-1/gutentor_featured-block.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/featured-block/featured-block-1/featured-block.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/featured-block/#section-b526b783-9078-4675-b8d3-f6c4ac207098',
				),
				array(
					'title'				=> __( 'Featured Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'featured-block', 'featured-block 2' ),
					'categories'		=> array( 'featured-block' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/featured-block/featured-block-2/gutentor_featured-block.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/featured-block/featured-block-2/featured-block.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/featured-block/#section-5c3dc2b1-32ed-4696-b5fa-abff0dff6dc4',
				),
				array(
					'title'				=> __( 'Gallery', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'gallery', 'gallery 1' ),
					'categories'		=> array( 'gallery' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/gallery/gallery-1/gutentor_gallery.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/gallery/gallery-1/gallery.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/gallery/#section-5c3dc2b1-32ed-4696-b5fa-abff0dff6dc4',
				),
				array(
					'title'				=> __( 'Gallery', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'gallery', 'gallery 2' ),
					'categories'		=> array( 'gallery' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/gallery/gallery-2/gutentor_gallery.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/gallery/gallery-2/gallery.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/gallery/#section-55f5836a-bcb5-4347-8878-5be8bc0e0bda',
				),
				array(
					'title'				=> __( 'Icon Box', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'icon-box', 'icon-box 1' ),
					'categories'		=> array( 'icon-box' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/icon-box/icon-box-1/gutentor_icon-box.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/icon-box/icon-box-1/icon-box.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/icon-box/#section-f5a1ee87-e6f5-4571-98a8-f262c4ab78e0',
				),
				array(
					'title'				=> __( 'Icon Box', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'icon-box', 'icon-box 2' ),
					'categories'		=> array( 'icon-box' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/icon-box/icon-box-2/gutentor_icon-box.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/icon-box/icon-box-2/icon-box.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/icon-box/#section-95b7bb09-ef44-4a4d-8d47-7842235dfecd',
				),
				array(
					'title'				=> __( 'Icon Box', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'icon-box', 'icon-box 3' ),
					'categories'		=> array( 'icon-box' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/icon-box/icon-box-3/gutentor_icon-box.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/icon-box/icon-box-3/icon-box.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/icon-box/#section-a2fe9962-9fde-4feb-a6a7-2376bc28f3f6',
				),
				array(
					'title'				=> __( 'Image Box', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'image-box', 'image-box 1' ),
					'categories'		=> array( 'image-box' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-box/image-box-1/gutentor_image-box.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-box/image-box-1/image-box.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/image-block/#section-d7ae3fd8-b954-4468-ae14-e904aff1e84c',
				),
				array(
					'title'				=> __( 'Image Box', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'image-box', 'image-box 2' ),
					'categories'		=> array( 'image-box' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-box/image-box-2/gutentor_image-box.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-box/image-box-2/image-box.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/image-block/#section-9766124c-aac1-466c-b54d-5e9b69c70d7d',
				),
				array(
					'title'				=> __( 'Image Box', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'image-box', 'image-box 3' ),
					'categories'		=> array( 'image-box' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-box/image-box-3/gutentor_image-box.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-box/image-box-3/image-box.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/image-block/#section-34ccd79b-3557-4521-a4b9-0c595d430665',
				),
				array(
					'title'				=> __( 'Image Slider', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'image-slider', 'image-slider 1' ),
					'categories'		=> array( 'image-slider' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-slider/image-slider-1/gutentor_image-slider.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-slider/image-slider-1/image-slider.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/image-slider/#section-35f902ba-cd22-4920-bc39-d104e23f3b89',
				),
				array(
					'title'				=> __( 'Image Slider', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'image-slider', 'image-slider 2' ),
					'categories'		=> array( 'image-slider' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-slider/image-slider-2/gutentor_image-slider.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/image-slider/image-slider-2/image-slider.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/image-slider/#section-7a3a2a07-17c1-4686-ab25-2df2fb94d3f9',
				),
				array(
					'title'				=> __( 'Opening Hours', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'opening-hours', 'opening-hours 1' ),
					'categories'		=> array( 'opening-hours' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/opening-hours/opening-hours-1/gutentor_opening-hours.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/opening-hours/opening-hours-1/opening-hours.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/opening-hours/#section-d83e7661-98af-446f-a56a-9ee11702e3d2',
				),
				array(
					'title'				=> __( 'Opening Hours', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'opening-hours', 'opening-hours 2' ),
					'categories'		=> array( 'opening-hours' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/opening-hours/opening-hours-2/gutentor_opening-hours.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/opening-hours/opening-hours-2/opening-hours.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/opening-hours/#section-ae615b86-93be-48a4-855d-10b045883d3a',
				),
				array(
					'title'				=> __( 'Pricing', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'pricing', 'pricing 1' ),
					'categories'		=> array( 'pricing' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/pricing/pricing-1/gutentor_pricing.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/pricing/pricing-1/pricing.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/pricing/#section-fa15ba5e-ec27-4941-afde-5d5211eaf61c',
				),
				array(
					'title'				=> __( 'Pricing', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'pricing', 'pricing 2' ),
					'categories'		=> array( 'pricing' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/pricing/pricing-2/gutentor_pricing.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/pricing/pricing-2/pricing.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/pricing/#section-8e24246c-bfb9-4ab0-ab80-04af0cbcee17',
				),
				array(
					'title'				=> __( 'Progressbar', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'progress-bar', 'progress-bar 1' ),
					'categories'		=> array( 'progress-bar' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/progress-bar/progress-bar-1/gutentor_progress-bar.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/progress-bar/progress-bar-1/progress-bar.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/progress-bar/#section-d63c446f-de55-47aa-8a1b-cf0fa1fc108c',
				),
				array(
					'title'				=> __( 'Progressbar', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'progress-bar', 'progress-bar 2' ),
					'categories'		=> array( 'progress-bar' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/progress-bar/progress-bar-2/gutentor_progress-bar.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/progress-bar/progress-bar-2/progress-bar.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/progress-bar/#section-c1a80860-638e-4f44-9f84-1fb58534c066',
				),
				array(
					'title'				=> __( 'Restaurant Menu', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'restaurant-menu', 'restaurant-menu 1' ),
					'categories'		=> array( 'restaurant-menu' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/restaurant-menu/restaurant-menu-1/gutentor_restaurant-menu.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/restaurant-menu/restaurant-menu-1/restaurant-menu.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/restaurant-menu/#section-eb78e751-096e-4dfd-8f12-b4a2c3f000e9',
				),
				array(
					'title'				=> __( 'Restaurant Menu', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'restaurant-menu', 'restaurant-menu 2' ),
					'categories'		=> array( 'restaurant-menu' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/restaurant-menu/restaurant-menu-2/gutentor_restaurant-menu.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/restaurant-menu/restaurant-menu-2/restaurant-menu.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/restaurant-menu/#section-992c7ec6-25e3-4eb2-8369-a4238352803f',
				),
				array(
					'title'				=> __( 'Social', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'social', 'social 1' ),
					'categories'		=> array( 'social' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/social/social-1/gutentor_social.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/social/social-1/social.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/social/#section-35f902ba-cd22-4920-bc39-d104e23f3b89',
				),
				array(
					'title'				=> __( 'Social', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'social', 'social 2' ),
					'categories'		=> array( 'social' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/social/social-2/gutentor_social.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/social/social-2/social.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/social/#section-5c8ad360-50a8-4201-ad09-13975983be71',
				),
				array(
					'title'				=> __( 'Tabs', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'tabs', 'tabs 1' ),
					'categories'		=> array( 'tabs' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/tabs/tabs-1/gutentor_tabs.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/tabs/tabs-1/tabs.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/tabs/#section-a066ea7b-a982-4a33-a45f-0a6bec70bce7',
				),
				array(
					'title'				=> __( 'Tabs', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'tabs', 'tabs 2' ),
					'categories'		=> array( 'tabs' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/tabs/tabs-2/gutentor_tabs.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/tabs/tabs-2/tabs.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/tabs/#section-0eadb066-bbf6-464e-ae09-14a9a7c2f1e1',
				),
				array(
					'title'				=> __( 'Team', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'team', 'team 1' ),
					'categories'		=> array( 'team' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/team/team-1/gutentor_team.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/team/team-1/team.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/team/#section-3ffc55c6-ddde-4e3e-b565-b2a06c13552e',
				),
				array(
					'title'				=> __( 'Team', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'team', 'team 2' ),
					'categories'		=> array( 'team' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/team/team-2/gutentor_team.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/team/team-2/team.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/team/#section-4481ff58-fb08-4635-8e9f-6bac9d207119',
				),
				array(
					'title'				=> __( 'Testimonial', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'testimonial', 'testimonial 1' ),
					'categories'		=> array( 'testimonial' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/testimonial/testimonial-1/gutentor_testimonial.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/testimonial/testimonial-1/testimonial.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/testimonial/#section-62a6a138-ab1a-444f-a551-e6634ef72335',
				),
				array(
					'title'				=> __( 'Testimonial', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'testimonial', 'testimonial 2' ),
					'categories'		=> array( 'testimonial' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/testimonial/testimonial-2/gutentor_testimonial.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/testimonial/testimonial-2/testimonial.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/testimonial/#section-cf510900-4818-465b-8fcd-e54dae852951',
				),
				array(
					'title'				=> __( 'Timeline', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'timeline', 'timeline 1' ),
					'categories'		=> array( 'timeline' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/timeline/timeline-1/gutentor_timeline.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/timeline/timeline-1/timeline.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/timeline/#section-3b803f53-93b4-47a8-a133-1f02e3a4ad57',
				),
				array(
					'title'				=> __( 'Blog', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'blog', 'blog 1' ),
					'categories'		=> array( 'blog' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/blog/blog-1/gutentor_blog-post.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/blog/blog-1/blog.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/blog/#section-749d8301-3bc3-480d-9d25-af0583d3154a',
				),
				array(
					'title'				=> __( 'Google Map', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'google-map', 'google-map 1' ),
					'categories'		=> array( 'google-map' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/google-map/google-map-1/gutentor_google-map-post.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/google-map/google-map-1/google-map.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/google-map/#section-90380ab9-e0eb-4857-aaf3-aa976c680c39',
				),
				array(
					'title'				=> __( 'Video Popup', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'video-popup', 'video-popup 1' ),
					'categories'		=> array( 'video-popup' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/video/video-1/gutentor_video-popup.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/video/video-1/video.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/video/#section-98cd8ba9-fc36-47a6-a29b-41a6c33cf888',
				),
				array(
					'title'				=> __( 'List Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'list', 'list 1' ),
					'categories'		=> array( 'list' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/list/list-1/gutentor_list.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/list/list-1/list.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/list/#section-8f4613b9-f012-4044-b04d-0f458132a42a',
				),
				array(
					'title'				=> __( 'List Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'list', 'list 2' ),
					'categories'		=> array( 'list' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/list/list-2/gutentor_list.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/list/list-2/list.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/list/#section-10536d92-1a4d-4288-b7f5-8bea7d6349ea',
				),
				array(
					'title'				=> __( 'List Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'list', 'list 3' ),
					'categories'		=> array( 'list' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/list/list-3/gutentor_list.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/list/list-3/list.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/list/#section-0808a464-478f-4450-a825-47c5120d2199',
				),
				array(
					'title'				=> __( 'Notification Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'notification', 'notification 1' ),
					'categories'		=> array( 'notification' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/notification/notification-1/gutentor_notification.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/notification/notification-1/notification.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/notification/#section-24d95c46-83e8-4088-9d0b-df1bdff55fc7',
				),
				array(
					'title'				=> __( 'Notification Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'notification', 'notification 2' ),
					'categories'		=> array( 'notification' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/notification/notification-2/gutentor_notification.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/notification/notification-2/notification.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/notification/#section-13041e64-e723-4b90-b161-116337326d10',
				),
				array(
					'title'				=> __( 'Notification Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'notification', 'notification 3' ),
					'categories'		=> array( 'notification' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/notification/notification-3/gutentor_notification.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/notification/notification-3/notification.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/notification/#section-aafc81ad-fb2b-4753-bf93-8d14fa7753dc',
				),
				array(
					'title'				=> __( 'Notification Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'notification', 'notification 4' ),
					'categories'		=> array( 'notification' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/notification/notification-4/gutentor_notification.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/notification/notification-4/notification.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/notification/#section-f0d29cf8-7fa6-4ab2-ab57-3ba2f7b5ff20',
				),
				array(
					'title'				=> __( 'Show More/Less Block', 'gutentor' ),
					'type'				=> 'block',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'show-more-less', 'show-more-less 1' ),
					'categories'		=> array( 'show-more-less' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/show-more-less/show-more-less-1/gutentor_show-more.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/blocks/show-more-less/show-more-less-1/show-more-less.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/free-block-demo/show-more-less/#section-1a932db4-7843-413f-b7b0-e1f44a0bb1fd',
				),
				array(
					'title'				=> __( 'Business', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'business', 'business 1' ),
					'categories'		=> array( 'business' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/business/business-1/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/business/business-1/business-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/business/',
				),
				array(
					'title'				=> __( 'Business', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'business', 'business 2' ),
					'categories'		=> array( 'business' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/business/business-2/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/business/business-2/business-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/business-2/',
				),
				array(
					'title'				=> __( 'Multipurpose', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'multipurpose', 'multipurpose 1' ),
					'categories'		=> array( 'multipurpose' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/multipurpose/multipurpose-1/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/multipurpose/multipurpose-1/multipurpose-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/multipurpose/',
				),
				array(
					'title'				=> __( 'Multipurpose', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'multipurpose', 'multipurpose 2' ),
					'categories'		=> array( 'multipurpose' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/multipurpose/multipurpose-2/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/multipurpose/multipurpose-2/multipurpose-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/multipurpose-2/',
				),
				array(
					'title'				=> __( 'Medical', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'medical', 'medical 1' ),
					'categories'		=> array( 'medical' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/medical/medical-1/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/medical/medical-1/medical-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/medical-1/',
				),
				array(
					'title'				=> __( 'Education', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'education', 'education 1' ),
					'categories'		=> array( 'education' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/education/education-1/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/education/education-1/education-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/education-1/',
				),

				array(
					'title'				=> __( 'Agency', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'agency', 'agency 1' ),
					'categories'		=> array( 'agency' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/agency/agency-1/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/agency/agency-1/agency-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/agency-1/',
				),
				array(
					'title'				=> __( 'Travel', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'travel', 'travel 1' ),
					'categories'		=> array( 'travel' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/travel/travel-1/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/travel/travel-1/travel-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/travel-1/',
				),
				array(
					'title'				=> __( 'Fitness', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'fitness', 'fitness 1' ),
					'categories'		=> array( 'fitness' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/fitness/fitness-1/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/fitness/fitness-1/fitness-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/fitness-1/',
				),
				array(
					'title'				=> __( 'Construction', 'gutentor' ),
					'type'				=> 'template',
					'author'			=> __( 'Gutentor', 'gutentor' ),
					'keywords'			=> array( 'construction', 'construction 1' ),
					'categories'		=> array( 'construction' ),
					'template_url'		=> 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/construction/construction-1/template.json',
					'screenshot_url'    => 'https://raw.githubusercontent.com/gutentor/template-library/master/templates/construction/construction-1/construction-template.jpg',
					'demo_url'    => 'https://www.demo.gutentor.com/templates/construction-1/',
				),
			);

			$templates = apply_filters( 'gutentor_advanced_import_templates', $templates_list );

			return rest_ensure_response( $templates );
		}

		/**
		 * Function to fetch template JSON.
		 *
		 * @return array|bool|\WP_Error
		 */
		public function import_template( $request ) {
			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}

			$url = $request->get_param( 'url' );
			$json = file_get_contents( $url );
			$obj = json_decode( $json );
			return rest_ensure_response( $obj );
		}

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 1.0.1
		 * @return object
		 */
		public static function get_instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance
			return $instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'gutentor' ), '1.0.0' );
		}
	}

}
Gutentor_Advanced_Import_Server::get_instance()->run();