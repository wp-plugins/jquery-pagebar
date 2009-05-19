$(document).ready(function() {
jQuery('.postbox h3').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
jQuery('.ui-slider').hide();
jQuery('#preview').click(function() {
	jQuery('.ui-slider').hide();
	jQuery('.ui-widget-content').css({'background-color' : jQuery('#colorpickerField1').val(),'border-color' : jQuery('#colorpickerField2').val()});
	jQuery('.ui-state-default').css({'background-color' : jQuery('#colorpickerField3').val(),'border-color' : jQuery('#colorpickerField4').val()});
	jQuery('.ui-state-default').click(function(){jQuery(this).css({'background-color' : jQuery('#colorpickerField5').val(),'border-color' : jQuery('#colorpickerField6').val()});});
    jQuery('.ui-state-default').hover(
      function () {
      	 jQuery(this).css({'background-color' : jQuery('#colorpickerField7').val(),'border-color' : jQuery('#colorpickerField8').val()});
      }, 
      function () {
            jQuery(this).css({'background-color' : jQuery('#colorpickerField3').val(),'border-color' : jQuery('#colorpickerField4').val()}); 
      }
    );
	jQuery('.ui-slider').animate({opacity: "show"}, "slow");
});
jQuery('#jp_position_manuell,#jp_position_end,#jp_position_start').click( function() { 
if (jQuery("#jp_position_manuell").attr("checked")) {
    jQuery('.manuell_code').animate({opacity: "show"}, "fast");
} else {
	jQuery('.manuell_code').animate({opacity: "hide"}, "fast");
}
});
jQuery('#colorpickerField1').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#colorpickerField1').val('#'+hex)
									.css('color','#'+hex);
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#colorpickerField1').val('#'+hex)
									.css('color','#'+hex);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
jQuery('#colorpickerField2').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#colorpickerField2').val('#'+hex)
									.css('color','#'+hex);
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#colorpickerField2').val('#'+hex)
									.css('color','#'+hex);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
jQuery('#colorpickerField3').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#colorpickerField3').val('#'+hex)
									.css('color','#'+hex);
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#colorpickerField3').val('#'+hex)
									.css('color','#'+hex);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
jQuery('#colorpickerField4').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#colorpickerField4').val('#'+hex)
									.css('color','#'+hex);	
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#colorpickerField4').val('#'+hex)
									.css('color','#'+hex);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
jQuery('#colorpickerField5').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#colorpickerField5').val('#'+hex)
									.css('color','#'+hex);	
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#colorpickerField5').val('#'+hex)
									.css('color','#'+hex);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
jQuery('#colorpickerField6').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#colorpickerField6').val('#'+hex)
									.css('color','#'+hex);	
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#colorpickerField6').val('#'+hex)
									.css('color','#'+hex);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
jQuery('#colorpickerField7').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#colorpickerField7').val('#'+hex)
									.css('color','#'+hex);	
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#colorpickerField7').val('#'+hex)
									.css('color','#'+hex);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
jQuery('#colorpickerField8').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#colorpickerField8').val('#'+hex)
									.css('color','#'+hex);	
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#colorpickerField8').val('#'+hex)
									.css('color','#'+hex);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
jQuery('#colorpickerField9').ColorPicker({
	onSubmit: function(hsb, hex, rgb) {
		jQuery('#colorpickerField9').val('#'+hex)
									.css('color','#'+hex);	
	},
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onBeforeShow: function () {
		jQuery(this).ColorPickerSetColor(this.value);
	},
	onChange: function(hsb, hex, rgb) {
		jQuery('#colorpickerField9').val('#'+hex)
									.css('color','#'+hex);
	}
})
.bind('keyup', function(){
	jQuery(this).ColorPickerSetColor(this.value);
});
});