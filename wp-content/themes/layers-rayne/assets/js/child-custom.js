jQuery(document).ready(function($){
	
		// BXslider
	$('.bxslider').bxSlider({
		mode: 'fade',
		pager: false,
		auto: true,
		pause: 3000,
		autoHover: true,
		responsive: true,
		touchEnabled: true,
		nextText: '<i class="l-right-arrow animate"></i>',
		prevText: '<i class="l-left-arrow animate"></i>'
	});

  $('ul.bxslider').magnificPopup({
  	type:'image',
  	delegate:'a',
  	gallery: {
          enabled:true
        },
      // Delay in milliseconds before popup is removed
  removalDelay: 300,

  // Class that is added to popup wrapper and background
  // make it unique to apply your CSS animations just to this exact popup
  mainClass: 'mfp-fade'
  });

});