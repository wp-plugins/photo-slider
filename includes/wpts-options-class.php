<?php
/* 
 * Admin Options Page And Save Function.
 */

class WPTS_OPTIONS {

    /*
     * Function to run on Init.
     */
    function Init(){

        register_setting( 'WPTS_OPTIONS_group', 'WPTS_OPTIONS' );

        add_settings_section( 'wpts_general_options', 'General Options', array('WPTS_OPTIONS', 'General_Overview'), 'WPTS_OPTIONS_page' );

        /*
         * General Option Fields    
         */
        $use_css_data_arr = array(
            'id'          => 'wpts_use_css',
            'description' => __( 'Uncheck This Box To Disable WP Top Slider From Using CSS', 'WP_Top_Slider' )
        );
        add_settings_field( 'wpts_use_css', __( 'Disable CSS?', 'WP_Top_Slider' ) , array('WPTS_OPTIONS', 'Checkbox'), 'WPTS_OPTIONS_page', 'wpts_general_options', $use_css_data_arr );

        $use_js_data_arr = array(
            'id' => 'wpts_use_js',
            'description' => __( 'Uncheck This Box To Disable WP Top Slider From Using Javascript.<br /> ( Not recommended unless you are going to add it somewhere else, it is necessary to animate the slides! )', 'WP_Top_Slider' )
        );
        add_settings_field( 'wpts_use_js', __( 'Disable Javascript?', 'WP_Top_Slider' ), array('WPTS_OPTIONS', 'Checkbox'), 'WPTS_OPTIONS_page', 'wpts_general_options', $use_js_data_arr );

        $footer_creds_data_arr = array(
            'id' => 'wpts_footer_credits',
            'description' => __( 'If you like this plugin, you can enable the addition of a small link in the footer of your website to the author of the plugin', 'WP_Top_Slider' )
        );
        add_settings_field( 'wpts_footer_credits', __( 'Enable Footer Credits?', 'WP_Top_Slider' ), array('WPTS_OPTIONS', 'Checkbox'), 'WPTS_OPTIONS_page', 'wpts_general_options', $footer_creds_data_arr );


        /*
         * Hook Up The Form Fields For the Add / Edit Slider Forms.
         */
        add_action( 'wpts_sliders_edit_form_fields' , array( 'WPTS_OPTIONS' , 'Edit_Sliders_Fields' ), 10, 2 );
        add_action( 'wpts_sliders_add_form_fields' , array( 'WPTS_OPTIONS' , 'Add_Sliders_Fields' ), 10, 1 );

        add_action( 'created_wpts_sliders' , array( 'WPTS_OPTIONS' , 'wpts_Save_Slider_Data' ) , 10, 2 );
        add_action( 'edited_wpts_sliders' , array( 'WPTS_OPTIONS' , 'wpts_Save_Slider_Data' ) , 10, 2 );

    }

    /*
     * Add the admin menus
     */
    function Add_Admin_Menus(){

        add_submenu_page( 'edit.php?post_type=wpts_slides' , __( 'Top Slider Options', 'WP_Top_Slider' ) , 'Options', 'edit_posts', 'wpts-options' , array('WPTS_OPTIONS', 'Options_Page') );
    
    }

    /*
     * Default Options For The Sliders
     */
    function Get_Default_Sliders_Options(){
        $opts = array(
            'wpts_timeout'           => 2000,
            'wpts_speed'             => 500,
            'wpts_pauseonhover'      => 'off',
            'wpts_pauseonpagerhover' => 'off',
            'wpts_effects'           => array( 'fade' ),
            'wpts_easing'            => 'swing',
            'wpts_height'            => 250,
            'wpts_outerwidth'        => 620,
            'wpts_fullwidth'         => 620,
            'wpts_halfwidth'         => 310,
            'wpts_useprevnext'       => 'on',
            'wpts_usepauseresume'    => false,
            'wpts_usepager'          => 'on',
            'wpts_order'             => 'slide_number'
            );
        return $opts;
    }

    /*
     * Create the options page
     */
    function Options_Page(){ global $WPTS_OPTIONS; ?>

        <div class="wrap">

            <h2 style="clear:both;"><?php _e( 'WP Top Slider Options' ); ?></h2>

            <form method="post" action="options.php">

                <?php settings_fields( 'WPTS_OPTIONS_group' ); ?>
                
                <?php do_settings_sections( 'WPTS_OPTIONS_page' ); ?>

                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>

            </form>

        </div>
        
    <?php }

    /*
     * Create the HTML for the extension of the edit sliders form.
     */
    function Edit_Sliders_Fields( $tag, $taxonomy ){
        
        $data = get_option( 'wpts_' . $tag->slug . '_options' );
        
        if( ! $data ){ //Something is very wrong...
            $data = WPTS_OPTIONS::Get_Default_Sliders_Options();
        }

        ?>
        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_timeout"><?php _e( 'Timeout' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <input name="wpts_timeout" id="wpts_timeout" type="text" value="<?php echo $data['wpts_timeout']; ?>" size="40" /><br />
                    <span class="description"><?php _e( 'This Controls The Timeout Value Of The Slider', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_speed"><?php _e( 'Speed' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <input name="wpts_speed" id="wpts_speed" type="text" value="<?php echo $data['wpts_speed']; ?>" size="40" /><br />
                    <span class="description"><?php _e( 'This Controls The Speed Of The Actual Transition', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_pauseonhover"><?php _e( 'Pause On Hover' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpts_pauseonhover'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpts_pauseonhover" id="wpts_pauseonhover" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e( 'Check This Box To Pause The Slideshow Advance When The User Hovers Over It', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_pauseonpagerhover"><?php _e( 'Pause On Pager Hover' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpts_pauseonpagerhover'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpts_pauseonpagerhover" id="wpts_pauseonpagerhover" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e('Check This Box To Pause The Slideshow Advance When The User Hovers Over The Pager', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>
        
        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_useprevnext"><?php _e( 'Use Previous Next Buttons' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpts_useprevnext'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpts_useprevnext" id="wpts_useprevnext" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e('Check This Box To Enable The Previous And Next Buttons', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>

        
        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_usepauseresume"><?php _e( 'Use Pause And Resume Links' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpts_usepauseresume'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpts_usepauseresume" id="wpts_usepauseresume" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e('Check This Box To Enable The Pause And Resume Links', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>
        
        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_usepager"><?php _e( 'Use Pager Links?' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <?php $checked = ( $data['wpts_usepager'] == 'on' ) ? 'checked="checked"' : '' ;  ?>
                    <input style="width:15px;" <?php echo $checked; ?> name="wpts_usepager" id="wpts_usepager" type="checkbox" value="on" /><br />
                    <span class="description"><?php _e('Check This Box To Use A Pager For Your Slider', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>


        <?php //Effects ?>
        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_effects"><?php _e( 'Effects' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <p class="description"><?php _e('These are the effects used during the transition, you can select as many as you like.', 'WP_Top_Slider' ); ?></p>
                    <?php
                    $effects = array(
                        'all', 'blindX', 'blindY', 'blindZ', 'cover', 'curtainX', 'curtainY', 'fade', 'fadeZoom',
                        'growX', 'growY', 'scrollUp', 'scrollDown', 'scrollLeft', 'scrollRight', 'scrollHorz', 'scrollVert',
                        'shuffle', 'slideX', 'slideY', 'toss', 'turnUp', 'turnDown', 'turnLeft', 'turnRight', 'uncover',
                        'wipe', 'zoom', 'none'
                    );
                    
                    foreach( $effects as $effect ){
                        $checked = ( in_array( $effect , $data['wpts_effects'] ) ) ? 'checked="checked"' : '' ;
                        echo '<p style="width:80px; float:left; margin-top:0; margin-bottom:0"><input ' . $checked . ' type="checkbox" name="wpts_effects[]" value="' . $effect . '" /><br /><span class="description"><b>' . $effect . '</b></span></p>';
                    }
                    ?>
                    <div style="clear:both"></div>
                </td>
        </tr>

        <?php //Easing ?>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_easing"><?php _e( 'Easing' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <?php
                    $easings = array(
                        'linear', 'swing', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic',
                        'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint',
                        'easeInOutQuint', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo',
                        'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic',
                        'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce', 'none'
                    );
                    ?>
                    <select name="wpts_easing" id="wpts_easing">
                        <?php
                        foreach( $easings as $easing ){
                            $selected = ( $easing == $data['wpts_easing'] ) ? 'selected="selected"' : '' ;
                            echo '<option ' . $selected . ' value="' . $easing . '">' . $easing . '</option>';
                        }
                        ?>
                    </select>
                    <br /><span class="description"><?php _e('This is the easing used in the transition.', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>

        <?php //Height ?>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_height"><?php _e( 'Height' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <input name="wpts_height" id="wpts_speed" type="text" value="<?php echo $data['wpts_height']; ?>" size="40" /><br />
                    <span class="description"><?php _e('The Height Of The Slider', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>
        
        <?php //Outerwidth ?>
        
        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_outerwidth"><?php _e( 'Outer Width' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <input name="wpts_outerwidth" id="wpts_outerwidth" type="text" value="<?php echo $data['wpts_outerwidth']; ?>" size="40" /><br />
                    <span class="description"><?php _e('The Outer Width Of The Slider Container', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>

        <?php //Fullwidth ?>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_fullwidth"><?php _e( 'Full Width' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <input name="wpts_fullwidth" id="wpts_speed" type="text" value="<?php echo $data['wpts_fullwidth']; ?>" size="40" /><br />
                    <span class="description"><?php _e('The Full Width Image Width', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>

        <?php //Halfwidth ?>

        <tr class="form-field">
                <th scope="row" valign="top"><label for="wpts_halfwidth"><?php _e( 'Half Width' , 'WP_Top_Slider'); ?></label></th>
                <td>
                    <input name="wpts_halfwidth" id="wpts_speed" type="text" value="<?php echo $data['wpts_halfwidth']; ?>" size="40" /><br />
                    <span class="description"><?php _e('The Half Width Image Width', 'WP_Top_Slider' ); ?></span>
                </td>
        </tr>
        
        <?php //Slide Order ?>

        <tr class="form-field">
            <?php $opts = array(
                'asc' => 'Ascending',
                'desc' => 'Descending',
                'slide_number' => 'Slide Number',
                'random' => 'Random'
            ); 
            ?>
            <th scope="row" valign="top"><label for="wpts_order"><?php _e( 'Slide Order' , 'WP_Top_Slider'); ?></label></th>
            <td>
                <select name="wpts_order" id="wpts_order">
                    <?php foreach( $opts as $key => $opt ){
                        $selected = ( $key == $data['wpts_order'] ) ? 'selected="selected"' : '' ;
                        echo '<option ' . $selected . ' value="' . $key . '">' . $opt . '</option>';
                    } ?>
                </select>
                <p><?php _e('Set The Order To Use When Selecting The Slides.', 'WP_Top_Slider' ); ?></p>
            </td>
        </tr>
        
        <?php
    }

    /*
     * Create the HTML for the extension of the add slider form.
     */
    function Add_Sliders_Fields( $taxonomy ){
        $data = WPTS_OPTIONS::Get_Default_Sliders_Options();
        ?>

        <div class="form-field">
                <label for="wpts_timeout"><?php _e( 'Timeout' , 'WP_Top_Slider'); ?></label>
                <input name="wpts_timeout" id="wpts_timeout" type="text" value="<?php echo $data['wpts_timeout']; ?>" size="40" />
                <p><?php _e('This Controls The Timeout Value Of The Slider', 'WP_Top_Slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpts_speed"><?php _e( 'Speed' , 'WP_Top_Slider'); ?></label>
                <input name="wpts_speed" id="wpts_speed" type="text" value="<?php echo $data['wpts_speed']; ?>" size="40" /><br />
                <p><?php _e('This Controls The Speed Of The Actual Transition', 'WP_Top_Slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpts_pauseonhover"><?php _e( 'Pause On Hover' , 'WP_Top_Slider'); ?></label>
                <input style="width:15px;" name="wpts_pauseonhover" id="wpts_pauseonhover" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Pause The Slideshow Advance When The User Hovers Over It', 'WP_Top_Slider' ); ?></p>
        </div>

        <div class="form-field">
                <label for="wpts_pauseonpagerhover"><?php _e( 'Pause On Pager Hover' , 'WP_Top_Slider'); ?></label>
                <input style="width:15px;" name="wpts_pauseonpagerhover" id="wpts_pauseonpagerhover" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Pause The Slideshow Advance When The User Hovers Over The Pager', 'WP_Top_Slider' ); ?></p>
        </div>
        
        <div class="form-field">
                <label for="wpts_useprevnext"><?php _e( 'Use Previous Next Buttons' , 'WP_Top_Slider'); ?></label>
                <input style="width:15px;" name="wpts_useprevnext" id="wpts_useprevnext" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Enable The Previous Next Buttons', 'WP_Top_Slider' ); ?></p>
        </div>
        
        <div class="form-field">
                <label for="wpts_usepauseresume"><?php _e( 'Use Pause And Resume Links' , 'WP_Top_Slider'); ?></label>
                <input style="width:15px;" name="wpts_usepauseresume" id="wpts_usepauseresume" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Enable The Pause And Resume Links', 'WP_Top_Slider' ); ?></p>
        </div>
        
        <div class="form-field">
                <label for="wpts_usepager"><?php _e( 'Use Pager Links?' , 'WP_Top_Slider'); ?></label>
                <input style="width:15px;" name="wpts_usepager" id="wpts_usepager" type="checkbox" value="on" />
                <p><?php _e('Check This Box To Use A Pager For Your Slider', 'WP_Top_Slider' ); ?></p>
        </div>

        <?php //Effects ?>
        <div class="form-field">
                <label for="wpts_effects"><?php _e( 'Effects' , 'WP_Top_Slider'); ?></label>
                <p><?php _e('These are the effects used during the transition, you can select as many as you like.', 'WP_Top_Slider' ); ?></p>
                <?php
                    $effects = array(
                        'all', 'blindX', 'blindY', 'blindZ', 'cover', 'curtainX', 'curtainY', 'fade', 'fadeZoom',
                        'growX', 'growY', 'scrollUp', 'scrollDown', 'scrollLeft', 'scrollRight', 'scrollHorz', 'scrollVert',
                        'shuffle', 'slideX', 'slideY', 'toss', 'turnUp', 'turnDown', 'turnLeft', 'turnRight', 'uncover',
                        'wipe', 'zoom', 'none'
                    );
                    
                    foreach( $effects as $effect ){
                        $checked = ( in_array( $effect , $data['wpts_effects'] ) ) ? 'checked="checked"' : '' ;
                        echo '<p style="width:80px; float:left; margin-top:0; margin-bottom:0"><input ' . $checked . ' type="checkbox" name="wpts_effects[]" value="' . $effect . '" /><span class="description"><b>' . $effect . '</b></span></p>';
                    }
                ?>
                <div style="clear:both;"></div>
        </div>

        <?php //Easing ?>

        <div class="form-field">
                <label for="wpts_easing"><?php _e( 'Easing' , 'WP_Top_Slider'); ?></label>
                <?php
                    $easings = array(
                        'linear', 'swing', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic',
                        'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint',
                        'easeInOutQuint', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo',
                        'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic',
                        'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce', 'none'
                    );
                ?>
                <select name="wpts_easing" id="wpts_easing">
                    <?php
                    foreach( $easings as $easing ){
                        //$selected = ( $easing == $data['wpts_easing'] ) ? 'selected="selected"' : '' ;
                        echo '<option value="' . $easing . '">' . $easing . '</option>';
                    }
                    ?>
                </select>
                <p><?php _e('This is the easing used in the transition.', 'WP_Top_Slider' ); ?></p>
        </div>

        <?php //Height ?>

        <div class="form-field">
                <label for="wpts_height"><?php _e( 'Height' , 'WP_Top_Slider'); ?></label>
                <input name="wpts_height" id="wpts_height" type="text" value="<?php echo $data['wpts_height']; ?>" size="40" />
                <p><?php _e('The Height Of The Slider', 'WP_Top_Slider' ); ?></p>
        </div>
        
        <?php //Outerwidth ?>

        <div class="form-field">
                <label for="wpts_outerwidth"><?php _e( 'Outer Width' , 'WP_Top_Slider'); ?></label>
                <input name="wpts_outerwidth" id="wpts_outerwidth" type="text" value="<?php echo $data['wpts_outerwidth']; ?>" size="40" />
                <p><?php _e('The Outer Width Of The Slider Container', 'WP_Top_Slider' ); ?></p>
        </div>

        <?php //Fullwidth ?>

        <div class="form-field">
                <label for="wpts_fullwidth"><?php _e( 'Full Width' , 'WP_Top_Slider'); ?></label>
                <input name="wpts_fullwidth" id="wpts_fullwidth" type="text" value="<?php echo $data['wpts_fullwidth']; ?>" size="40" />
                <p><?php _e('The Full Width Image Width', 'WP_Top_Slider' ); ?></p>
        </div>

        <?php //Halfwidth ?>

        <div class="form-field">
                <label for="wpts_halfwidth"><?php _e( 'Half Width' , 'WP_Top_Slider'); ?></label>
                <input name="wpts_halfwidth" id="wpts_halfwidth" type="text" value="<?php echo $data['wpts_halfwidth']; ?>" size="40" />
                <p><?php _e('The Half Width Image Width', 'WP_Top_Slider' ); ?></p>
        </div>
        
        <?php //Order ?>

        <div class="form-field">
            <?php $opts = array(
                'asc' => 'Ascending',
                'desc' => 'Descending',
                'slide_number' => 'Slide Number',
                'random' => 'Random'
            ); 
            ?>
            <label for="wpts_order"><?php _e( 'Slide Order' , 'WP_Top_Slider'); ?></label>
            <select name="wpts_order" id="wpts_order">
                <?php foreach( $opts as $key => $opt ){
                    //$selected = ( $key == $data['wpts_order'] ) ? 'selected="selected"' : '' ;
                    echo '<option value="' . $key . '">' . $opt . '</option>';
                } ?>
            </select>
            <p><?php _e('Set The Order To Use When Selecting The Slides.', 'WP_Top_Slider' ); ?></p>
        </div>

        <?php
    }

    /*
     * Save the data
     */
    function Wpts_Save_Slider_Data( $term_id, $tt_id ){
        $fields = array(
            'wpts_timeout',
            'wpts_speed',
            'wpts_pauseonhover',
            'wpts_pauseonpagerhover',
            'wpts_effects',
            'wpts_easing',
            'wpts_height',
            'wpts_fullwidth',
            'wpts_halfwidth',
            'wpts_useprevnext',
            'wpts_usepauseresume',
            'wpts_usepager',
            'wpts_outerwidth',
            'wpts_order'
        );

        //Get the original data
        $data = WPTS_OPTIONS::Get_Default_Sliders_Options();
        
        $from_posteditor = false;
        
        if( isset( $_POST['tag-name'] ) ){
            $slider_name = $_POST['tag-name'];
        } elseif( isset( $_POST['name'] ) ) {
            $slider_name = $_POST['name'];
        } else {
            $slider_name = $_POST['newwpts_sliders'];
            $from_posteditor = true;
        }

        $slug = ( empty( $_POST['slug'] ) ) ? strtolower( str_replace( ' ' , '-' , $slider_name ) ) :  $_POST['slug'] ;
        
        //Overwrite Defaults If Has Been Posted
        foreach( $fields as $field ){

            if( isset( $_POST[$field] ) ){
                $data[$field] = $_POST[$field];
            } elseif( ! $from_posteditor ) {
                $data[$field] = false;
            }

        }
        update_option( 'wpts_' . $slug . '_options' , $data );
        
    }

    /*
     * Output General Info
     */
    function General_Overview(){
        _e( '<p>These are the general options for controlling the WP Top Slider. You can control the effects of each slider individually, This is done in the slider edit page.</p>', 'WP_Top_Slider' );
    }

    /**
     *
     * Output A Checkbox, $data should be in the following format:
     * $data = array(
     *  'id' => 'id_of_the_element',
     *  'description' => 'Option Description.'
     * )
     */
    function Checkbox( $data ){
        global $WP_Top_Slider;

        $checked = ( isset( $WP_Top_Slider->options[ $data['id'] ] ) ) ? 'checked="checked"' : '' ;

        echo '<input ' . $checked . ' id="' . $data['id'] . '" name="WPTS_OPTIONS[' . $data['id'] . ']" type="checkbox" /> <span class="description">' . $data['description'] . '</span>';
        
    }

    /*
     * Output a Text input field
     */
    function TextInput( $data ){
        global $WP_Top_Slider;

        echo '<input id="' . $data['id'] . '" name="WPTS_OPTIONS[' . $data['id'] . ']" size="40" type="text" value="' . $WP_Top_Slider->options[ $data['id'] ] . '" /> <span class="description">' . $data['description'] . '</span>';

    }

    /*
     * Output A Select Field
     */
    function Select( $data ){
        global $WP_Top_Slider;

        echo '<select name="WPTS_OPTIONS[' . $data['id'] . ']">';
        foreach( $data['options'] as $option ){

            $selected = ( $option == $WP_Top_Slider->options[ $data['id'] ] ) ? 'selected="selected"' : '' ;
            echo '<option ' . $selected . ' value="' . $option . '">' . $option . '</option>';

        }
        echo '</select> <span class="description">' . $data['description'] . '</span>';

    }

    /*
     * Output A Multiselect Field
     */
    function MultiSelect( $data ){
        global $WP_Top_Slider;

        echo '<select style="height:auto;" name="WPTS_OPTIONS[' . $data['id'] . '][]" multiple="multiple">';
        foreach( $data['options'] as $option ){

            $selected = ( in_array( $option, $WP_Top_Slider->options[ $data['id'] ] ) ) ? 'selected="selected"' : '' ;
            echo '<option ' . $selected . ' value="' . $option . '">' . $option . '</option>';
            
        }
        echo '</select> <span class="description">' . $data['description'] . '</span>';

    }

    
}
?>