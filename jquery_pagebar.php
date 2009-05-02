<?php
/* 
Plugin Name: jQuery-Pagebar
Plugin URI: http://ocean90.de/blog/wp-plugin-jquery-pagebar/
Description: A pagebar with jQuery
Version: 0.2.1
Author: ocean90
Author URI: http://ocean90.de
*/

/*
	jp_textdomain
*/
function jp_textdomain() {

	if (function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('jquerypagebar', false, dirname( plugin_basename(__FILE__) ) . '/lang');
	}
	
}


/*
	jp_filter_plugin_actions
*/
function jp_filter_plugin_actions($links, $file){
	
	static $this_plugin;

	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);

		if( $file == $this_plugin ) {
			$settings_link = '<a href="options-general.php?page=jquery_pagebar.php">' . __('Settings') . '</a>';
			$links = array_merge( array($settings_link), $links); 
		}

	return $links;
	
}


/*
	jp_scripts
*/
function jp_scripts() {
	
	wp_deregister_script('jquery');
	wp_register_script('jquery', (plugins_url('jquery-pagebar/js/jquery.js')), false, '1.3.2');
	wp_enqueue_script('jquery');
	
	wp_deregister_script('jquery-ui-core');
	wp_register_script('jquery-ui-core', ( plugins_url('jquery-pagebar/js/jquery_ui_slider.js')), false, '1.7.1');
	wp_enqueue_script('jquery-ui-core');
}    


/*
	jp_scripts
*/
function jp_hide_navigation() {
	
	function next_posts_link_css($content) { 
		return 'style="display:none;"';
	}
	
	function previous_posts_link_css($content) { 
		return 'style="display:none;"';
	}
	
	add_filter('next_posts_link_attributes', 'next_posts_link_css' ); 
	add_filter('previous_posts_link_attributes', 'previous_posts_link_css' );
	
}


/*
	jp_add_header_js
*/
function jp_add_header_js() {
	
	global $paged, $wp_query;
	
	$options = jp_get_option();
		
	if (is_home() or is_search()) {	 
				
		$url = get_bloginfo('url');
	
		if (is_search()) {
			$loc_href =  ' \'' . $url .'/?s=' . urlencode(get_query_var('s')) . '&paged=\'+ ui.value ;';
		} else {
			$loc_href =  '\''.$url .'/?paged=\'+ ui.value ;';
		}
		
		$dbc = addslashes ($options['display_before_current']);
		$dam = addslashes ($options['display_after_maxpage']);
		$dbm = addslashes ($options['display_before_maxpage']);

			
		if (($dbm and $dam and $dbc) == "")
		 	$dbm= "/";
	
		echo "\n";	
	
		?>
    
<script type="text/javascript">
/* jQuery Pagebar Script*/
jQuery.noConflict();
jQuery(document).ready(function() {
	jQuery("#slider").slider({
		value: <?php if ($paged > 1) echo $paged; else echo "1"; ?>,
		min: 1,
		max: <?php $max_page = $wp_query->max_num_pages; echo $max_page;?>,
		step: 1,
		stop: function(event, ui) {
			var page =<?php if ($paged > 1) echo $paged; else echo "1"; ?>;
			if (page !=  ui.value) {
			  window.location.href =  <?php echo $loc_href ?>
			}
		},
		slide: function(event,ui) {
			jQuery("#pages").empty().append('<?php echo $dbc; ?>'+ ui.value + '<?php echo $dbm, $max_page, $dam; ?>');
		},
	});
	jQuery("#pages").append('<?php echo $dbc; ?>'+jQuery("#slider").slider("value") +'<?php echo $dbm, $max_page, $dam; ?>');
});
</script>

    <?php
	
	echo "\n";
	
	}
	
}


/*
	 jp_add_loop_html
*/
function jp_add_loop_html() {
	
	global  $wp_query;
	
	$max_page = $wp_query->max_num_pages;
	
	if($max_page > 1) { 
	
	?>
    
	<div id="pages"></div>
	<div id="slider"></div>
    
 	<?php
	
    }
	
}


/*
	jp_get_option
*/
function jp_get_option() {
	
	$jp_options = get_option('jp_options');
	
	$jp_get_option_array = array(
								
		'colorpicker_bar_bg_color_value' => $jp_options['bar_bg_color'],
		'colorpicker_bar_border_color_value' => $jp_options['bar_border_color'],
		
		'colorpicker_handle_bg_color_value' => $jp_options['handle_bg_color'],
		'colorpicker_handle_border_color_value' => $jp_options['handle_border_color'],
		'colorpicker_handle_active_bg_color_value' => $jp_options['handle_active_bg_color'],
		'colorpicker_handle_active_border_color_value' => $jp_options['handle_active_border_color'],
		'colorpicker_handle_hover_bg_color_value' => $jp_options['handle_hover_bg_color'],
		'colorpicker_handle_hover_border_color_value' => $jp_options['handle_hover_border_color'],
		
		'pagebar_position' => $jp_options['pagebar_position'],
		
		'pagebar_width' =>  $jp_options['pagebar_width'],
		
		'hide_navigation' => $jp_options['hide_navigation'],
						
		'colorpicker_number_page_font_color_value' => $jp_options['number_page_font_color'],
		'number_page_font_weight' => $jp_options['number_page_font_weight'],
		'number_page_font_size' => $jp_options['number_page_font_size'],
		
		'display_before_current' => stripslashes ($jp_options['display_before_current']),
		'display_before_maxpage' => stripslashes ($jp_options['display_before_maxpage']),
		'display_after_maxpage' => stripslashes ($jp_options['display_after_maxpage']),
						
		'uninstall' => $jp_options['uninstall'],
	
	);
	
	return $jp_get_option_array;
	
}

/* 
	jp_add_header_css 
*/
function jp_add_header_css() {
	
	$options = jp_get_option();
	
	echo "\n";
	 
	?>
    
<style type="text/css">
/* jQuery Pagebar CSS*/
.ui-corner-all {-moz-border-radius: 5px;-webkit-border-radius: 5px;}
.ui-slider { position: relative; text-align: left; }
.ui-slider .ui-slider-handle { position: absolute; z-index: 2; width: 13px; height: 13px; cursor: default; }
.ui-slider .ui-slider-range { position: absolute; z-index: 1; font-size: .7em; display: block; border: 0; }
.ui-slider-horizontal { height: .8em; }
.ui-slider-horizontal .ui-slider-handle { top: -2px; margin-left: -.6em; }
.ui-slider-horizontal .ui-slider-range { top: 0; height: 100%; }
.ui-slider-horizontal .ui-slider-range-min { left: 0; }
.ui-slider-horizontal .ui-slider-range-max { right: 0; }
.ui-widget-content { border: 1px solid <?php echo $options['colorpicker_bar_border_color_value']; ?>; background: <?php echo $options['colorpicker_bar_bg_color_value']; ?>; width:<?php echo $options['pagebar_width']; ?>; margin: auto}
.ui-state-default, .ui-widget-content .ui-state-default { border: 1px solid <?php echo $options['colorpicker_handle_bg_color_value']; ?>; background: <?php echo $options['colorpicker_handle_border_color_value']; ?>; outline: none; }
.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { text-decoration: none; outline: none; }
.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus { border: 1px solid <?php echo $options['colorpicker_handle_hover_border_color_value']; ?>; background: <?php echo $options['colorpicker_handle_hover_border_color_value']; ?>; outline: none; }
.ui-state-hover a, .ui-state-hover a:hover {text-decoration: none; outline: none; }
.ui-state-active, .ui-widget-content .ui-state-active { border: 1px solid <?php echo $options['colorpicker_handle_active_border_color_value']; ?>; background: <?php echo $options['colorpicker_handle_active_border_color_value']; ?>; outline: none; }
.ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited { outline: none; text-decoration: none; }
#pages {color:<?php echo $options['colorpicker_number_page_font_color_value']; ?>;font-weight:<?php echo $options['number_page_font_weight']; ?>;font-size:<?php echo $options['number_page_font_size']; ?>;margin:auto; text-align:center}
<?php if ($options['hide_navigation'] == on) echo ".navigation {display:none;}"; ?>
</style>

 	<?php
	
}


/*
	jp_admin_head
*/
function jp_admin_head() {
	
	if ( basename($_SERVER['REQUEST_URI']) == 'options-general.php?page=jquery_pagebar.php') {
		
		$options = jp_get_option();
		
		echo "\n";
	 
	?>
    
<style type="text/css">
div.icon32 {background: url(<?php echo plugins_url('jquery-pagebar/img/icon32.png') ?>) no-repeat;}
div.jp .postbox h3 {cursor: pointer}
div.jp h4 {text-decoration:underline}
div.jp input[type="text"] {font-size:11px;}
div.jp div.inside_first{ background: url(<?php echo plugins_url('jquery-pagebar/img/jquery_wordpress.gif') ?>) no-repeat right top;}
.ui-corner-all {-moz-border-radius: 5px;-webkit-border-radius: 5px;}
.ui-slider { position: relative; text-align: left; }
.ui-slider .ui-slider-handle { position: absolute; z-index: 2; width: 13px; height: 13px; cursor: default; }
.ui-slider .ui-slider-range { position: absolute; z-index: 1; font-size: .7em; display: block; border: 0; }
.ui-slider-horizontal { height: .8em; }
.ui-slider-horizontal .ui-slider-handle { top: -2px; margin-left: -.6em; }
.ui-slider-horizontal .ui-slider-range { top: 0; height: 100%; }
.ui-slider-horizontal .ui-slider-range-min { left: 0; }
.ui-slider-horizontal .ui-slider-range-max { right: 0; }
.ui-widget-content { border: 1px solid <?php echo $options['colorpicker_bar_border_color_value']; ?>; background: <?php echo $options['colorpicker_bar_bg_color_value']; ?>; width:400px; margin: auto}
.ui-state-default, .ui-widget-content .ui-state-default { border: 1px solid <?php echo $options['colorpicker_handle_bg_color_value']; ?>; background: <?php echo $options['colorpicker_handle_border_color_value']; ?>; outline: none; }
.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { text-decoration: none; outline: none; }
.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus { border: 1px solid <?php echo $options['colorpicker_handle_hover_border_color_value']; ?>; background: <?php echo $options['colorpicker_handle_hover_border_color_value']; ?>; outline: none; }
.ui-state-hover a, .ui-state-hover a:hover {text-decoration: none; outline: none; }
.ui-state-active, .ui-widget-content .ui-state-active { border: 1px solid <?php echo $options['colorpicker_handle_active_border_color_value']; ?>; background: <?php echo $options['colorpicker_handle_active_border_color_value']; ?>; outline: none; }
.ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited { outline: none; text-decoration: none; }
#pages {color:<?php echo $options['colorpicker_number_page_font_color_value']; ?>;font-weight:<?php echo $options['number_page_font_weight']; ?>;font-size:13px;margin:auto; text-align:center}
</style>

<link rel="stylesheet" href="<?php echo plugins_url('jquery-pagebar/colorpicker/colorpicker.css') ?>" type="text/css" />

<script src="<?php echo plugins_url('jquery-pagebar/colorpicker/colorpicker.js') ?>"></script>
<script src="<?php echo plugins_url('jquery-pagebar/js/jquery_pagebar.js') ?>"></script>

	<?php
	
	}
	
}


/*
	jp_option_page
*/
function jp_option_page() {
	
	global $wp_version, $wpdb;
	
	if ( isset($_POST['jp_submit'])) {
		
		$options = array(
						 
			'bar_bg_color' => $_POST['colorpicker_bar_bg_color'],
			'bar_border_color' => $_POST['colorpicker_bar_border_color'],
			
			'handle_bg_color' => $_POST['colorpicker_handle_bg_color'],
			'handle_border_color' => $_POST['colorpicker_handle_border_color'],
			'handle_active_bg_color' => $_POST['colorpicker_handle_active_bg_color'],
			'handle_active_border_color' => $_POST['colorpicker_handle_active_border_color'],
			'handle_hover_bg_color' => $_POST['colorpicker_handle_hover_bg_color'],
			'handle_hover_border_color' => $_POST['colorpicker_handle_hover_border_color'],
			
			'pagebar_position' => $_POST['jp_pagebar_position'],
			
			'pagebar_width' =>  $_POST['pagebar_width'],
			
			'hide_navigation' => $_POST['hide_navigation'],
			
			'number_page_font_color' => $_POST['colorpicker_number_page_font_color'],
			'number_page_font_weight' => $_POST['jp_number_page_font_weight'],
			'number_page_font_size' => $_POST['number_page_font_size'],
			
			'display_before_current' => str_replace('"','\'',$_POST['display_before_current']),
			'display_before_maxpage' => str_replace('"','\'',$_POST['display_before_maxpage']),
			'display_after_maxpage' => str_replace('"','\'',$_POST['display_after_maxpage']),
			
			'uninstall' => $_POST['jp_uninstall'],
						
						);
		
		update_option('jp_options', $options);

	?>
    <div id="message" class="updated fade"><p><strong><?php _e('Settings saved.') ?></strong></p></div>
	<?php
    } 
	
	$options = jp_get_option();

	?>
    
	<div class="wrap jp">
	<?php if ( version_compare( $wp_version, '2.6.999', '>' ) ) { ?>
		<div class="icon32"><br /></div>
	<?php } ?>
	<h2>jQuery-Pagebar</h2>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=jquery_pagebar.php">
	<?php wp_nonce_field('jp') ?>
        <div id="poststuff" class="close-me">
            <div class="postbox">
                <h3><?php _e('Settings') ?></h3>
                <div class="inside inside_first">
                    <p><h4><?php _e('Position of the jQuery-Pagebar:','jquerypagebar') ?></h4></p>
                    <div style="float:left; width:180px;">
                        <p><?php _e('Set jQuery-Pagebar on:','jquerypagebar') ?></p>
                    </div>
                    <div style="float:left; width:80px;">
                        <?php if ($options['pagebar_position'] == "end") $end = "checked"; else $start = "checked"; ?>
                        <p><label for="jp_position_start"><input type="radio" name="jp_pagebar_position" value="start" <?php echo $start; ?> id="jp_position_start"><?php _e('top','jquerypagebar') ?></label></p>
                        <p><label for="jp_position_end"><input type="radio" name="jp_pagebar_position" value="end" <?php echo $end; ?> id="jp_position_end"><?php _e('bottom','jquerypagebar') ?></label></p>
                    </div>
                    <div style="float:left; width:100px;">
                        <p>&emsp;</p>
                        <p><?php _e('of the loop.','jquerypagebar')?></p>
                    </div>
                    <div style="clear:both"></div>
                    <p><br/></p>
                    <p><h4><?php _e('Width of the jQuery-Pagebar:','jquerypagebar') ?></h4></p>
                    <p><?php _e('You can use %, px or em: ','jquerypagebar') ?><input type="text" id="pagebar_width" value="<?php echo $options['pagebar_width']; ?>" name="pagebar_width" /></p>
                    <p><br/></p>
                    <p><h4><?php _e('Colors of the jQuery-Pagebar:','jquerypagebar') ?></h4></p>
                    <div style="float:left; width:300px;">
                    <p><strong><?php _e('Colors of the bar:','jquerypagebar') ?></strong></p>
                    <table>
                        <tr>
                            <td><p><?php _e('Background color:','jquerypagebar') ?></p></td>
                            <td><p><input style="color:<?php echo $options['colorpicker_bar_bg_color_value']; ?>" type="text" id="colorpickerField1" maxlength="7" value="<?php echo $options['colorpicker_bar_bg_color_value']; ?>" name="colorpicker_bar_bg_color" /></p></td>
                        </tr>
                        <tr>
                            <td><p><?php _e('Border color:','jquerypagebar') ?></p></td>
                            <td><p><input style="color:<?php echo $options['colorpicker_bar_border_color_value']; ?>" type="text" id="colorpickerField2" maxlength="7" value="<?php echo $options['colorpicker_bar_border_color_value']; ?>" name="colorpicker_bar_border_color" /></p></td>
                        </tr>
                    </table>
                    </div>
                    <div style="float:left; width:300px;">
                    <p><strong><?php _e('Color of the slider (normal):','jquerypagebar') ?></strong></p>
                    <table>
                        <tr>
                            <td><p><?php _e('Background color:','jquerypagebar') ?></p></td>
                            <td><p><input style="color:<?php echo $options['colorpicker_handle_bg_color_value']; ?>" type="text" id="colorpickerField3" maxlength="7" value="<?php echo $options['colorpicker_handle_bg_color_value']; ?>" name="colorpicker_handle_bg_color" /></p></td>
                        </tr>
                        <tr>
                            <td><p><?php _e('Border color:','jquerypagebar') ?></p></td>
                            <td><p><input style="color:<?php echo $options['colorpicker_handle_border_color_value']; ?>" type="text" id="colorpickerField4" maxlength="7" value="<?php echo $options['colorpicker_handle_border_color_value']; ?>" name="colorpicker_handle_border_color" /></p></td>
                        </tr>
                    </table>
                    </div>
                    <div style="float:left; width:300px;">
                    <p><strong><?php _e('Color of the slider (active):','jquerypagebar') ?></strong></p>
                    <table>
                        <tr>
                            <td><p><?php _e('Background color:','jquerypagebar') ?></p></td>
                            <td><p><input style="color:<?php echo $options['colorpicker_handle_active_bg_color_value']; ?>" type="text" id="colorpickerField5" maxlength="7" value="<?php echo $options['colorpicker_handle_active_bg_color_value']; ?>" name="colorpicker_handle_active_bg_color" /></p></td>
                        </tr>
                        <tr>
                            <td><p><?php _e('Border color:','jquerypagebar') ?></p></td>
                            <td><p><input style="color:<?php echo $options['colorpicker_handle_active_border_color_value']; ?>" type="text" id="colorpickerField6" maxlength="7" value="<?php echo $options['colorpicker_handle_active_border_color_value']; ?>" name="colorpicker_handle_active_border_color" /></p></td>
                        </tr>
                    </table>
                    </div>
                    <div style="float:left; width:300px;">
                    <p><strong><?php _e('Color of the slider (hover):','jquerypagebar') ?></strong></p>
                    <table>
                        <tr>
                            <td><p><?php _e('Background color:','jquerypagebar') ?></p></td>
                            <td><p><input style="color:<?php echo $options['colorpicker_handle_hover_bg_color_value']; ?>" type="text" id="colorpickerField7" maxlength="7" value="<?php echo $options['colorpicker_handle_hover_bg_color_value']; ?>" name="colorpicker_handle_hover_bg_color" /></p></td>
                        </tr>
                        <tr>
                            <td><p><?php _e('Border color:','jquerypagebar') ?></p></td>
                            <td><p><input style="color:<?php echo $options['colorpicker_handle_hover_border_color_value']; ?>" type="text" id="colorpickerField8" maxlength="7" value="<?php echo $options['colorpicker_handle_hover_border_color_value']; ?>" name="colorpicker_handle_hover_border_color" /></p></td>
                        </tr>
                    </table>
                    </div>
                    <div style="clear:both"></div>
                    <p><br/></p>
                    <p><h4><?php _e('Style and display of the number of the page:','jquerypagebar') ?></h4></p>
                    <table>
                    	<tr>
                        	<td><p><?php _e('Color:','jquerypagebar') ?></p></td>
                            <td><p><input style="color:<?php echo $options['colorpicker_number_page_font_color_value']; ?>" type="text" id="colorpickerField9" maxlength="7" value="<?php echo $options['colorpicker_number_page_font_color_value']; ?>" name="colorpicker_number_page_font_color" /></p></td>
                        </tr>
                        <tr>
                        	<td><p><?php _e('Font-size:','jquerypagebar') ?></p></td>
                            <td><p><input type="text" id="number_page_font_size" value="<?php echo $options['number_page_font_size']; ?>" name="number_page_font_size" /></p></td>
                        </tr>
                        <tr>
                        	<td><p><?php _e('Font-weight:','jquerypagebar') ?></p></td>
                          	<?php 	if ($options['number_page_font_weight'] == "bold") $bold = "selected";
						   			elseif ($options['number_page_font_weight'] == "lighter") $lighter = "selected";
								 	elseif ($options['number_page_font_weight'] == "inherit") $inherit = "selected";
									else $normal = "selected";								 
							?>
                            <td><p> <select name="jp_number_page_font_weight">
                                        <option  <?php echo $bold; ?>>bold</option>
                                        <option  <?php echo $normal; ?>>normal</option>
                                        <option  <?php echo $lighter; ?>>lighter</option>
                                        <option  <?php echo $inherit; ?>>inherit</option>
                                    </select>
                                 </p></td>
                         </tr>
                         <tr>
                         	<td><p><?php _e('Display:','jquerypagebar') ?></p></td>
                            <td><p style="color:#ccc;">
                            	<input type="text" id="display_before_current" value="<?php echo $options['display_before_current']; ?>" name="display_before_current" /> <?php _e('{Current}','jquerypagebar') ?>  <input type="text" id="display_before_maxpage" value="<?php echo $options['display_before_maxpage']; ?>" name="display_before_maxpage" /> <?php _e('{Max Page}','jquerypagebar') ?> <input type="text" id="display_after_maxpage" value="<?php echo $options['display_after_maxpage']; ?>" name="display_after_maxpage" />
                                </p>
                            </td>
                                                        
                         </tr>
                    </table>
					<p><br/></p>
                    <div class="postbox closed" style="width:600px;">
                        <h3><?php _e('Preview','jquerypagebar') ?></h3>
                        <div class="inside">
                            <p class="preview">
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                                  <a style="left: 20%;" class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a>
                                </div>
                            </p>
                            <p><input type="button" name="preview" id="preview" class="button-secondary" value="<?php _e('Generate preview','jquerypagebar') ?>" /></p>
                        </div>
                    </div>
                    <p><h4><?php _e('Uninstall:','jquerypagebar') ?></h4></p>
                    <p><label for="uninstall"><input type="checkbox" name="jp_uninstall" <?php if ($options['uninstall'] == "on") echo "checked"; ?> id="uninstall" /> <?php _e('Remove Settings when plugin is deactivated from the "Manage Plugins" page.','jquerypagebar') ?></label><p>
                    <p><br/></p>
                    <p><h4><?php _e('Hide navigation:','jquerypagebar') ?></h4></p>
                    <p><label for="hide_navigation"><input type="checkbox" name="hide_navigation" <?php if ($options['hide_navigation'] == "on") echo "checked"; ?> id="hide_navigation" /> <?php _e('Remove standard navigation automatically.','jquerypagebar') ?></label><p>
                    <p><br/></p>
                    <p><input type="submit" name="jp_submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
                </div>
             </div>
             <div class="postbox closed">
                <h3><?php _e('About jQuery-Pagebar','jquerypagebar') ?></h3>
                <div class="inside">
                    <p><h4><?php _e('Thanks to...','jquerypagebar') ?></h4></p>
                    <p>- <?php _e('<em>Stefan Petre</em> for the jQuery Plugin <em><a href="http://www.eyecon.ro/colorpicker/">Colorpicker</a></em>.','jquerypagebar')?></p>
                    <p>- <?php _e('The <em>development team</em> from <em><a href="http://jquery.com/">jQuery</a></em> and <em><a href="http://jqueryui.com/">jQuery UI</a></em>','jquerypagebar') ?>.</p>
                    <p>- <?php _e('<em>Denis</em> for beta testing jQuery-Pagebar on his blog <em><a href="http://pixonder.de/">pixonder.de</a></em>.','jquerypagebar') ?></p>
                    <p><br/></p>
                    <p><h4><?php _e('Support','jquerypagebar') ?></h4></p>
                    <p><?php _e('If you have problems or wishes you can comment on the plugin page or write me via <a href="http://twitter.com/ocean90_EN">Twitter (EN)</a>/<a href="http://twitter.com/ocean90">Twitter (DE)</a>','jquerypagebar') ?></p>
                    <p><br/></p>
                    <p style="font-weight:bold">
					<?php	 $plugin_data = get_plugin_data( __FILE__ );
							printf('%1$s ' . __(' - The better navigation for WordPress') . ' | ' . __('Version') . ' <a href="http://ocean90.de/blog/wp-plugin-jquery-pagebar/#changelog" title="' . __('History', 'jquerypagebar') . '">%2$s</a> | ' . __('Author') . ' %3$s<br />', $plugin_data['Title'], $plugin_data['Version'], $plugin_data['Author']);?>
                  	</p>
                </div>
             </div>
        </div>
        
     </form>
     </div>
     
    
	<?php 

}

/*
	jp_option_menu
*/
function jp_option_menu() {
	
	global $wp_version;
	
	if ( current_user_can('edit_posts') && function_exists('add_submenu_page') ) {

		$menutitle = '';
		
		if ( version_compare( $wp_version, '2.6.999', '>' ) ) {
			$menutitle = '<img src="' . plugins_url('jquery-pagebar/img/icon.png') . '" alt="" width="11" height="9" />' . ' ';
		}
		$menutitle .= ('jQuery-Pagebar');
		
		add_options_page ('jQuery-Pagebar', $menutitle , 10, basename(__FILE__), 'jp_option_page');
		
		}
}

/*
	jp_install
*/
function jp_install() {
	
	$options = array(
					 
		'bar_bg_color' => '#282422',
		'bar_border_color' => '#282422',
		
		'handle_bg_color' => '#336699',
		'handle_border_color' => '#336699',
		'handle_active_bg_color' => '#cccccc',
		'handle_active_border_color' => '#cccccc',
		'handle_hover_bg_color' => '#505050',
		'handle_hover_border_color' => '#505050',
		
		'pagebar_position' => 'end',
		
		'pagebar_width' =>  '400px',
		
		'hide_navigation' => '',
		
		'number_page_font_color' => '#336699',
		'number_page_font_weight' => 'bold',
		'number_page_font_size' => '13px',
		
		'display_before_current' => '',
		'display_after_current' => '',
		'display_before_maxpage' => '',
		'display_after_maxpage' => '',
		
		'uninstall' => '',
		
	);
	
	add_option('jp_options', $options);

}

/*
	jp_uninstall
*/
function jp_uninstall() {
	
	$options = jp_get_option();
	
	if ($options['uninstall'] == "on") {
		delete_option('jp_options');
	}
}

/*
	action hooks
*/
if ( function_exists('add_action') ) {
	
	$options = jp_get_option();
	
	add_action('init', 'jp_textdomain');
	add_action("admin_head", 'jp_admin_head');
	add_action('admin_menu', 'jp_option_menu');
	add_action ('wp_head','jp_add_header_css'); 
	add_action ('wp_head','jp_add_header_js');
	
	if ($options['pagebar_position'] == "start")
		add_action ( 'loop_start', 'jp_add_loop_html' );
	else
		add_action ( 'loop_end', 'jp_add_loop_html');
		
	add_action('init', 'jp_scripts');
	
	if ($options['hide_navigation'] == "on")
		add_action('init', 'jp_hide_navigation');
		
}

/*
	filter hooks
*/
if (function_exists('add_filter')) {
	add_filter('plugin_action_links', 'jp_filter_plugin_actions', 10, 2 );
}

/*
	install and uninstall hooks
*/
register_activation_hook(__FILE__, 'jp_install');
register_deactivation_hook(__FILE__,'jp_uninstall');

?>
