/* Simple X Buttons – popup window handler */
( function () {
	'use strict';

	var W = 600, H = 360;

	function openPopup( url ) {
		var left = Math.round( ( window.screen.width - W ) / 2 );
		var top  = Math.round( ( window.screen.height - H ) / 2 );
		window.open( url, 'sxb_intent', 'width=' + W + ',height=' + H + ',left=' + left + ',top=' + top + ',scrollbars=yes,resizable=yes' );
	}

	document.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest( '[data-sxb-popup="1"]' );
		if ( ! btn ) return;
		e.preventDefault();
		openPopup( btn.href );
	} );
}() );
