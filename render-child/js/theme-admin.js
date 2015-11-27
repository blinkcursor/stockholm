jQuery(document).ready(function($) {

	$body = $('body');

/* ==========================================================================
   add datepicker when posting event
   ========================================================================== */

	if ( $body.hasClass('wp-admin') && $body.hasClass('post-type-event') ) {

		$("input[name='event_starts']").datepicker({
			dateFormat: "yy-mm-dd"
		});
	}

});
