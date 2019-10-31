<?php
/**
 * Blog Post Template 1
 *
 * @param {string} $data
 * @param {array} $post
 * @param {array} $attributes
 * @return {mix}
 */
if(!function_exists('gutentor_blog_post_template1')){

    function gutentor_blog_post_template1($data,$post,$attributes){


        if('blog-template1' != $attributes['blockBlogTemplate']){
            return $data;
        }
        $output = '';
        $overlay = ($attributes['blockImageBoxImageOverlayColor']['enable']) ? "<div class='overlay'></div>" : '';
        $enable_image_display = $attributes['blockEnableImageBoxDisplayOptions'] ? $attributes['blockEnableImageBoxDisplayOptions'] : false;
        if ($attributes['enablePostImage']) {
            $image_output = '';
            $output .= '<div class="gutentor-single-item-image-box">';
                if ('bg-image' == $attributes['blockImageBoxDisplayOptions'] && $enable_image_display) {
                    $url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                    if ($url) {
                        $image_output .= '<div class="gutentor-bg-image" style="background-image:url(' . $url . ')">';
                        $image_output .= $overlay;
                        $image_output .= '</div>';
                    }

                }
                else {
                    if(has_post_thumbnail()){
                        $image_output .= '<div class="gutentor-image-thumb">';
                        $image_output .= get_the_post_thumbnail('','','');
                        $image_output .= $overlay;
                        $image_output .= '</div>';
                    }
                }
                $output .= apply_filters( 'gutentor_save_item_image_display_data', $image_output,get_permalink(),$attributes);
            $output .= '</div>';/*.gutentor-single-item-image-box*/
        }
        $output .= '<div class="gutentor-post-content">';
            if ($attributes['blockSingleItemTitleEnable']) {
                $title_tag = $attributes['blockSingleItemTitleTag'];
                $output .= '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">';
                $output .= '<'.$title_tag.' class="gutentor-single-item-title">';
                $output .= get_the_title();
                $output .= '</'.$title_tag.'>';
                $output .= '</a>';
            }
            $output .= '<div class="entry-meta">';
                if ($attributes['enablePostDate']) {
                    $output .= '<div class="posted-on"><i class="far fa-calendar-alt"></i>';
                    $output .= '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . get_the_date() . '</a>';
                    $output .= '</div>';

                }
                if ($attributes['enablePostAuthor']) {
                    $output .= '<div class="author vcard"><i class="far fa-user"></i>';
                    $output .= '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . get_the_author() . '</a>';
                    $output .= '</div>';
                }
                if ($attributes['enablePostCategory']) {
                    $categories_list = get_the_category_list(esc_html__(', ', 'gutentor'));
                    if ($categories_list) {
                        $output .= '<div class="cat-links"><i class="fas fa-tags"></i>' . $categories_list . '</div>';
                    }
                }
            $output         .= '</div>';/*.entry-meta*/
            if ($attributes['excerptLength'] > 0 && $attributes['blockSingleItemDescriptionEnable']) {
                $desc_tag = $attributes['blockSingleItemDescriptionTag'];
                $output         .= '<div class="gutentor-post-excerpt gutentor-single-item-desc">';
                $output         .= "<$desc_tag>" . gutentor_get_excerpt_by_id( $post->ID, $attributes['excerptLength'] ) . "</$desc_tag>";
                $output         .= '</div>';
            }
            if ($attributes['blockSingleItemButtonEnable']) {
                $default_class = gutentor_concat_space('gutentor-button','gutentor-single-item-button');
                $icon = (isset($attributes['buttonIcon'])&& $attributes['buttonIcon']['value']) ?  '<i class="gutentor-button-icon '.$attributes['buttonIcon']['value'].'" ></i>' : '';
                $icon_options = (isset($attributes['blockSingleItemButtonIconOptions'])) ?  $attributes['blockSingleItemButtonIconOptions'] : '';
                $link_options = (isset($attributes['blockSingleItemButtonLinkOptions'])) ?  $attributes['blockSingleItemButtonLinkOptions'] : '';
                $output .= '<a class="'.gutentor_concat_space($default_class,GutentorButtonOptionsClasses($icon_options)).'" '.apply_filters('gutentor_save_link_attr','',esc_url(get_permalink()),$link_options). '>'.$icon.'<span>'.  esc_html($attributes['buttonText']) . '</span></a>';
            }
        $output .= '</div>';/*.gutentor-post-content*/
        return $output;
    }
}
add_filter( 'gutentor_save_blog_post_block_template_data', 'gutentor_blog_post_template1', 10, 3 );