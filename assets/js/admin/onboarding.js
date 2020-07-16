( function( $ ) {
	"use strict";

	// When the screen is changed.
	$( document ).on( 'click', '.ngl-boarding-change', function( event ) {
		event.preventDefault();

		var step = $( this ).attr( 'data-go-to-screen' );
		var th   = $( '.ngl-boarding:visible' );

		th.addClass( 'is-hidden' );
		$( '.ngl-boarding[data-screen=' + step + ']' ).removeClass( 'is-hidden' );

		return false;
	} );

	// Next screen
	$( document ).on( 'click', '.ngl-boarding-next.ready', function( event ) {
		event.preventDefault();

		var th = $( '.ngl-boarding:visible' ).attr( 'data-screen' );
		var next = parseInt( th ) + 1;

		$( '.ngl-boarding:visible' ).addClass( 'is-hidden' );
		$( '.ngl-boarding[data-screen=' + next + ']' ).removeClass( 'is-hidden' );

		$( '.ngl-boarding[data-screen=' + next + ']' ).find( 'input[type=text]#ngl_from_email' ).trigger( 'change' );

		$( '.ngl-boarding:visible' ).find( '.ui.dropdown, .ui.dropdown' ).dropdown();

		// No navigation here.
		if ( $( '.ngl-boarding-completed:visible' ).length ) {
			$( '.ngl-boarding-next, .ngl-boarding-prev, .ngl-boarding-skip, .ngl-boarding-close' ).hide();
		}

		$( '.ngl-boarding-next' ).removeClass( 'ready' );

		var keep_next_off = false;

		setTimeout( function() {
			$( '.ngl-metabox-flex' ).each( function() {
				if ( $( this ).hasClass( 'is-error' ) ) {
					keep_next_off = true;
				}
			} );
			if ( ! keep_next_off ) {
				$( '.ngl-boarding-next' ).removeClass( 'disabled' ).addClass( 'ready' );
			}
		}, 1500 );

		return false;
	} );

	// Previous screen
	$( document ).on( 'click', '.ngl-boarding-prev:not(.disabled)', function( event ) {
		event.preventDefault();

		var th = $( '.ngl-boarding:visible' ).attr( 'data-screen' );
		var prev = parseInt( th ) - 1;

		$( '.ngl-boarding:visible' ).addClass( 'is-hidden' );
		$( '.ngl-boarding[data-screen=' + prev + ']' ).removeClass( 'is-hidden' );

		$( '.ngl-boarding:visible' ).find( '.ui.dropdown, .ui.dropdown' ).dropdown();

		// No navigation here.
		if ( $( '.ngl-boarding-completed:visible' ).length ) {
			$( '.ngl-boarding-next, .ngl-boarding-prev, .ngl-boarding-skip, .ngl-boarding-close' ).hide();
		}

		return false;
	} );

} )( jQuery );