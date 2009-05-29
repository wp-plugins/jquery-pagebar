<?php
/* 
Plugin Name: jQuery-Pagebar
Plugin URI: http://ocean90.de/blog/wordpress/wp-plugin-jquery-pagebar/
Description: A pagebar with jQuery
Version: 0.3.2
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
		
		'show_next_prev' => $jp_options['show_next_prev'],
		
		'load_jquery' => $jp_options['load_jquery'],
		'load_jquery_ui' => $jp_options['load_jquery_ui'],
		'script_position' => $jp_options['script_position'],
						
		'colorpicker_number_page_font_color_value' => $jp_options['number_page_font_color'],
		'number_page_font_weight' => $jp_options['number_page_font_weight'],
		'number_page_font_size' => $jp_options['number_page_font_size'],
		
		'display_before_current' => stripslashes ($jp_options['display_before_current']),
		'display_before_maxpage' => stripslashes ($jp_options['display_before_maxpage']),
		'display_after_maxpage' => stripslashes ($jp_options['display_after_maxpage']),
						
		'uninstall' => $jp_options['uninstall'],
		
		'test' => $jp_options['test'],
	
	);
	
	return $jp_get_option_array;
	
}


/*
	jp_script_jquery
*/
function jp_script_jquery() {
	
	wp_deregister_script('jquery');
	wp_register_script('jquery', (plugins_url('jquery-pagebar/js/jquery.js')), false, '1.3.2');
	
}


/*
	jp_script_jquery_ui
*/
function jp_script_jquery_ui() {
	
	wp_deregister_script('jquery-ui-core');
	wp_register_script('jquery-ui-core', ( plugins_url('jquery-pagebar/js/jquery_ui_slider.js')), false, '1.7.1',true);
		
}  


/*
	jp_hide_navigation
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
	jp_add__js
*/
function jp_add_js() {
	
	global $paged, $wp_query;
	
	$options = jp_get_option();
	
	if (is_home() or is_search() or is_archive()) {	 
				
		$url = get_pagenum_link($paged);
		$url = preg_replace("/page([\/0-9]+)/ie","",$url);
		$url = preg_replace("/\&paged=([0-9]+)/ie","",$url);
		$url = preg_replace("/\?paged=([0-9]+)/ie","",$url);

		if(get_option('permalink_structure') != "") {
			if (is_search()) {
				$loc_href =  '\''. $url .'&paged=\'+ ui.value ;';
			}else {
				$loc_href =  '\''. $url .'?paged=\'+ ui.value ;';
			}
		} else {
			if (is_search() or is_archive()) {	 
				$loc_href =  '\''. $url .'&paged=\'+ ui.value ;';
			} else {
				$loc_href =  '\''. $url .'/?paged=\'+ ui.value ;';
			}
		} 

		$dbc = addslashes ($options['display_before_current']);
		$dam = addslashes ($options['display_after_maxpage']);
		$dbm = addslashes ($options['display_before_maxpage']);

			
		if (($dbm and $dam and $dbc) == "")
		 	$dbm= "/";
	
		echo "\n";	
	
		?>
    
<script type="text/javascript">
 //<![CDATA[
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
			jQuery("#number").empty().append('<?php echo $dbc; ?>'+ ui.value + '<?php echo $dbm, $max_page, $dam; ?>');
		}
		
	});
	jQuery("#number").empty().append('<?php echo $dbc; ?>'+jQuery("#slider").slider("value") +'<?php echo $dbm, $max_page, $dam; ?>');
});
//]]>
</script>

    <?php
	
	echo "\n";
	
	}
	
}


/*
	jquery-pagebar()
*/
function jquery_pagebar() {
	
	jp_add_loop_html();
}


/*
	 jp_add_loop_html
*/
function jp_add_loop_html() {
	
	global $paged, $wp_query;
	
	if (is_home() or is_search() or is_archive()) {	 
	
		$options = jp_get_option();
		
		$max_page = $wp_query->max_num_pages;
		
		if($max_page > 1 && strpos(TEMPLATEPATH, 'wptouch') === false) { 
			if ($options['show_next_prev'] == "on")	{
				if ($paged > 1) {
					$loc_href_prev = '<a href="' .get_pagenum_link($paged-1) .'" title="' . __('Newer Entries','jquerypagebar') . '" >&laquo;</a>';
				}
				
				if ($paged != $max_page) {
					if ($paged < 1) $paged = 1;
						$loc_href_next = '<a href="'.get_pagenum_link($paged+1).'" title="' . __('Older Entries','jquerypagebar') . '" >&raquo;</a>';
				}
			}
		?>
		<div id="jquery_pagebar">
		
			<div id="pages"> <?php echo $loc_href_prev; ?> <span id="number"> <?php _e('Navigation','jquerypagebar'); ?></span> <?php echo $loc_href_next; ?></div>
			<div id="slider"></div>
		</div>
		
		<?php
		
		}
	}
}


/* 
	jp_add_header_css 
*/
function jp_add_header_css() {
	
	$options = jp_get_option();
		
	if (is_home() or is_search() or is_archive()) {	
	
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
.ui-widget-content { border: 1px solid <?php echo $options['colorpicker_bar_border_cologr_value']; ?>; background: <?php echo $options['colorpicker_bar_bg_color_value']; echo ' url('. $options['bar_image'].')'; ?> 0 0 repeat-x; width:<?php echo $options['pagebar_width']; ?>; margin: auto}
.ui-state-default, .ui-widget-content .ui-state-default { border: 1px solid <?php echo $options['colorpicker_handle_bg_color_value']; ?>; background: <?php echo $options['colorpicker_handle_border_color_value']; ?>; outline: none; }
.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { text-decoration: none; outline: none; }
.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus { border: 1px solid <?php echo $options['colorpicker_handle_hover_border_color_value']; ?>; background: <?php echo $options['colorpicker_handle_hover_border_color_value']; echo ' url('. $options['slider_image'] .')'; ?> 0 50% repeat-x; outline: none; }
.ui-state-hover a, .ui-state-hover a:hover {text-decoration: none; outline: none; }
.ui-state-active, .ui-widget-content .ui-state-active { border: 1px solid <?php echo $options['colorpicker_handle_active_border_color_value']; ?>; background: <?php echo $options['colorpicker_handle_active_border_color_value']; ?>; outline: none; }
.ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited { outline: none; text-decoration: none; }
#jquery_pagebar { padding: 10px 0 10px 0;}
#pages {color:<?php echo $options['colorpicker_number_page_font_color_value']; ?>;font-weight:<?php echo $options['number_page_font_weight']; ?>;font-size:<?php echo $options['number_page_font_size']; ?>;margin:auto; text-align:center;}
#pages a {color:<?php echo $options['colorpicker_number_page_font_color_value']; ?>;font-weight:<?php echo $options['number_page_font_weight']; ?>;font-size:<?php echo $options['number_page_font_size']; ?>; text-decoration:none; outline: none; }
<?php if ($options['hide_navigation'] == on) echo ".navigation {display:none;}\n"; ?>
</style>

 		<?php
	}
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
div.jp div.inside_first{ background: url(<?php echo plugins_url('jquery-pagebar/img/jquery_pagebar_logo.jpg') ?>) no-repeat right top;}
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
.manuell_code {background:#FFFBCC; border: 1px solid #E6DB55;height:80px;-moz-border-radius-bottomleft:3px;-moz-border-radius-bottomright:3px;-moz-border-radius-topleft:3px;-moz-border-radius-topright:3px;padding:0 0.6em; display:none;margin-top:-5px;}
input[type="radio"] {vertical-align: text-top}
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
	
	if (isset($_POST['jp_reset'])) {
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
		
		'show_next_prev' => 'on',
		
		'load_jquery' => 'on',
		'load_jquery_ui' => 'on',
		'script_position' => 'head',
		
		'number_page_font_color' => '#336699',
		'number_page_font_weight' => 'bold',
		'number_page_font_size' => '13px',
		
		'display_before_current' => '',
		'display_after_current' => '',
		'display_before_maxpage' => '',
		'display_after_maxpage' => '',
		
		'uninstall' => '',
		
	);
	
	update_option('jp_options', $options);
	
	?>
    <div id="message" class="updated fade"><p><strong><?php _e('Settings reset.') ?></strong></p></div>
    
    <?php
    } 
	
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
			
			'show_next_prev' => $_POST['show_next_prev'],
			
			'load_jquery' => $_POST['load_jquery'],
			'load_jquery_ui' => $_POST['load_jquery_ui'],
			'script_position' => $_POST['script_position'],
			
			'number_page_font_color' => $_POST['colorpicker_number_page_font_color'],
			'number_page_font_weight' => $_POST['jp_number_page_font_weight'],
			'number_page_font_size' => $_POST['number_page_font_size'],
			
			'display_before_current' => str_replace('"','\'',$_POST['display_before_current']),
			'display_before_maxpage' => str_replace('"','\'',$_POST['display_before_maxpage']),
			'display_after_maxpage' => str_replace('"','\'',$_POST['display_after_maxpage']),
			
			'uninstall' => $_POST['jp_uninstall'],
						
						);
		
		update_option('jp_options', $options);
		
		if(($_POST['load_jquery'] == "") or ($_POST['load_jquery_ui']) == "") {
			?>
            <div id="message" class="error fade" style="background-color: #fc7979 !important;"><p><strong><?php _e('Attention, you have the loading of jQuery disabled. Please check if jQuery is loaded differently, otherwise the jQuery-Pagebar is not displayed.','jquerypagebar') ?></strong></p></div>
            <?php }
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
                    <div style="float:left; width:140px;">
                        <p><?php _e('Set jQuery-Pagebar','jquerypagebar') ?></p>
                    </div>
                    <div style="float:left; width:120px;">
                        <?php if ($options['pagebar_position'] == "start")
								$start = "checked"; 
							  elseif ($options['pagebar_position'] == "manuell") 
							  	$manuell = "checked"; 
							  else  
							  	$end = "checked"; 
						?>
                        <p><label for="jp_position_start"><input type="radio" name="jp_pagebar_position" value="start" <?php echo $start; ?> id="jp_position_start"><?php _e(' on top.','jquerypagebar') ?></label></p>
                        <p><label for="jp_position_end"><input type="radio" name="jp_pagebar_position" value="end" <?php echo $end; ?> id="jp_position_end"><?php _e(' on bottom.','jquerypagebar') ?></label></p>
                         <p><label for="jp_position_manuell"><input type="radio" name="jp_pagebar_position" value="manuell" <?php echo $manuell; ?> id="jp_position_manuell"><?php _e(' manually.','jquerypagebar') ?></label></p>
                    </div>
                    <div style="float:left; width:200px;"  class="manuell_code">
                    	<?php _e('<p><strong>You choose manuelly,</strong><br />
                        please copy following code and paste it in your theme.</p>','jquerypagebar') ?>
                    	<p><pre><code>&lt;?php jquery_pagebar(); ?&gt;</code></pre></p>
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
                    <div class="postbox closed" style="width:600px;">
                        <h3><?php _e('Preview','jquerypagebar') ?></h3>
                        <div class="inside">
                            <p class="preview">
                                <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                                  <a style="left: 20%;" class="ui-slider-handle ui-state-default ui-corner-all" ></a>
                                </div>
                            </p>
                            <p><input type="button" name="preview" id="preview" class="button-secondary" value="<?php _e('Generate preview','jquerypagebar') ?>" /></p>
                        </div>
                    </div>
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
                    <p><h4><?php _e('Load jQuery and jQuery UI with Slider:','jquerypagebar') ?></h4></p>
                    <p><label for="load_jquery"><input type="checkbox" name="load_jquery" <?php if ($options['load_jquery'] == "on") echo "checked"; ?> id="load_jquery" /> <?php _e('Load jQuery from the plugin dir. It is version 1.3.2.','jquerypagebar') ?></label><p>
                    <p><label for="load_jquery_ui"><input type="checkbox" name="load_jquery_ui" <?php if ($options['load_jquery_ui'] == "on") echo "checked"; ?> id="load_jquery_ui" /> <?php _e('Load jQuery UI with Slider from the plugin dir. It is version 1.7.1. <font color="red">This is important, without it jQuery-Pagebar doesn\'t work!</font>','jquerypagebar') ?></label><p>
                     <p><?php _e('Load jQuery-Pagebar script in','jquerypagebar') ?>
                     <?php if ($options['script_position'] == "footer")
								$footer = "checked"; 
							  else  
							  	$head = "checked"; 
						?>
                    <label for="script_position_footer"><input type="radio" name="script_position" value="footer" <?php echo $footer; ?> id="script_position_footer"><?php _e( ' footer','jquerypagebar') ?></label><?php _e(' or in ','jquerypagebar') ?>
                    <label for="script_position_head"><input type="radio" name="script_position" value="head" <?php echo $head; ?> id="script_position_head"><?php _e(' head.','jquerypagebar') ?></label></p>
                    <p><br/></p>
                    <p><h4><?php _e('Navigation:','jquerypagebar') ?></h4></p>
                    <p><label for="hide_navigation"><input type="checkbox" name="hide_navigation" <?php if ($options['hide_navigation'] == "on") echo "checked"; ?> id="hide_navigation" /> <?php _e('Remove standard WordPress navigation automatically.','jquerypagebar') ?></label><p>
                    <p><label for="show_next_prev"><input type="checkbox" name="show_next_prev" <?php if ($options['show_next_prev'] == "on") echo "checked"; ?> id="show_next_prev" /> <?php _e('Show linked arrows to the next or previous page before an after the page number.  <font color="red">You should activated this option for visitors who had deactivated javascript.</font>','jquerypagebar') ?></label>
                    </p>
                    <p><br/></p>
                    <p><h4><?php _e('Uninstall:','jquerypagebar') ?></h4></p>
                    <p><label for="uninstall"><input type="checkbox" name="jp_uninstall" <?php if ($options['uninstall'] == "on") echo "checked"; ?> id="uninstall" /> <?php _e('Remove Settings when plugin is deactivated from the "Manage Plugins" page.','jquerypagebar') ?></label><p>
                    <p><br/></p>
                    <p><input type="submit" name="jp_submit" class="button-primary" value="<?php _e('Save Changes') ?>" /> <input type="submit" name="jp_reset" class="button-secondary" value="<?php _e('Reset Options','jquerypagebar') ?>" /></p>
                </div>
             </div>
             <div class="postbox">
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
		
		'hide_navigation' => 'on',
		
		'show_next_prev' => 'on',
		
		'load_jquery' => 'on',
		'load_jquery_ui' => 'on',
		'script_position' => 'head',
		
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
	

	if ($options['script_position'] == "footer")
		add_action ('wp_footer','jp_add_js');
	else 
		add_action ('wp_head','jp_add_js');
		
	if ($options['pagebar_position'] == "start")
		add_action ( 'loop_start', 'jp_add_loop_html' );
	elseif ($options['pagebar_position'] == "end")
		add_action ( 'loop_end', 'jp_add_loop_html');
	
	if (!is_admin()) { 
		if ($options['load_jquery'] == "on")	
			add_action('init', 'jp_script_jquery');
		if ($options['load_jquery_ui'] == "on")	
			add_action('init', 'jp_script_jquery_ui');
	}
	
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

/*
	load jquery
*/
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');

?>