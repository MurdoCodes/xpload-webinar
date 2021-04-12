jQuery(function($) {
	/**
		* Pusher Declaration
	**/
	var pusherWebinar = new Pusher('a6e881af5162a58d2816', {
		cluster: 'us2'
	});
	/**
		**
		* Show/Hide Admin Video Camera/Profile Picture for subscriber
		**
	**/
	var changeVideoImage = pusherWebinar.subscribe('changevideoimage');
	changeVideoImage.bind('changevideoimageevent', function(data) {
		if(data != "video"){
			$("#xprowebinarSubscriberImage").attr("src",data);
			$('#xprowebinarSubscriberImage').css("display", "initial");		
			
	        $('.avatar-container .red5pro-media-container').css("display","none");
	        $('#xprowebinarSubscriberCamera').css("display","none");
		}else{
			$('#xprowebinarSubscriberImage').css("display","none");
	        $('.avatar-container .red5pro-media-container').css("display", "initial");
	        $('#xprowebinarSubscriberCamera').css("display", "initial");
		}
	});
	
	/**
		**
		* Subscriber Subscribing to chat and added
		* Attendees List
		**
	**/
	$(".chatboxsubscriber").css("display", "none");
	$("#join_chat").click(function(event){
		event.preventDefault();
	    onloadSubscriber();
	});
	
    function onloadSubscriber(){
		var chatUserID = $("#chatUserID").val();
	 	var chatUserName = $("#chatUserName").val();
	 	var url = pluginsURL.pluginsURL + '/xpload-webinar/php/presence_auth.php';
	    jQuery.ajax({            
            type: "POST",
            url: url,
            data: ({ userId: chatUserID, userName: chatUserName}),
            dataType: 'json',
    		cache: false,
	        success: function(response) {
	        	var html = '';
            	var newmessage = 0;
            	$.alert({
				    title: 'Congratulation!',
				    content: 'You may now start chatting!',
				});
            	$('#joinchatcontainer').hide();
            	$(".chatboxsubscriber").css("display", "block");
	        },
	        error: function(response) {
	            console.log(response);
	        }
        });
	}

	
	/**
		**
		* If Admin Pause the webinar, video will pause
		**
	**/
	
	var pauseWebinar = pusherWebinar.subscribe('pausewebinar');

	pauseWebinar.bind('pausewebinarevent', function(data) {
		$('#xprowebinar-subscriber').trigger('pause');
        $('#xprowebinarSubscriberCamera').trigger('pause');
	});


	/** 
		**
		* Set Screenshare audio to unmute
		**
	**/
	var vid = document.getElementById("xprowebinar-subscriber");
	if(vid.muted == true){
		vid.muted = false;
	}else{
		vid.muted = false;
	}
});