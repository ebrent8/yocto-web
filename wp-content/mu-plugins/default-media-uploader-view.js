( function( $, _ ) {

	var media = wp.media;

	if ( media ) {

		media.view.MediaFrame.Select.prototype.initialize = function() {

			media.view.MediaFrame.prototype.initialize.apply( this, arguments );

			_.defaults( this.options, {
				selection: [],
				library: { 
							uploadedTo: media.view.settings.post.id, 
							orderby: 'menuOrder', 
							order: 'ASC' 
				},
				multiple: false,
				state: 'library'
			});

			this.createSelection();
			this.createStates();
			this.bindHandlers();
			
			this.states.forEach(function( state ) {
                var library = state.get('library');
                if ( library ) {
                    library.props.set( 'uploadedTo', media.view.settings.post.id );
                    library.props.set( 'orderby', 'menuOrder' );
                    library.props.set( 'order', 'ASC' );
                }
            });

		};
		
		media.controller.FeaturedImage.prototype.initialize = function() {

			var library, comparator;

			if ( ! this.get('library') ) {
				this.set( 'library', media.query( { 
												type: 'image', 
												uploadedTo: media.view.settings.post.id, 
												orderby: 'menuOrder', 
												order: 'ASC' 
											} ) );
			}

			media.controller.Library.prototype.initialize.apply( this, arguments );

			library    = this.get('library');
			comparator = library.comparator;

			library.comparator = function( a, b ) {
				var aInQuery = !! this.mirroring.get( a.cid ),
					bInQuery = !! this.mirroring.get( b.cid );

				if ( ! aInQuery && bInQuery ) {
					return -1;
				} else if ( aInQuery && ! bInQuery ) {
					return 1;
				} else {
					return comparator.apply( this, arguments );
				}
			};

			library.observe( this.get('selection') );
		};
		
	}

}(jQuery, _));