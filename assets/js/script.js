jQuery(function($) {
	/**
		**
		* Page Loader
		**
	**/
	jQuery(window).load(function () {
	    setTimeout(function () {
	        $('.loader').fadeOut('slow');
	    }, 1000);
	});
	/**
		* Pusher Declaration
	**/
	var pusherWebinar = new Pusher('a6e881af5162a58d2816', {
		cluster: 'us2'
	});
	/**
		** 
		* Display Notification To Everyone		
		**
	**/
	var notificationchannel = pusherWebinar.subscribe('notificationChannel');
	notificationchannel.bind('notificationEvent', function(data) {		
		
		var html = '';
		data.forEach(function(data) {
			var fontsize = $('#fontSizeSelect').val();
			var notif_id = data.xpload_notif_id;
    		var userId = data.xpload_notif_uid;
        	var userName = data.xpload_notif_name;	
        	var userMsg = data.xpload_notif_messages;	        	        	
        	var datetime = data.xpload_notif_datetime;
        	var currentDate = data.xpload_notif_date;
        	var audioSrc = data.xpload_notif_audiosrc;
        	var notifColor = data.xpload_notif_color;

        	toastr.success(userName + ': ' +userMsg);
        	PlayNotificationSound(audioSrc)
        	
        	html = "<li class='actual_msg' id='notif_msg_"+notif_id+"' style='text-align:left;float:left;background: #ffffff;width:90%;"+notifColor+"font-size:"+fontsize+"px;'><section><strong style='text-transform:capitalize;font-weight:900;font-size:.8vw;'>"+userName+"</strong><p style='display:block;margin-top:1%;margin-bottom:1%;font-weight:400;' id='userMsg'>"+userMsg+"</p><span class='date'>"+datetime+"</span><i class='fa fa-trash deleteNotif' id='"+notif_id+"' style='margin-left: .2vw;'></i></section></li>";
    	});

    	$("#xprowebinarNotificationMsgBox").append(html);
		$("#xprowebinarNotification").val('');
		$("#xprowebinarNotificationMsgBox").animate({scrollTop: $("#xprowebinarNotificationMsgBox").get(0).scrollHeight},900);
    });

	/**
		* Notification Mute/Unmute
	**/
	var notifSoundIcon = $('.notifSound');
	notifSoundIcon.on('click', function() {
	  var id = $(this).attr('id');
	  if(id == 'notif-volume-up'){
	    $('#notif-volume-up').addClass("xploadhide");
	    $('#notif-volume-up').removeClass("xploadshow");
	    $('#notif-volume-mute').removeClass("xploadhide");
	    $('#notif-volume-mute').addClass("xploadshow");
	    var element = document.getElementById('PlayNotificationSound');
	  	element.muted = true;
	  }else if(id == 'notif-volume-mute'){
	    $('#notif-volume-up').addClass("xploadshow");
	    $('#notif-volume-up').removeClass("xploadhide");
	    $('#notif-volume-mute').removeClass("xploadshow");
	    $('#notif-volume-mute').addClass("xploadhide");
	    var element = document.getElementById('PlayNotificationSound');
	  	element.muted = false;          
	  }
	}); 

	/**
		**
		* CHAT
		* Minimize/Maximize Chat Box
		**
	**/
	/** Calling Pusher Chat to subscriber **/
	pusherChat();

	$(".emojionearea-editor").addClass("style-4");
	var $chatbox = $('.chat-container'),
        $chatboxTitle = $('.chatbox__title__tray'),
        $videoContainer = $('.xploadvideo-container'),
        $chatbox2 = $('#chatbox');

    // $chatboxTitle.on('click', function() {
    //     $chatbox.toggleClass('chatbox--tray');
    //     $videoContainer.toggleClass('xploadvideomaxwidth-container');
    //     $chatbox2.toggleClass('chatboxmaxheight');
    // });

    // $chatbox.on('transitionend', function() {
    //     if ($chatbox.hasClass('chatbox--closed')) $chatbox.remove();
    // });

    /** Mute/Unmute Chat Sound **/
    var chatSoundIcon = $('.chatSound');
	chatSoundIcon.on('click', function() {
        var chatid = $(this).attr('id');
        if(chatid == 'chat-volume-up'){
        	$('#chat-volume-up').addClass("xploadhide");
        	$('#chat-volume-up').removeClass("xploadshow");
        	$('#chat-volume-mute').removeClass("xploadhide");
        	$('#chat-volume-mute').addClass("xploadshow");
        	var element = document.getElementById('PlayChatSound');
    		element.muted = true;
        }else if(chatid == 'chat-volume-mute'){
        	$('#chat-volume-up').addClass("xploadshow");
        	$('#chat-volume-up').removeClass("xploadhide");
        	$('#chat-volume-mute').removeClass("xploadshow");
        	$('#chat-volume-mute').addClass("xploadhide");
        	var element = document.getElementById('PlayChatSound');
    		element.muted = false;        	
        }
    });

    /** 
    	** 
    	* Admin sends message to chatbox
    	**
    **/
    $(".msg_box").emojioneArea({
        pickerPosition: "top",
        tonesStyle: "radio"
    });
    setTimeout(addEventListeners,3000);

    function addEventListeners(){
        $('.msg_box').on('keydown', function (e) {
        	let textContentWithHTMLTags = document.querySelector('.msg_box .emojionearea-editor').innerHTML; 
			let textContent = document.querySelector('.msg_box .emojionearea-editor').innerText;		

			var chatUserID = $("#chatUserID").val();
	    	var chatUserName = $("#chatUserName").val();
	    	var chatMsgBox = $("#chatMsgBox").val();
	    	var currentdate = new Date();
	    	var datetime = (currentdate.getMonth()+1)  + "/" 
	                + currentdate.getDate() + "/"
	                + currentdate.getFullYear() + " @ "  
	                + currentdate.getHours() + ":"  
	                + currentdate.getMinutes() + ":" 
	                + currentdate.getSeconds();
	        var date = currentdate.getFullYear() + "/" + (currentdate.getMonth()+1) + "/" + currentdate.getDate();
			$(this).tooltip("hide");

	        if (e.which == 13) {
	        	e.preventDefault();
	        	if( $('.notificationInput-container').length ){
					chatColor = $('.sp-preview-inner').css("backgroundColor");
				}else{
					chatColor = '#ffffff';
				}

				data={
            		chatUserID:chatUserID,
            		chatUserName:chatUserName,            		
			        chatMsgBox:textContentWithHTMLTags, textContent,
			        chatDateTime:datetime,
			        chatDate:date,
			        chatColor:chatColor
		        };

				if( !$(".msg_box .emojionearea-editor").html() ) {
			        $(this).css("border","2px solid red");
					$(this).tooltip("show");

			    }else{
			    	$( "#chatMsgBox" ).empty();
		        	$( ".msg_box .emojionearea-editor" ).empty();
		       		add_chat(data.chatUserID, data.chatUserName, data.chatMsgBox, data.chatDateTime, data.chatDate, data.chatColor );
			    }           	
		        
            }
        });
    }

    function add_chat(chatUserID, chatUserName, chatMsgBox, chatDateTime, chatDate, chatColor){

        jQuery.ajax({            
            type: "POST",
            url: pluginsURL.pluginsURL + '/xpload-webinar/php/submitChat.php',
            data: ({ userId: chatUserID, userName: chatUserName, userMsg: chatMsgBox, datetime: chatDateTime, date:chatDate, chatColor:chatColor }),
            dataType: 'json',
    		cache: false,
	        success: function(response) {
	        	var html = '';
            	var newmessage = 0;
	        	response.forEach(function(data) {
	        		var fontsize = $('#fontSizeSelect').val();
	        		var chat_id = data.xpload_chat_id;
	        		var userId = data.xpload_chat_uid;
		        	var userName = data.xpload_chat_name;	
		        	var userMsg = data.xpload_chat_messages;	        	        	
		        	var datetime = data.xpload_chat_datetime;
		        	var currentDate = data.xpload_chat_date;
		        	var chatColor = data.xpload_chat_color
		        	newmessage = 1;
		        	html += "<li class='actual_msg' id='chat_msg_"+chat_id+"' style='text-align:right;float:right;word-wrap: break-word;width:80%;background-color:"+chatColor+";font-size:"+fontsize+"px;'><section><strong style='text-transform:capitalize;font-weight:900;font-size:.8vw;'>"+userName+"</strong><p style='text-align: left;margin-top:1%;margin-bottom:1%;'>"+userMsg+"</p><i class='fa fa-trash deleteChat' id='"+chat_id+"' style='margin-right: .2vw;'></i><span class='date' style='display:inline-block !important;'>"+datetime+"</span></section></li>";		        	
	        	});
				$("#chatbox").append(html);
				$("#chatbox").animate({scrollTop: $("#chatbox").get(0).scrollHeight},900);
	        },
	        error: function(response) {
	            console.log(response);
	        }
        });
    }

    function pusherChat(){
    	var chatUserID = $("#chatUserID").val();
    	var pusher = new Pusher('a6e881af5162a58d2816', {
	      cluster: 'us2'
	    });
		var channel = pusher.subscribe('my-chat');
		channel.bind('chat-event', function(data) {
			PlayChatSound();			
			var html = '';
			data.forEach(function(data) {
        		var userId = data.xpload_chat_uid;

        		if(userId != chatUserID){
        			var fontsize = $('#fontSizeSelect').val();
        			var chat_id = data.xpload_chat_id;
		        	var userName = data.xpload_chat_name;	
		        	var userMsg = data.xpload_chat_messages;	        	        	
		        	var datetime = data.xpload_chat_datetime;
		        	var currentDate = data.xpload_chat_date;
		        	var chatColor = data.xpload_chat_color
		        	toastr.warning(userName + ': ' +userMsg);

		        	html += "<li class='actual_msg' id='chat_msg_"+chat_id+"' style='text-align:left;float:left;background-color:"+chatColor+";font-size:"+fontsize+"px;'><section><strong style='text-transform:capitalize;font-weight:900;font-size:.8vw;'>"+userName+"</strong><p style='margin-top:1%;margin-bottom:1%;'>"+userMsg+"</p><span class='date' style='display:inline-block !important;'>"+datetime+"</span><i class='fa fa-trash deleteChat' id='"+chat_id+"' style='margin-left: .2vw;'></i></section></li>";
	        	}
        	});

			$("#chatbox").append(html);
			$("#chatMsgBox").val('');
			$("#chatbox").animate({scrollTop: $("#chatbox").get(0).scrollHeight},900);
	    });
    }
    /** END SEND CHAT **/

	/**
		**
		* Chat Search
		**
	**/
	$("#chat-search").click(function(){
		$("#chatSearch").modal();
	});

	$("#searchChatItem").keyup(function () {
	        delay(function () {
	            var keyword = $("#searchChatItem").val();
	            var currentdate = new Date();
				var date = currentdate.getFullYear() + "/" + (currentdate.getMonth()+1) + "/" + currentdate.getDate();
				var fontsize = $('#fontSizeSelect').val();
	            $.ajax({
	                type: "POST",
		            url: pluginsURL.pluginsURL + '/xpload-webinar/php/searchChat.php',
		            data: ({ keyword:keyword, date:date }),
		            dataType: 'json',
		    		cache: false,
	                success: function(response) {
	                	if ( response.length == 0 ) {
                			$('#searchChatList').empty();                		
                			html += "<li>No data</li>";
				        	$("#searchChatList").append(html);	                		
					    }else{ 
                			$('#searchChatList').empty();
		                    var html = '';
							response.forEach(function(data) {
								var chat_id = data.xpload_chat_id; 
					    		var userId = data.xpload_chat_uid;
					        	var userName = data.xpload_chat_name;	
					        	var userMsg = data.xpload_chat_messages;	        	        	
					        	var datetime = data.xpload_chat_datetime;
					        	var currentDate = data.xpload_chat_date;
					        	var notifColor = data.xpload_chat_color;
					        	
					        	html += "<li class='actual_msg' id="+chat_id+" style='text-align:left;float:left;background: #ffffff;width:90%;"+notifColor+"font-size:"+fontsize+"px;'><i class='fa fa-trash deleteChat' id="+chat_id+" aria-hidden='true'></i><section><strong style='text-transform:capitalize;font-weight:900;font-size:.8vw;'>"+userName+"</strong><p style='display:block;margin-top:1%;margin-bottom:1%;font-weight:500;' id='userMsg'>"+userMsg+"</p><span class='date' style='display:block!important;'>"+datetime+"</span></section></li>";
					    	});

					    	$("#searchChatList").append(html);
				    	}
	                }
	            });
	        }, 500);
	    }
	);
	/** END CHAT SEARCH **/
	/**
		**
		* Fetch All Chat
		**
	**/
	fetchChat();
	function fetchChat(){
		var chatUserID = $("#chatUserID").val();
		var currentdate = new Date();
		var date = currentdate.getFullYear() + "/" + (currentdate.getMonth()+1) + "/" + currentdate.getDate();
		var fontsize = $('#fontSizeSelect').val();

		jQuery.ajax({            
            type: "POST",
            url: pluginsURL.pluginsURL + '/xpload-webinar/php/getAllChat.php',
            data: ({ date:date }),
            dataType: 'json',
    		cache: false,
	        success: function(response) {
	        	var html = '';
            	// var newmessage = 0;
	        	response.forEach(function(data) {
	        		var chat_id = data.xpload_chat_id;    		
	        		var userId = data.xpload_chat_uid;
		        	var userName = data.xpload_chat_name;	
		        	var userMsg = data.xpload_chat_messages;	        	        	
		        	var datetime = data.xpload_chat_datetime;
		        	var currentDate = data.xpload_chat_date;
		        	var chatColor = data.xpload_chat_color
		        	// newmessage = 1;

	        		if(userId != chatUserID){
	        			html += "<li class='actual_msg' id='chat_msg_"+chat_id+"' style='text-align:left;float:left;background-color:"+chatColor+";font-size:"+fontsize+"px;'><section><strong style='text-transform:capitalize;font-weight:900;font-size:.8vw;'>"+userName+"</strong><p style='margin-top:1%;margin-bottom:1%;'>"+userMsg+"</p><span class='date' style='display:inline-block !important;'>"+datetime+"</span><i class='fa fa-trash deleteChat' id='"+chat_id+"' style='margin-left: .2vw;'></i></section></li>";
	        		}else{
	        			html += "<li class='actual_msg' id='chat_msg_"+chat_id+"' style='text-align:right;float:right;word-wrap: break-word;width:80%;background-color:"+chatColor+";font-size:"+fontsize+"px;'></div><section><strong style='text-transform:capitalize;font-weight:900;font-size:.8vw;'>"+userName+"</strong><p style='margin-top:1%;margin-bottom:1%;text-align: left;'>"+userMsg+"</p><i class='fa fa-trash deleteChat' id='"+chat_id+"' style='margin-right: .2vw;'></i><span class='date' style='display:inline-block !important;'>"+datetime+"</span></section></li>";
	        				        			
	        		}
	        	});
	        	$("#chatbox").append(html);	
				$("#chatbox").animate({scrollTop: $("#chatbox").get(0).scrollHeight},900);	
				
	        },
	        error: function(response) {
	            console.log(response);
	        }
        });
	}
	/** END FETCH CHAT **/
	/**
		**
		* DELETE CHAT
		**
	**/	
	$("#chat-container").on('click', '.actual_msg .deleteChat', function() {        
        var chat_id = this.id;

        $.confirm({
		    title: 'Delete chat message!',
		    content: 'Are you sure you want to delete this message?',
		    buttons: {
		        confirm: function () {
		            jQuery.ajax({            
			            type: "POST",
			            url: pluginsURL.pluginsURL + '/xpload-webinar/php/deleteChat.php',
			            data: ({ chat_id:chat_id }),
			            dataType: 'json',
			    		cache: false,
				        success: function(response) {
				        	$.confirm({
							    title: 'Congratulations!',
							    content: 'Message has been deleted succesfully!',
							});
				        },
				        error: function(response) {
				            $.alert({
							    title: 'Alert!',
							    content: 'Failed to delete message! Please try again!',
							});
				        }
			 		});
		        },
		        cancel: function () {
		            // $.alert('Canceled!');
		        }
		    }
		});
        
 	}); 	

 	var pusher9 = new Pusher('a6e881af5162a58d2816', {
      cluster: 'us2'
    });
	var channel9 = pusher9.subscribe('delete-chat');
	channel9.bind('delete-chatevent', function(data) {		
		var chatid = "#chat_msg_" + data;
		$(chatid).fadeOut(300, function(){ $(this).remove();});
    });
    /** END DELETE CHAT **/

    /**
		**
		* DELETE NOTIFICATION
		**
	**/	
	
	$("#notification-container").on('click', '.actual_msg .deleteNotif', function() {        
        var notif_id = this.id;

        $.confirm({
		    title: 'Delete Trade Announcement message!',
		    content: 'Are you sure you want to delete this message?',
		    buttons: {
		        confirm: function () {
		            jQuery.ajax({            
			            type: "POST",
			            url: pluginsURL.pluginsURL + '/xpload-webinar/php/deleteNotif.php',
			            data: ({ notif_id:notif_id }),
			            dataType: 'json',
			    		cache: false,
				        success: function(response) {
				        	$.confirm({
							    title: 'Congratulations!',
							    content: 'Message has been deleted succesfully!',
							});
				        },
				        error: function(response) {
				            $.alert({
							    title: 'Alert!',
							    content: 'Failed to delete message! Please try again!',
							});
				        }
			 		});
		        },
		        cancel: function () {
		            // $.alert('Canceled!');
		        }
		    }
		});
        
 	}); 	

 	var pusher9 = new Pusher('a6e881af5162a58d2816', {
      cluster: 'us2'
    });
	var channel10 = pusher9.subscribe('delete-notif');
	channel10.bind('delete-notifevent', function(data) {		
		var chatid = "#notif_msg_" + data;
		$(chatid).fadeOut(300, function(){ $(this).remove();});
    });

    /**
    	**
    	* Chat and Notification Sound
		**
	**/
	function PlayNotificationSound(audioSrc) {
        var audio = $("#PlayNotificationSound");      
    	$("#PlayNotificationSoundFile").attr("src", audioSrc);
    	audio[0].pause();
	    audio[0].load();
	    audio[0].play();
    }
    function PlayChatSound() {
        var chatSound = document.getElementById("PlayChatSound");
        chatSound.currentTime = 0;
        chatSound.play();
    }

    /** 
    	**
    	* Change Font Size
    	**
    **/
	$('#fontSizeSelect').on('change', function() {		
		var fontsize = this.value+"px";
		$(".chat-container .chatbox p").css("font-size", fontsize);
		$(".notification-container .notificationBox #userMsg").css("font-size", fontsize);
	});	

	/**
		**
		* Fetch All Notification For Today
		**
	**/
	fetchNotif();
	function fetchNotif(){
		var currentdate = new Date();
		var date = currentdate.getFullYear() + "/" + (currentdate.getMonth()+1) + "/" + currentdate.getDate();
		var fontsize = $( "#fontSizeSelect" ).val();

		jQuery.ajax({            
            type: "POST",
            url: pluginsURL.pluginsURL + '/xpload-webinar/php/getAllNotif.php',
            data: ({ date:date }),
            dataType: 'json',
    		cache: false,
	        success: function(response) {
	        	var html = '';
				response.forEach(function(data) {
					var notif_id = data.xpload_notif_id;
		    		var userId = data.xpload_notif_uid;
		        	var userName = data.xpload_notif_name;	
		        	var userMsg = data.xpload_notif_messages;	        	        	
		        	var datetime = data.xpload_notif_datetime;
		        	var currentDate = data.xpload_notif_date;
		        	var audioSrc = data.xpload_notif_audiosrc;
		        	var notifColor = data.xpload_notif_color;
		        	
		        	html += "<li class='actual_msg' id='notif_msg_"+notif_id+"' style='text-align:left;float:left;background: #ffffff;width:90%;"+notifColor+"font-size:"+fontsize+"px;'><section><strong style='text-transform:capitalize;font-weight:900;font-size:.8vw;'>"+userName+"</strong><p style='display:block;margin-top:1%;margin-bottom:1%;font-weight:400;' id='userMsg'>"+userMsg+"</p><span class='date'>"+datetime+"</span><i class='fa fa-trash deleteNotif' id='"+notif_id+"' style='margin-left: .2vw;'></i></section></li>";
		    	});

		    	$("#xprowebinarNotificationMsgBox").append(html);
				$("#xprowebinarNotificationMsgBox").animate({scrollTop: $("#xprowebinarNotificationMsgBox").get(0).scrollHeight},900);
				
	        },
	        error: function(response) {
	            console.log(response);
	        }
        });
	}


	/**
		**
		* Search Notification
		**
	**/
	$("#notif-search").click(function(){
		$("#notifSearch").modal();
	});

	var delay = (function() {
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();

	$("#searchNotifItem").keyup(function () {
	        delay(function () {
	            var keyword = $("#searchNotifItem").val();
	            var currentdate = new Date();
				var date = currentdate.getFullYear() + "/" + (currentdate.getMonth()+1) + "/" + currentdate.getDate();
				var fontsize = $('#fontSizeSelect').val();

	            var URL = encodeURI("search.php?q=" + keyword);
	            $.ajax({
	                type: "POST",
		            url: pluginsURL.pluginsURL + '/xpload-webinar/php/searchNotif.php',
		            data: ({ keyword:keyword, date:date }),
		            dataType: 'json',
		    		cache: false,
	                success: function(response) {
	                	if ( response.length == 0 ) {
                			$('#searchNotifList').empty();                		
                			html += "<li>No data</li>";
				        	$("#searchNotifList").append(html);	                		
					    }else{ 
                			$('#searchNotifList').empty();
		                    var html = '';
							response.forEach(function(data) {
					    		var userId = data.xpload_notif_uid;
					        	var userName = data.xpload_notif_name;	
					        	var userMsg = data.xpload_notif_messages;	        	        	
					        	var datetime = data.xpload_notif_datetime;
					        	var currentDate = data.xpload_notif_date;
					        	var audioSrc = data.xpload_notif_audiosrc;
					        	var notifColor = data.xpload_notif_color;
					        	
					        	html += "<li class='actual_msg' style='text-align:left;float:left;background: #ffffff;width:90%;"+notifColor+";font-size:"+fontsize+"px;'><section><strong style='text-transform:capitalize;font-weight:900;font-size:.8vw;'>"+userName+"</strong><p style='display:block;margin-top:1%;margin-bottom:1%;font-weight:500;' id='userMsg'>"+userMsg+"</p><span class='date' style='display:block!important;'>"+datetime+"</span></section></li>";
					    	});

					    	$("#searchNotifList").append(html);
				    	}
	                }
	            });
	        }, 500);
	    }
	);

	// Declaration of elements
	var videoContainer = document.getElementById("videoContainer");
	var notificationcontainer = document.getElementById("notification-container");
	var chatcontainer = document.getElementById("chat-container");
	var avatar1 = document.getElementById("avatar1");
	var avatar2 = document.getElementById("avatar2");
	var avatar3 = document.getElementById("avatar3");
	/**
		**
		* Moving of containers
		**
	**/
	$( "#videoContainer" ).draggable({ 
		handle: "#header-image",
		start: function( event, ui ) {
			if(event.target.id == "videoContainer"){
				videoContainer.style.zIndex = "2";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "1";
			}
		},
		stop: function( event, ui ) {
			var style = document.getElementById(event.target.id).style.cssText;
		    saveElementPosition(event.target, style);
		}
	});

	// Notification Container
	$( "#notification-container" ).draggable({ 
		handle: "h5",
		start: function( event, ui ) {
			if(event.target.id == "notification-container"){
				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "2";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "1";
			}
		},
		stop: function( event, ui ) {
			var style = document.getElementById(event.target.id).style.cssText;
		    saveElementPosition(event.target, style);
		}
	});
	// Chat Contianer
	$( "#chat-container" ).draggable({ 
		handle: "h5",
		start: function( event, ui ) {
			if(event.target.id == "chat-container"){
				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "2";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "1";
			}
		},
		stop: function( event, ui ) {
			var style = document.getElementById(event.target.id).style.cssText;
		    saveElementPosition(event.target, style);
		}
	});
	// Avatar 1
	$( "#avatar1" ).draggable({ 
		handle: "h3",
		start: function( event, ui ) {
			if(event.target.id == "avatar1"){
				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "2";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "1";
			}
		},
		stop: function( event, ui ) {
			var style = document.getElementById(event.target.id).style.cssText;
		    saveElementPosition(event.target, style);
		}
	});
	// Avatar 2
	$( "#avatar2" ).draggable({ 
		handle: "h3",
		start: function( event, ui ) {
			if(event.target.id == "avatar2"){
				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "2";
				avatar3.style.zIndex = "1";
			}
		},
		stop: function( event, ui ) {
			var style = document.getElementById(event.target.id).style.cssText;
		    saveElementPosition(event.target, style);
		}
	});
	// Avatar 3
	$( "#avatar3" ).draggable({ 
		handle: "h3",
		start: function( event, ui ) {
			if(event.target.id == "avatar3"){
				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "2";
			}
		},
		stop: function( event, ui ) {
			var style = document.getElementById(event.target.id).style.cssText;
		    saveElementPosition(event.target, style);
		}
	});

	/**
		**
		* Resizing of containers
		**
	**/
	/** Change made recent **/
	$("#videoContainer").resizable({
		handles: "n, e, s, w, ne, se, sw, nw",
		resize: function( e, $el, opt ) {
			if(e.target.id == "videoContainer"){
				videoContainer.style.zIndex = "2";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "1";
			}
			$('#videocontainerrow').css('position', 'absolute').css('top', '0');
			$('#avatarcontainerow').css('position', 'absolute').css('width', '100%').css('top', '31.1vw');
			$('#xprowebinar-subscriber').css('height', '100%');
		},
	    stop: function (e, $el, opt) {        
	        var style = document.getElementById(e.target.id).style.cssText;
	        saveElementPosition(e.target, style);
	    }
	});
	$( "#notification-container" ).resizable({
		handles: "n, e, s, w, ne, se, sw, nw",
		resize: function( e, $el, opt ) {
			if(e.target.id == "notification-container"){
				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "2";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "1";
			}
			$('#notification-container').css('max-width', '100%');
		},
	    stop: function (e, $el, opt) {		        
	        var style = document.getElementById(e.target.id).style.cssText;
	        saveElementPosition(e.target, style);
	    }
	});
	$( "#chat-container" ).resizable({
		handles: "n, e, s, w, ne, se, sw, nw",
		resize: function( e, $el, opt ) {
			if(e.target.id == "chat-container"){
				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "2";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "1";
			}
			$('#chat-container').css('max-width', '100%');
		},
	    stop: function (e, $el, opt) {		        
	        var style = document.getElementById(e.target.id).style.cssText;
	        saveElementPosition(e.target, style);
	    }
	});
	$( "#avatar1" ).resizable({
		handles: "n, e, s, w, ne, se, sw, nw",
		resize: function( e, $el, opt ) {
			if(e.target.id == "avatar1"){
				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "2";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "1";
			}	
		},
	    stop: function (e, $el, opt) {		        
	        var style = document.getElementById(e.target.id).style.cssText;
	        saveElementPosition(e.target, style);
	    }
	});
	$( "#avatar2" ).resizable({
		handles: "n, e, s, w, ne, se, sw, nw",
		resize: function( e, $el, opt ) {			
			if(e.target.id == "avatar2"){
				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "2";
				avatar3.style.zIndex = "1";
			}
			$('#xprowebinarPublisherCamera').css('height', '100%');
		},
	    stop: function (e, $el, opt) {		        
	        var style = document.getElementById(e.target.id).style.cssText;
	        saveElementPosition(e.target, style);
	    }
	});
	$( "#avatar3" ).resizable({
		handles: "n, e, s, w, ne, se, sw, nw",
		resize: function( e, $el, opt ) {
			if(e.target.id == "avatar3"){

				console.log(e);
				console.log($el);
				console.log(opt);

				videoContainer.style.zIndex = "1";
				notificationcontainer.style.zIndex = "1";
				chatcontainer.style.zIndex = "1";
				avatar1.style.zIndex = "1";
				avatar2.style.zIndex = "1";
				avatar3.style.zIndex = "2";
			}
		},
	    stop: function (e, $el, opt) {		        
	        var style = document.getElementById(e.target.id).style.cssText;
	        saveElementPosition(e.target, style);
	    }
	});
	
	$( "#resetView" ).click(function() {
	  	$.confirm({
		    title: 'Reset View!',
		    content: 'Do you want to reset the view of all the elements?',
		    buttons: {
		        confirm: function () {
		        	var style1 = "width:100%;height:auto;";
					var style2 = "";

		            document.getElementById("videoContainer").style.cssText = style1;
		            document.getElementById("notification-container").style.cssText = style2;
		            document.getElementById("chat-container").style.cssText = style2;
		            document.getElementById("avatar1").style.cssText = style2;
		            document.getElementById("avatar2").style.cssText = style2;
		            document.getElementById("avatar3").style.cssText = style2;

		            document.getElementById("videocontainerrow").style.cssText = style2;
		            document.getElementById("avatarcontainerow").style.cssText = style2;

		            document.getElementById("xprowebinarPublisherCamera").style.cssText = style2;

		            var videoContainer = document.getElementById("videoContainer");
		            var notificationcontainer = document.getElementById("notification-container");
					var chatcontainer = document.getElementById("chat-container");
					var avatar1 = document.getElementById("avatar1");
					var avatar2 = document.getElementById("avatar2");
					var avatar3 = document.getElementById("avatar3");

					saveElementPosition(videoContainer, style1);
					saveElementPosition(notificationcontainer, style2);
					saveElementPosition(chatcontainer, style2);
					saveElementPosition(avatar1, style2);
					saveElementPosition(avatar2, style2);
					saveElementPosition(avatar3, style2);
		        },
		        cancel: function () {
		            // $.alert('Canceled!');
		        }
		    }
		});
	});

	function saveElementPosition(element, style){
		var userId = $("#chatUserID").val();
		var userName = $("#chatUserName").val();
			jQuery.ajax({            
				type: "POST",
				url: pluginsURL.pluginsURL + '/xpload-webinar/php/elementPosition.php',
				data: ({ userId:userId, userName:userName, elementId:element.id, style:style }),
				cache: false,
				success: function(response) {
					// console.log("SUCCESS RESPONSE " + response);
				},
				error: function(response) {
					// console.log("ERROR RESPONSE " + response);
				}
			});
	}


	$( "#closeavatar1" ).click(function() {
		$( "#avatar1" ).css("display", "none");
	});

	$( "#closeavatar2" ).click(function() {
		$( "#avatar2" ).css("display", "none");
	});

	$( "#closeavatar3" ).click(function() {
		$( "#avatar3" ).css("display", "none");
	});

	$( "#avatarcontainer" ).click(function() {
		if(this.id == "avatarcontainer"){
			videoContainer.style.zIndex = "1";
			notificationcontainer.style.zIndex = "1";
			chatcontainer.style.zIndex = "1";
			avatar1.style.zIndex = "2";
			avatar2.style.zIndex = "2";
			avatar3.style.zIndex = "2";
			avatarcontainer.style.zIndex = "2"
		}
	});
	$( "#videoContainer" ).click(function() {
		if(this.id == "videoContainer"){
			videoContainer.style.zIndex = "2";
			notificationcontainer.style.zIndex = "1";
			chatcontainer.style.zIndex = "1";
			avatar1.style.zIndex = "1";
			avatar2.style.zIndex = "1";
			avatar3.style.zIndex = "1";
			avatarcontainer.style.zIndex = "1"
		}
	});
	$( "#notification-container" ).click(function() {
		if(this.id == "notification-container"){
			videoContainer.style.zIndex = "1";
			notificationcontainer.style.zIndex = "2";
			chatcontainer.style.zIndex = "1";
			avatar1.style.zIndex = "1";
			avatar2.style.zIndex = "1";
			avatar3.style.zIndex = "1";
			avatarcontainer.style.zIndex = "1"
		}
	});
	$( "#chat-container" ).click(function() {
		if(this.id == "chat-container"){
			videoContainer.style.zIndex = "1";
			notificationcontainer.style.zIndex = "1";
			chatcontainer.style.zIndex = "2";
			avatar1.style.zIndex = "1";
			avatar2.style.zIndex = "1";
			avatar3.style.zIndex = "1";
			avatarcontainer.style.zIndex = "1"
		}
	});

	/** Change made recent **/
	// $(function () {
	//   $('[data-toggle="tooltip"]').tooltip()
	// })

	
});