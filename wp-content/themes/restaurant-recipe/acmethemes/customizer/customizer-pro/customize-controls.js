( function( api ) {

	// Extends our custom "restaurant-recipe" section.
	api.sectionConstructor['restaurant-recipe'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );