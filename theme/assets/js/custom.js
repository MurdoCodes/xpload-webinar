jQuery(document).ready(function( $ ){
	// Append Button On Image Carousel
	$("div[data-swiper-slide-index='1']").appendTo( "<a class='image-carousel-button' id='carousel-image-1' href='#''>Access <i class='fas fa-angle-right'></i></a>" );
	// Pop Up On Click
	pop_up_open = function(e) {
		e.preventDefault();
		elementorProFrontend.modules.popup.showPopup( { id: 138167 } );
	}
	// Show more list
	$( "#show-more-list-one ul li:nth-child(n+6)" ).toggle("slow");
	$( "#show-more-list-two ul li:nth-child(n+6)" ).toggle("slow"); 
	$('#forex-one-show-more').click(function () {      
	$( "#show-more-list-one ul li:nth-child(n+6)" ).toggle("slow");      
		var text = $('#forex-one-show-more').text();
		$('#forex-one-show-more').text(
		text == "Read Less" ? "Show More" : "Read Less");
	});
	$('#forex-two-show-more').click(function () {      
	$( "#show-more-list-two ul li:nth-child(n+6)" ).toggle("slow");      
		var text = $('#forex-two-show-more').text();
		$('#forex-two-show-more').text(
		text == "Read Less" ? "Show More" : "Read Less");
	});
	// Newsletter Text
	jQuery(document).ready(function( $ ){
	    $( "#footer-claim-free #header-text" ).text( "Instant Download" );
		$( "#footer-claim-free #header-text" ).append( "<p id='sub-header'>Download our Free Traders Guide To Success</p>" );
	});
	// Adding Pulse
	$(".owl-item").hover(
		function () {
			$(this).addClass("pulse");
		},
		function () {
			$(this).removeClass("pulse");
		}
	);
	// Read More
	$( ".elementor-604 .elementor-element.elementor-element-3c38254 > .elementor-widget-container article.post-9 a" ).click(function( event ) {
		event.preventDefault();
		$( ".elementor-post__excerpt" ).toggle("slow");
	});

	// Header Banner
	$("#header-link2").click(function(){
      //this will find the selected website from the dropdown
      var go_to_url = "https://specials.livetraders.com/all-deals-page-2020";
      
      //this will redirect us in new tab
      window.open(go_to_url, '_blank');
      // open on same page
      // window.location.href = go_to_url
   });

	// document.querySelector("#header-link2").addEventListener("click", function() {
	// 	window.location.href = "https://specials.livetraders.com/all-deals-page-2020";
	// });
});