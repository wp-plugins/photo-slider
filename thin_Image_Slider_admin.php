<?php $default_thin_Image_Slider_height = get_option('thin_Image_Slider_height');
      $default_thin_Image_Slider_width = get_option('thin_Image_Slider_width');
	  $default_thin_Image_Slider_bg_color = get_option('thin_Image_Slider_bg_color');
      $default_thin_Image_Slider_border_color = get_option('thin_Image_Slider_border_color');
	  $default_thin_Image_Slider_border_width = get_option('thin_Image_Slider_border_width');
	  $default_thin_Image_Slider_padding = get_option('thin_Image_Slider_padding');
	  $default_thin_Title_Character_Length = get_option('thin_Title_Character_Length');
	  $default_thin_sliding_speed = get_option('thin_sliding_spped');
	  $default_thin_caption = get_option('thin_caption');
	  $default_thin_transition_type = get_option('thin_transition_type');
?>
      <form method="post">
           <label for="thin_Image_Slider_width">Width :</label>
           <input type="text" name="thin_Image_Slider_width" value="<?php echo $default_thin_Image_Slider_width;?>"/>
           <label for="thin_Image_Slider_height">Height :</label>
           <input type="text" name="thin_Image_Slider_height" value="<?php echo $default_thin_Image_Slider_height;?>"/><br/>
           <label for="thin_Image_Slider_border_width">Border Width :</label>
           <input type="text" name="thin_Image_Slider_border_width" value="<?php echo $default_thin_Image_Slider_border_width;?>"/>
           <label for="thin_Image_Slider_border_color">Border Color :</label>
           <input type="text" name="thin_Image_Slider_border_color" value="<?php echo $default_thin_Image_Slider_border_color;?>"/><br/>
           <label for="thin_Image_Slider_bg_color">Background Color :</label>
           <input type="text" name="thin_Image_Slider_bg_color" value="<?php echo $default_thin_Image_Slider_bg_color;?>"/>
           <label for="thin_Image_Slider_padding">Padding :</label>
           <input type="text" name="thin_Image_Slider_padding" value="<?php echo $default_thin_Image_Slider_padding;?>"/><br/>
           <label for="thin_Title_Character_Length">No of Character (Title) :</label>
           <input type="text" name="thin_Title_Character_Length" value="<?php echo $default_thin_Title_Character_Length;?>"/>
           <label for="thin_sliding_spped">Speed :</label>
           <input type="text" name="thin_sliding_spped" value="<?php echo $default_thin_sliding_speed;?>"/><br/>
           <label for="thin_caption">Display Caption / Title :</label>
           <input type="radio" name="thin_caption" value="yes"<?php echo $default_thin_caption;?> /> Yes <input type="radio" name="thin_caption" value="no"<?php echo $default_thin_caption;?> /> No<br/>
           <label for="thin_transition_type">Transition Type :</label>
           <input type="radio" name="thin_transition_type" value="fadein"<?php echo $default_thin_transition_type;?> /> Fade In/Out <input type="radio" name="thin_transition_type" value="slide"<?php echo $default_thin_transition_type;?> /> Slide<br/>
                      
           <input type="submit" name="submit" value="Submit"/>
        </form>
        