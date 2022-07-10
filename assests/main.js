/*global woocommerce_admin_meta_boxes */
jQuery( function( $ ) {
	// Attribute Tables.

	// Initial order.
	var woocommerce_aff_items = $( '.product_aff_cf' ).find( '.woocommerce_aff' ).get();

	woocommerce_aff_items.sort( function( a, b ) {
	   var compA = parseInt( $( a ).attr( 'rel' ), 10 );
	   var compB = parseInt( $( b ).attr( 'rel' ), 10 );
	   return ( compA < compB ) ? -1 : ( compA > compB ) ? 1 : 0;
	});

	$( woocommerce_aff_items ).each( function( index, el ) {
		$( '.product_aff_cf' ).append( el );
	});

	function aff_row_indexes() {
		$( '.product_aff_cf .woocommerce_aff' ).each( function( index, el ) {
			$( '.aff_position', el ).val( parseInt( $( el ).index( '.product_aff_cf .woocommerce_aff' ), 10 ) );
		});
	}

	// Add rows.
	$( 'button.add_attribute' ).on( 'click', function() {
		var size         = $( '.product_aff_cf .woocommerce_aff' ).length;
		var $wrapper     = $( this ).closest( '#product_aff_cf' );
		var $attributes  = $wrapper.find( '.product_aff_cf' );
		var product_type = $( 'select#product-type' ).val();
		var data         = {
			action:   'woocommerce_add_aff',
			i:        size,
			security: woocommerce_admin_meta_boxes.add_aff_nonce
		};

		$wrapper.block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		$.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {
			$attributes.append( response );

			$( document.body ).trigger( 'wc-enhanced-select-init' );

			aff_row_indexes();

			$attributes.find( '.woocommerce_aff' ).last().find( 'h3' ).trigger( 'click' );

			$wrapper.unblock();

			$( document.body ).trigger( 'woocommerce_added_aff' );
		});

		return false;
	});

	$( '.product_aff_cf' ).on( 'blur', 'input.aff_name', function() {
		$( this ).closest( '.woocommerce_aff' ).find( 'strong.aff_name' ).text( $( this ).val() );
	});

	$( '.product_aff_cf' ).on( 'click', '.remove_row', function() {
		if ( window.confirm( woocommerce_admin_meta_boxes.remove_aff ) ) {
			var $parent = $( this ).parent().parent();

			$parent.find( 'select, input[type=text]' ).val( '' );
			$parent.hide();
			aff_row_indexes();
		}
		return false;
	});

	// Attribute ordering.
	$( '.product_aff_cf' ).sortable({
		items: '.woocommerce_aff',
		cursor: 'move',
		axis: 'y',
		handle: 'h3',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'wc-metabox-sortable-placeholder',
		start: function( event, ui ) {
			ui.item.css( 'background-color', '#f6f6f6' );
		},
		stop: function( event, ui ) {
			ui.item.removeAttr( 'style' );
			aff_row_indexes();
		}
	});

	// Save attributes and update variations.
	$( '.save_aff_cf' ).on( 'click', function() {

		$( '.product_aff_cf' ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var original_data = $( '.product_aff_cf' ).find( 'input, select, textarea' );
		var data = {
			post_id     : woocommerce_admin_meta_boxes.post_id,
			product_type: $( '#product-type' ).val(),
			data        : original_data.serialize(),
			action      : 'woocommerce_save_attributes',
			security    : woocommerce_admin_meta_boxes.save_attributes_nonce
		};

		$.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {
			if ( response.error ) {
				// Error.
				window.alert( response.error );
			} else if ( response.data ) {
				// Success.
				$( '.product_aff_cf' ).html( response.data.html );
				$( '.product_aff_cf' ).unblock();

				// Hide the 'Used for variations' checkbox if not viewing a variable product
				show_and_hide_panels();

				// Make sure the dropdown is not disabled for empty value attributes.
				$( 'select.attribute_taxonomy' ).find( 'option' ).prop( 'disabled', false );

				$( '.product_aff_cf .woocommerce_aff' ).each( function( index, el ) {
					if ( $( el ).css( 'display' ) !== 'none' && $( el ).is( '.taxonomy' ) ) {
						$( 'select.attribute_taxonomy' )
							.find( 'option[value="' + $( el ).data( 'taxonomy' ) + '"]' )
							.prop( 'disabled', true );
					}
				});

				// Reload variations panel.
				var this_page = window.location.toString();
				this_page = this_page.replace( 'post-new.php?', 'post.php?post=' + woocommerce_admin_meta_boxes.post_id + '&action=edit&' );

				$( '#variable_product_options' ).load( this_page + ' #variable_product_options_inner', function() {
					$( '#variable_product_options' ).trigger( 'reload' );
				} );
			}
		});
	});
});
