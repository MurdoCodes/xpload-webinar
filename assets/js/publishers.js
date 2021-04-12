jQuery(function($) {
	/**
		**
		* Pusher Declaration
		**
	**/
	var adminpusherWebinar = new Pusher('a6e881af5162a58d2816', {
	cluster: 'us2'
	});

	/**
		**
		* Show/Hide Admin Video Camera/Profile Picture
		**
	**/
	$("input[name=showContentVidPic]:radio").click(function () {
	    if ($('input[name=showContentVidPic]:checked').val() == "video") {
	        var val = $( this ).val();
	        $('#xprowebinarPublisherImage').css("display","none");
	        $('#xprowebinarPublisherCamera').css("display","inherit");

	        $.ajax({
	          type: "POST",
	          url: pluginsURL.pluginsURL + '/xpload-webinar/php/changeVideoImage.php',
	          data: { data: val },
	          success: function (response) {            
	          },
	          error: function (msg) {            
	          }
	        });

	    } else if ($('input[name=showContentVidPic]:checked').val() == "image") {
	        var val = $( this ).val();
	        var imageVal = $('#xprowebinarPublisherImage').attr('src');
	        $('#xprowebinarPublisherImage').css("display","inherit");
	        $('#xprowebinarPublisherCamera').css("display","none");

	        $.ajax({
	          type: "POST",
	          url: pluginsURL.pluginsURL + '/xpload-webinar/php/changeVideoImage.php',
	          data: { data: imageVal },
	          success: function (response) {            
	          },
	          error: function (msg) {
	          }
	        });
	    }
	});

	/**
		**
		* NOTIFICATION Sound Picker
		**
	**/
	$('#notificationSound').change(function(){
		var sourceUrl = $(this).val();
		var audio = $("#PlayNotificationSound");      
		$("#PlayNotificationSoundFile").attr("src", sourceUrl);
		audio[0].pause();
		audio[0].load();
		audio[0].play();
	});

	/**
		**
		* NOTIFICATION Text Color Picker
		**
	**/
	$("#notificationColorPicker").spectrum({
		color: "#ffffff",
		change: function(color) {
		  // $('.xprowebinarNotification .emojionearea-editor').css('color', color.toHexString());  
		},
		move: function(color) {
			// $('.xprowebinarNotification').css('color', color.toHexString());
		}
	});
	/**
		**
		* Emoji Select on Notification
		**
	**/
	$(".xprowebinarNotification").emojioneArea({
        pickerPosition: "top",
        tonesStyle: "radio"
    });
	setTimeout(notifAddEventListeners,3000);

	$('.xprowebinarNotification').tooltip({
	    trigger: "manual"
	});

	function notifAddEventListeners(){
		$('.xprowebinarNotification').on('keydown', function (e) {
			$(this).tooltip("hide");
			if ( e.which == 13 ) {
				e.preventDefault();
				if( !$(".xprowebinarNotification .emojionearea-editor").html() ) {
			        $(this).css("border","2px solid red");
					$(this).tooltip("show");
			    }else{
			    	add_notification();
			    }
		        
		    }
    	});
	}
	/**
		**
		* Send Notification
		**
	**/
    function add_notification(){
    	let textContentWithHTMLTags = document.querySelector('.xprowebinarNotification .emojionearea-editor').innerHTML; 
		let textContent = document.querySelector('.xprowebinarNotification .emojionearea-editor').innerText;	

    	var userId = $("#userID").val();
    	var UsrValue = $("#userName").val();
    	var msgStyle = document.querySelector('.sp-preview-inner').getAttribute('style');    	
    	var userName = '<span>' + UsrValue + '<span>';
    	var userMsg = $(".xprowebinarNotification").val();
    	
    	var currentdate = new Date();
    	var datetime = "Now: " + (currentdate.getMonth()+1)  + "/" 
	                + currentdate.getDate() + "/"
	                + currentdate.getFullYear() + " @ "  
	                + currentdate.getHours() + ":"  
	                + currentdate.getMinutes() + ":" 
	                + currentdate.getSeconds();
        var date = currentdate.getFullYear() + "/" + (currentdate.getMonth()+1) + "/" + currentdate.getDate();

        var soundUrlSrc = document.getElementById('PlayNotificationSoundFile'); 
    	var soundURL = soundUrlSrc.src;

    	data={
    		userId:userId,
    		userName:userName,            		
	        userMsg:textContentWithHTMLTags, textContent,
	        datetime:datetime,
	        date:date,
	        soundURL:soundURL,
	        msgStyle:msgStyle
        };

        jQuery.ajax({            
            type: "POST",
            url: pluginsURL.pluginsURL + '/xpload-webinar/php/submitNotif.php',
            data: data,
            dataType: 'json',
    		cache: false,
	        success: function(response) {
	        	$( "#notifMsgBox" ).empty();
		        $( ".xprowebinarNotification .emojionearea-editor" ).empty();
	        },
	        error: function(response) {
	            console.log(response);
	        }
        });

    }

	/**
		**
		* Show list of subscribed members
		**
	**/
	var presencechannel = adminpusherWebinar.subscribe("presence_channel");
		presencechannel.bind('presence_channel_event', function(data) {
		addMember(data);
	});

	function addMember(data){
		var userID = data.userID;
		var userName = data.userName; 
		var time = data.time;

		// toastr.success(userName + ': has connected!');
		var html = '';
		html += "<div id='subscriberOnline' style='display:flex;'><span></span><p>"+userName+"</p></div>";
		$("#member-list .list").append(html); 
	}

	$("#show_subscribers").click(function(event){
		event.preventDefault();
		$("#member-list").css('display', "flex");
		$(".user-info-in").css('display', "flex");
	    $("#member-list").animate({width:"30%"}, 200).find(".user-info-in").animate({width:"18.6vw"}, 200);   
	});

	$("#chatbuttonsubscribe").click(function(event){
		event.preventDefault();
		$("#member-list").css('display', "none")
	    $(".user-info-in").animate({width:"0%"},200);
	    $("#member-list").animate({width:"0%"},200);   
	});

	$("#member-list").click(function(event){
		event.preventDefault();
		$("#member-list").css('display', "none")
		$(".user-info-in").css('display', "none");
	    $(".user-info-in").animate({width:"0%"},200);
	    $("#member-list").animate({width:"0%"},200);      
	});

	$(".cc").click(function(e){ 
	    $("#member-list").css('display', "none")
	    $("#member-list").animate({width:"0%"},200); 	    
	});

});