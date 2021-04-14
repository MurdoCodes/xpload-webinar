jQuery(function($) {
	$(window).on('load', function() {
	    $('#modalUserName').modal({
	        backdrop: 'static',
	        keyboard: false
	    })
	});

	$('#saveUserName').on('click', function(){
		var chatusername = $( "#modalUserNameInput" ).val();
		if(chatusername != ''){
			$("#chatUserName").val(chatusername);
			$('#modalUserName').modal('hide');
		}else{
			$('#modalUserNameInput').css('border', '1px solid red');
		}
	});
});