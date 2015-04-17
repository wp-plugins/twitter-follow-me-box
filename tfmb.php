<?php
/*
Plugin Name: Twitter Follow Me Box
Plugin URI: http://www.cirolini.com.br/wordpress-plugin-twiiter-follow-me-box/
Description: Creates a box "follow me" twitter without touching the template
Version: 2.1
Author: Rafael Cirolini
Author URI: http://www.cirolini.com.br/
License: GPL2
*/

add_action('admin_menu', 'tfmb_add_menu');
add_action('admin_init', 'tfmb_reg_function' );
add_action('wp_head', 'tfmb_add_head'); 
add_action('init', 'tmfb_reg_jquery');

register_activation_hook( __FILE__, 'tmfb_activate' );

function tmfb_activate() {
	add_option('tfmb_enable','0');
	add_option('tfmb_color','000000');
	add_option('tfmb_side','right');
	add_option('tfmb_from_top','130');
}

function tmfb_reg_jquery() {
    wp_enqueue_script('jquery');            
}    

function tfmb_add_menu() {
    $page = add_options_page('Fallow Me Options', 'Fallow Me Options', 'administrator', 'tfmb_menu', 'tfmb_menu_function');
    add_action('admin_print_scripts-' . $page, 'tfmb_admin_styles');
}

function tfmb_reg_function() {
	register_setting( 'tfmb-settings-group', 'tfmb_enable' );
	register_setting( 'tfmb-settings-group', 'tfmb_twitter_account' );
	register_setting( 'tfmb-settings-group', 'tfmb_color' );
	register_setting( 'tfmb-settings-group', 'tfmb_side' );
	register_setting( 'tfmb-settings-group', 'tfmb_from_top' );
	
	wp_register_script('tfmb_colorpicker', WP_PLUGIN_URL . '/twitter-follow-me-box/picker/colorpicker.js', array('jquery'));
}

function tfmb_admin_styles() {
	wp_enqueue_script ('jquery');
	wp_enqueue_script('tfmb_colorpicker');
}

function tfmb_add_head() {
	
	$enable = get_option('tfmb_enable');
	$twitter_account = get_option('tfmb_twitter_account');
	$color = get_option('tfmb_color');
	$side = get_option('tfmb_side');
	$top = get_option('tfmb_from_top');
	$px = "px";
	$top = "$top$px";
	
	if ($side == "left") {
		$imagem_side = "right";
	} 
	else {
		$imagem_side = "left";
	}
	
	if ($enable == 1) {
 	
 	echo "
 		<!-- by Twitter Follow Me Box v1.1 -->
 		<script type=\"text/javascript\">
 			//<![CDATA[
  			jQuery(document).ready(function(){
    			jQuery(\"body\").append(\"<div id=\\\"tfmBox\\\"></div>\");
    			jQuery(\"#tfmBox\").css({'position' : 'fixed', 'top' : '$top', 'width' : '30px', 'height' : '119px', 'z-index' : '1000', 'cursor' : 'pointer', 'background' : '#$color url(" . WP_PLUGIN_URL . "/twitter-follow-me-box/follow-me.png) no-repeat scroll $imagem_side top', '$side' : '0'});
    			jQuery(\"#tfmBox\").click(function () { 
			      window.open('http://twitter.com/$twitter_account/');
    			});
    		});
    		//]]>
  		</script>
		<!-- /by Twitter Follow Me Box -->
 	";
 	
 	}
}

function tfmb_verify_enable() {
	$enable = get_option('tfmb_enable');
	
	if ($enable == 1) {
		echo "checked=\"checked\"";
	}
}

function tfmb_verify_disable() {
	$enable = get_option('tfmb_enable');
	
	if ($enable == 0) {
		echo "checked=\"checked\"";
	}
}

function tfmb_menu_function() {
?>

<script type="text/javascript">
      var $jq = jQuery.noConflict();
		$jq(document).ready(function() {

		  $jq('#tfmb_color').ColorPicker({
			  onShow: function (colpkr) { 
			       $jq(colpkr).fadeIn(500); 
				   return false; 
			  }, 
			  onHide: function (colpkr) {
				  $jq(colpkr).fadeOut(500); 
				  return false; 
			  },
			  onSubmit: function(hsb, hex, rgb, el) {
				  $jq(el).val(hex);
				  $jq(el).ColorPickerHide();
			  },
			  onBeforeShow: function () {
				  $jq(this).ColorPickerSetColor(this.value);
			  }
		  })
		  .bind('keyup', function(){
			  $jq(this).ColorPickerSetColor(this.value);
		  });
		});
</script>

<link rel="stylesheet" media="screen" type="text/css" href="<?php echo bloginfo( 'url' ) . '/wp-content/plugins/twitter-follow-me-box/picker/colorpicker.css'; ?>" />

<div class="wrap">
<h2>Twitter Follow Me Box</h2>
 
<form method="post" action="options.php">
    <?php settings_fields( 'tfmb-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Enable</th>
        <td>
        	<label> 
        		<input type="radio" value="1" <?php tfmb_verify_enable(); ?> name="tfmb_enable">
        		Enable
        	</label>
        	<br>
        	<label>
        		<input type="radio" value="0" <?php tfmb_verify_disable(); ?> name="tfmb_enable">
        		Disable
        	</label>
        </td>
        </tr>
 
        <tr valign="top">
        <th scope="row">Twitter Account</th>
        <td>
        <input type="text" name="tfmb_twitter_account" value="<?php echo get_option('tfmb_twitter_account'); ?>" />
        </tr>
    	
    	<tr valign="top">
        <th scope="row">Color</th>
        <td>
        <label>
        <input type="text" name="tfmb_color" id="tfmb_color" size="7" value="<?php echo get_option('tfmb_color'); ?>" />
        </label>
        </tr>
        
        <tr valign="top">
        <th scope="row">Side</th>
        <td>
        	<select name="tfmb_side">
        		<option value="right">Right</option>
        		<option value="left">Left</option>
        	</select>
        </tr>
        
        <tr valign="top">
        <th scope="row">From Top</th>
        <td>
        <label>
        <input type="text" name="tfmb_from_top" size="3" value="<?php echo get_option('tfmb_from_top'); ?>" />
        px
        </label>
        </tr>
    
    
    </table>
 
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
 
</form>
</div>

<?php } ?>
