<?php
/*
 * The Outputting function, Template tag and Shortcode for WP Top Slider.
 */

/*
 * Template Tag
 */
function wp_top_slider( $slider , $echo = true ){

    global $post , $WPTS_OPTIONS, $wp_query;

    //Preserve The Original query
    //$original_query = $wp_query->query;
    
    $timthumbsrc = WPTS_HTTP_PATH . 'includes/timthumb/timthumb.php';

    $data = get_option( 'wpts_' . $slider . '_options' );
    
    $prev_next_buttons = ( isset( $data['wpts_useprevnext'] ) && $data['wpts_useprevnext'] == 'on' ) ? true : false ;
    $pause_resume_links = ( isset( $data['wpts_usepauseresume'] ) && $data['wpts_usepauseresume'] == 'on' ) ? true : false ;
    $pager = ( isset( $data['wpts_usepager'] ) && $data['wpts_usepager'] == 'on' ) ? true : false ;
    
    //Image Measurement Filters
    $full_width = $data['wpts_fullwidth'] ;
    $half_width = $data['wpts_halfwidth'];
    $image_height = $data['wpts_height'];

    /*
     * Set Some args and run a WP Query
     */
    $slide_args = array(
        'post_type' => 'wpts_slides',
        'posts_per_page' => -1,
	'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy'  => 'wpts_sliders',
                'field'     => 'slug',
                'terms'     => $slider
            )	
        )
    );
    
    //Handle The ordering.
    if( isset( $data['wpts_order'] ) ){
        
        switch( $data['wpts_order'] ):
            
            case 'asc':
                $slide_args['order'] = 'ASC';
                break;
            
            case 'desc':
                $slide_args['order'] = 'DESC';
                break;
            
            case 'random':
                $slide_args['orderby'] = 'rand';
                break;
            
            case 'slide_number':
            default:
                $slide_args['order'] = 'ASC';
                $slide_args['orderby'] = 'meta_value_num';
                $slide_args['meta_key'] = '_slide_order';
                break;
            
        endswitch;
        
    } else {
        $slide_args['order'] = 'ASC';
        $slide_args['orderby'] = 'meta_value_num';
        $slide_args['meta_key'] = '_slide_order';
    }

    $slides = new WP_Query( $slide_args );
    
    /*
     * Let The Party Begin!
     */
    if( $slides -> have_posts() ):

    $out = '<div class="wpts_slideshow" id="wpts_slideshow_' . $slider . '">';
        
        if( $prev_next_buttons ){ $out .= '<div id="wpts_prev_' . $slider . '"></div>'; }
        
        $out .= '<div class="wpts_slideshow_">';

            while( $slides->have_posts() ): $slides->the_post();

            $out .= '<div id="slide-' . $post->ID . '" class="wpts_slide">';
            
            $type = get_post_meta($post->ID , '_slide_type' , true);
            
            //No Images = NO Images!
            if( ! has_post_thumbnail( $post->ID) )
                $type = 'Full Width Text';
            
            $wpts_default_path = WPTS_ABSPATH . 'templates';
            
            if( file_exists( trailingslashit( TEMPLATEPATH ) . 'wpts' ) ){
                $wpts_templatepath = trailingslashit( TEMPLATEPATH ) . 'wpts';
            } else {
                $wpts_templatepath = $wpts_default_path;
            }

            switch ( $type ){

                case 'Full Width Image':
                    
                    /**
                     * Template Heirachy : 
                     * theme/wpts/{$slug}_full-width-image.php
                     * theme/wpts/full-width-image.php
                     * wp-top-slider/templates/full-width-image.php
                     */
                    if( file_exists( $wpts_templatepath . '/' . $slider . '_full-width-image.php' ) ){
                        $include = $wpts_templatepath . '/' . $slider . '_full-width-image.php';
                    } elseif( file_exists( $wpts_templatepath . '/full-width-image.php' ) ) {
                        $include = $wpts_templatepath . '/full-width-image.php';
                    } else {
                        $include = $wpts_default_path . '/full-width-image.php';
                    }
                    
                    break;

                case 'Left Half Image':
                    
                    /**
                     * Template Heirachy : 
                     * theme/wpts/{$slug}_left-half-image.php
                     * theme/wpts/left-half-image.php
                     * wp-top-slider/templates/left-half-image.php
                     */
                    if( file_exists( $wpts_templatepath . '/' . $slider . '_left-half-image.php' ) ){
                        $include = $wpts_templatepath . '/' . $slider . '_left-half-image.php';
                    } elseif( file_exists( $wpts_templatepath . '/left-half-image.php' ) ) {
                        $include = $wpts_templatepath . '/left-half-image.php';
                    } else {
                        $include = $wpts_default_path . '/left-half-image.php';
                    }
                    
                    break;

                case 'Right Half Image':
                    
                    /**
                     * Template Heirachy : 
                     * theme/wpts/{$slug}_right-half-image.php
                     * theme/wpts/right-half-image.php
                     * wp-top-slider/templates/right-half-image.php
                     */
                    if( file_exists( $wpts_templatepath . '/' . $slider . '_right-half-image.php' ) ){
                        $include = $wpts_templatepath . '/' . $slider . '_right-half-image.php';
                    } elseif( file_exists( $wpts_templatepath . '/right-half-image.php' ) ) {
                        $include = $wpts_templatepath . '/right-half-image.php';
                    } else {
                        $include = $wpts_default_path . '/right-half-image.php';
                    }

                    break;

                case 'Full Width Text':
                default:
                    
                    /**
                     * Template Heirachy : 
                     * theme/wpts/{$slug}_full-width-text.php
                     * theme/wpts/full-width-text.php
                     * wp-top-slider/templates/full-width-text.php
                     */
                    if( file_exists( $wpts_templatepath . '/' . $slider . '_full-width-text.php' ) ){
                        $include = $wpts_templatepath . '/' . $slider . '_full-width-text.php';
                    } elseif( file_exists( $wpts_templatepath . '/full-width-text.php' ) ) {
                        $include = $wpts_templatepath . '/full-width-text.php';
                    } else {
                        $include = $wpts_default_path . '/full-width-text.php';
                    }

                    break;

            }
            
            ob_start();
            
            include $include;
            
            $out .= ob_get_contents();
            
            ob_end_clean();

            $out .= '</div>'; //End Slide


            endwhile; 

        $out .= '</div>';

        if( $pager ){ $out .= '<div id="wpts_slideshow_pager_' . $slider . '"></div>'; }
        
        if( $prev_next_buttons ){ $out .= '<div id="wpts_next_' . $slider . '"></div>'; }
        
        
        
    $out .= '</div><!--end WP Top Slider slideshow-->';
    
    if( $pause_resume_links ):
        $out .= '<a href="javascript:void(0)" class="wpts_pause_' . $slider . '">' . __( 'Pause' , 'WP_Top_Slider' ) . '</a>';
        $out .= '<a href="javascript:void(0)" class="wpts_resume_' . $slider . '">' . __( 'Resume' , 'WP_Top_Slider' ) . '</a>';
    endif;

    else: //No Slides Found

    $out = '<!-- ' . __( 'No Slides Are In The Selected Slider Or The Slider Selected Has Been Incorrectly Named.' , 'WP_Top_Slider' ) . ' -->';

    endif; 
    
    //wp_reset_query(); //This should have done it.
    
    wp_reset_postdata();
    
    //query_posts( $original_query ); //Force Reset.
    
    if( ! $echo )
        return $out;

    echo $out;
    
}


/*
 * Shortcode
 */
function sc_wpts( $atts ){
    extract(shortcode_atts(array(
        'name' => false
    ), $atts));

    if( ! $name )
        return false;
    
    return WP_Top_Slider( $name,  false );
    
}
add_shortcode( 'TopSlider' , 'sc_wpts' );

?>