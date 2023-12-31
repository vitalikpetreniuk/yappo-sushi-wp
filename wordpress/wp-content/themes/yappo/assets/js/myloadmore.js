jQuery(function($){ // use jQuery code inside this to avoid "$ is not defined" error
	$('.misha_loadmore').click(function(){

		var button = $(this),
			data = {
				'action': 'loadmore1',
				'query': $('#row').data('posts'), // that's how we get params from wp_localize_script() function
				'page' : $('#row').data('current_page')
			};

		$.ajax({ // you can also use $.post here
			url : $('#row').data('ajaxurl'), // AJAX handler
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('Loading...'); // change the button text, you can also add a preloader image
			},
			success : function( data ){
				if( data ) {
					button.text( 'More posts' );
					$('#row').append(data); // insert new posts
					$('#row').data('current_page++');

					if ( $('#row').data('current_page') == $('#row').data('max_page')) {
						button.remove(); // if last page, remove the button
					}


					// you can also fire the "post-load" event here if you use a plugin that requires it
					// $( document.body ).trigger( 'post-load' );
				} else {
					button.remove(); // if no data, remove the button as well
				}
			}
		});
	});
});