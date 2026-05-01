function colorPickerInit () {
	jQuery('.simple-twitter-widget .stp-colorholder').each(function() {
		var c = jQuery(this).parent('.stp-colorpicker');
		var w = jQuery(this).next('.stp-colorwheel');
		var f = jQuery(w).farbtastic(this);
	});
}

function colorPickerShow (el) {
	var c = jQuery(el).parent('.stp-colorpicker');
	var w = jQuery(el).next('.stp-colorwheel');
	var f = jQuery(w).farbtastic(el);

	jQuery(w).show();
	jQuery(w).mouseleave(function(){
		jQuery(this).hide();
    });
}