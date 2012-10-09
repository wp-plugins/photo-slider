<?php
/**
 * General Functions File For WP Top Slider
 */

/*
 * Echo Out The Option Driven JS.
 */
function wpts_js_init(){
    
global $WP_Top_Slider;

$WPTS_OPTIONS = $WP_Top_Slider->options;

    /*
     * get all the slides.
     */
    $slides = get_terms( 'wpts_sliders' );
    
    if( isset( $WP_Top_Slider->options['wpts_use_js'] ) ){
        
        echo "<script type='text/javascript'>jQuery(document).ready(function($){ \n";
        //Cycle through them
        foreach( $slides as $slide ){

            $slider_opts = array( 'timeout' , 'pause', 'pauseOnPagerHover' , 'fx', 'easing', 'speed' );

            $data = get_option( 'wpts_' . $slide->slug . '_options' );

            //If A Slider Timeout Has Been Specified:
            $timeout = ( isset( $data['wpts_timeout'] ) ) ?  $data['wpts_timeout'] : 0 ;

            //Pause On Hover
            $pause = ( isset( $data['wpts_pauseonhover'] ) && $data['wpts_pauseonhover'] == 'on' ) ? 1 : false ;

            //Pause on Pager Hover?
            $pauseOnPagerHover = ( isset( $data['wpts_pauseonpagerhover'] ) && $data['wpts_pauseonpagerhover'] == 'on' ) ? 1 : false ;

            //wpts_slider_speed
            $speed = ( isset( $data['wpts_speed'] ) ) ? $data['wpts_speed']  : false ;

            //Effects
            $fx = false;
            if(  isset( $data['wpts_effects'] ) ){
                $fx = implode( ',', $data['wpts_effects'] );
            }

            //Easing
            $easing = ( isset( $data['wpts_easing'] ) ) ?  $data['wpts_easing'] : false ;

            echo "$( '#wpts_slideshow_" . $slide->slug . " .wpts_slideshow_' ).cycle({\n";
                foreach( $slider_opts as $opt ){
                    if( $$opt !== false ){
                        echo "'" . $opt . "' : '" . $$opt . "',\n";
                    }
                }
            //Use Previous Next Buttons?
            if( isset( $data['wpts_useprevnext'] ) && $data['wpts_useprevnext'] == 'on' ){
                echo "'prev' : '#wpts_prev_" . $slide->slug . "', 'next' : '#wpts_next_" . $slide->slug . "',";
            }
            echo "'pager' : '#wpts_slideshow_pager_" . $slide->slug . "' \n";
            echo "}); \n\n";
            
            //Use Pause Resume Buttons?
            if( isset( $data['wpts_usepauseresume'] ) ){
                echo "$( '.wpts_pause_" . $slide->slug . "' ).click( function(){ $( '#wpts_slideshow_" . $slide->slug . " .wpts_slideshow_' ).cycle( 'pause' ) }) \n";
                echo "$( '.wpts_resume_" . $slide->slug . "' ).click( function(){ $( '#wpts_slideshow_" . $slide->slug . " .wpts_slideshow_' ).cycle( 'resume' ) }) \n";
            }

        }
        

        echo "})</script>";
    }//End if use JS

    if( isset( $WP_Top_Slider->options['wpts_use_css'] ) ){
        
        echo '<style type="text/css" media="all">';
        
        foreach( $slides as $slide ){

            $data = get_option( 'wpts_' . $slide->slug . '_options' );

            //echo '/*' . var_dump( $data ) . /'*/';//wpts_outerwidth
            
            $outerwidth = $data['wpts_outerwidth'];
            $halfwidth = $data['wpts_halfwidth'];
            $fullwidth = $data['wpts_fullwidth'];
            $height = $data['wpts_height'];
            $halfHeight = $height / 2;
            $prevNextHeight = $halfHeight;
            $textHalfWidth = ( $fullwidth - $halfwidth ) - 20;
            $pagerTop = $height - 20 ;
            $prevBgPath = WPTS_HTTP_PATH . 'images/wpss_prev_arrow.png';
            $nextBgPath = WPTS_HTTP_PATH . 'images/wpss_next_arrow.png';

            echo '
            #wpts_slideshow_' . $slide->slug . '{ padding:10px; background-color:#F1F1F1; height:' . $height . 'px; overflow:hidden; position:relative; margin-bottom:5px; clear:both; width: ' . $outerwidth . 'px }
            #wpts_slideshow_' . $slide->slug . ' .wpts_img.half{ float:left; width:' . $halfwidth . 'px; }
            #wpts_slideshow_' . $slide->slug . ' .wpts_content_half{ padding: 10px; float:left; width:' . $textHalfWidth . 'px; }
            #wpts_slideshow_pager_' . $slide->slug . '{ position:absolute; z-index:1000; right:17px; top:' . $pagerTop . 'px; }
            #wpts_slideshow_pager_' . $slide->slug . ' a{ float:left; height:20px; width:20px; text-align:center; line-height:20px; color:#FFF; background-color:#000; margin-right:5px; }
            #wpts_slideshow_pager_' . $slide->slug . ' a:hover, #wpts_slideshow_pager_' . $slide->slug . ' a.activeSlide{ background-color:#1f1e1e; }
            #wpts_next_' . $slide->slug . '{ position:absolute; right: 0 ; top: ' . $prevNextHeight . 'px ; height:30px; width: 30px; cursor:pointer; z-index:1000; background: url(' . $nextBgPath . ') no-repeat; }
            #wpts_prev_' . $slide->slug . '{ position:absolute; left: 0 ; top: ' . $prevNextHeight . 'px ; height:30px; width: 30px; cursor:pointer; z-index:1000; background: url(' . $prevBgPath . ') no-repeat; }
            ';

        }
        
        echo '</style>';
    }

}
add_action( 'wp_head' , 'wpts_js_init' );

/**
 * Display the post content.
 * 
 * Usage of the_content() in the WPTS Templates is not recommended as it may conflict with the 
 *
 * @since 1.3
 *
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param bool $stripteaser Optional. Strip teaser content before the more text. Default is false.
 */
function wpts_the_content($more_link_text = null, $stripteaser = false) {
	$content = get_the_content($more_link_text, $stripteaser);
	$content = apply_filters('wpts_the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	echo $content;
}
?>