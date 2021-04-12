/**
  **
  * Defining/accessing testbed configuration.
  **
**/
(function (window, adapter) {

  if (typeof adapter !== 'undefined') {
    console.log('Browser: ' + JSON.stringify(adapter.browserDetails, null, 2));
  }

  // http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
  function getParameterByName(name, url) { // eslint-disable-line no-unused-vars
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  var protocol = window.location.protocol;
  var port = window.location.port;
  protocol = protocol.substring(0, protocol.lastIndexOf(':'));

  var isMoz = !!navigator.mozGetUserMedia;
  var isEdge = (adapter && adapter.browserDetails.browser.toLowerCase() === 'edge')  || (window.navigator.userAgent.toLowerCase().indexOf("edge") > -1);
  var isiPod = !!navigator.platform && /iPod/.test(navigator.platform);
  var config = sessionStorage.getItem('xprowebinarSettings');
  var json;
  var serverSettings = {
    "protocol": protocol,
    "httpport": port,
    "hlsport": 5080,
    "hlssport": 443,
    "wsport": 8081,
    "wssport": 8083,
    "rtmpport": 1935,
    "rtmpsport": 1936
  };
  function assignStorage () {
    json = {
      "host": "xprowebinar.com",
      "port": 443, // rtsp
      "stream1": "xprowebinarscreenshare",
      "stream2": "xprowebinarscreenshare",
      "app": "live",
      // "proxy": "streammanager",
      "isEdge": isEdge,
      "streamMode": "live",
      "mediaElementId": "xprowebinarPublisherCamera",
      "cameraWidth": 1080,
      "cameraHeight": 720,
      "embedWidth": "100%",
      "embedHeight": 480,
      "buffer": 0.5,
      "bandwidth": {
        "audio": 56,
        "video": 3000
      },
      "useAudio": true,
      "useVideo": true,
      "mediaConstraints": {
        "audio": isiPod ? false : true,
        "video": (isMoz || isEdge) ? true : {
          "width": {
            "min": 640,
            "max": 1080
          },
          "height": {
            "min": 480,
            "max": 720
          },
          "frameRate": {
            "min": 24,
            "max": 48
          }
        }
      },
      "publisherFailoverOrder": "rtc,rtmp",
      "subscriberFailoverOrder": "rtc,rtmp,hls",
      "iceServers": [
        {
          "urls": "stun:stun2.l.google.com:19302"
        }
      ],
      "googleIce": [
        {
          "urls": "stun:stun2.l.google.com:19302"
        }
      ],
      "mozIce": [
        {
          "urls": "stun:stun.services.mozilla.com:3478"
        }
      ],
      "iceTransport": "udp",
      "verboseLogging": true,
      "streamManagerAPI": "3.0",
      "streamManagerAccessToken": "DBM8Q81WK8VMKBKJ"
    };
    /**
    if (isMoz) {
      json.iceServers = json.mozIce;
    }
    */
    sessionStorage.setItem('xprowebinarSettings', JSON.stringify(json));
  }

  function defineIceServers () {
    var param = getParameterByName('ice');
    if (param) {
      if (param === 'moz') {
        json.iceServers = json.mozIce;
      }
      else {
        json.iceServers = json.googleIce;
      }
      console.log('ICE server provided in query param: ' + JSON.stringify(json.iceServers, null, 2));
    }
  }

  if (config) {
    try {
      json = JSON.parse(config);
    }
    catch (e) {
      assignStorage();
    }
    finally {
      defineIceServers();
      sessionStorage.setItem('xprowebinarSettings', JSON.stringify(json));
    }
  }
  else {
    assignStorage();
    defineIceServers();
    sessionStorage.setItem('xprowebinarSettings', JSON.stringify(json));
  }

  sessionStorage.setItem('r5proServerSettings', JSON.stringify(serverSettings));
  return json;

})(this, window.adapter);


/**
  **
  * Jquery Function
  **
**/

jQuery(function($) {
  connectToStream();
  var captureButton = $('.startVideoCapture');
  var pauseCaptureButton = $('.pauseVideoCapture');
  var stopCaptureButton = $('.stopVideoCapture');

  captureButton.click(function() {
    capture(setupPublisher);
  });

  stopCaptureButton.click(function() {
    $.alert({
      title: 'Alert!',
      content: 'Webinar is shutting down!',
    });
    shutdown();
    // document.getElementById("presenter").style.display = "flex";
    captureButton.css('display','initial');
    pauseCaptureButton.css("display", "none");
    stopCaptureButton.css("display", "none");
    
  });

  pauseCaptureButton.click(function() {
    pauseWebinar();
    shutdown();
  });

  // Starts Subscribers webinar when video are ready for connection
  function startWebinar(){
    var adminID = $('#adminId').val();
    $.ajax({
        type: "POST",
        url: pluginsURL.pluginsURL + '/xpload-webinar/php/startwebinar.php',
        data: { data: adminID },
        success: function (response) {
          $.alert({
            title: 'Attention!',
            content: 'Subsribers are connecting!',
          });
          $('.xploadchatnotif-container').css("height", "49.5vw");
          captureButton.css('display','none');
          pauseCaptureButton.css("display", "initial");
          stopCaptureButton.css("display", "initial");
        },
        error: function (msg) {
          $.alert({
            title: 'Attention!',
            content: msg,
          });
        }
      });
  }

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
    * Webinar starts to other admins
    * When Publisher admin click "Start Webinar" button
    **
  **/
  var channelWebinar = adminpusherWebinar.subscribe('startwebinar');

  channelWebinar.bind('startwebinarevent', function(data) {

    var currentAdminId = $('#currentAdminId').val();
    var thisadmin = $('#thisadmin').val();

    var captureButton = $('.startVideoCapture');
    var pauseCaptureButton = $('.pauseVideoCapture');
    var stopCaptureButton = $('.stopVideoCapture');

    if(!$('#selectvidimage').length){      
        $( ".selectvidimage" ).css('display', 'flex');
    }

    if(data != currentAdminId){ 
      captureButton.css('display','none');
      pauseCaptureButton.css("display", "none");
      stopCaptureButton.css("display", "none");

      $( "#xprowebinarPublisherCamera" ).remove();
      
      if(!$('#xprowebinarPublisherCamera').length){
        $( "#xprowebinarSubscriberCamera" ).css('display', 'flex');        
        $( ".selectvidimage" ).css('display', 'flex');
      }
      connectToStream();
    }else{
      connectAdminStream();
    }

  });

  // Admin Pause Stop Webinar
  var pausewebinar = adminpusherWebinar.subscribe('pausewebinar');

  pausewebinar.bind('pausewebinarevent', function(data) {
    $.alert({
      title: 'Attention!',
      content:'Webinar is Paused!',
    });
    var adminID = $('#adminId').val();
    var thisadmin = $('#thisadmin').val();

    if(data != currentAdminId){
      $('#xprowebinar-subscriber').trigger('pause');
      $('#xprowebinarSubscriberCamera').trigger('pause');
      captureButton.css('display','initial');
      pauseCaptureButton.css("display", "none");
      stopCaptureButton.css("display", "initial");
    }
  });

  function pauseWebinar(){
    var adminID = $('#adminId').val();
    $.ajax({
        type: "POST",
        url: pluginsURL.pluginsURL + '/xpload-webinar/php/pausewebinar.php',
        data: { data: adminID },
        success: function (response) {
          $('#xprowebinar-subscriber').trigger('pause');
          $('#xprowebinarPublisherCamera').trigger('pause');
          captureButton.css('display','initial');
          pauseCaptureButton.css("display", "none");
          stopCaptureButton.css("display", "initial");
          // document.getElementById("presenter").style.display = "flex";
        },
        error: function (msg) {
          $.alert({
            title: 'Attention!',
            content:msg,
          });
        }
      });
  }

  /**
    **
    * Start Xprowebinar Configuration
    **
  **/
  var configuration = (function () {
    var conf = sessionStorage.getItem('xprowebinarSettings');
    try {
      return JSON.parse(conf);
    }
    catch (e) {
      console.error('Could not read configuration from sessionstorage: ' + e.message);
    }
    return {}
  })();

  function getAuthenticationParams () {
    var auth = configuration.authentication;
    return auth && auth.enabled
      ? {
        connectionParams: {
          username: auth.username,
          password: auth.password
        }
      }
      : {};
  }

  var serverSettings = (function() {
    var settings = sessionStorage.getItem('r5proServerSettings');
    try {
      return JSON.parse(settings);
    }
    catch (e) {
      console.error('Could not read server settings from sessionstorage: ' + e.message);
    }
    return {};
  })();

  /**
    **
    * Publisher status messages
    **
  **/
  function onPublisherEvent (event) {
    console.log('[Red5ProPublisher] ' + event.type + '.');  
    if(event.type === "WebRTC.PeerConnection.Open"){
      startWebinar();
    }else if(event.type == "Publisher.Connection.Closed"){
      $.alert({
        title: 'Attention!',
        content:'Connection Failed! Click ok to restart connection!',
      });
      capture(setupPublisher);
    }
  }
  
  function onPublishSuccess (publisher) {
    console.log('[Red5ProPublisher] Publish Complete.');
    try {
      var pc = publisher.getPeerConnection();
      var stream = publisher.getMediaStream();
      window.trackBitrate(pc, onBitrateUpdate);
      statisticsField.classList.remove('hidden');
      stream.getVideoTracks().forEach(function (track) {
        var settings = track.getSettings();
        onResolutionUpdate(settings.width, settings.height);
      });
    }
    catch (e) {
      // no tracking for you!
    }
  }

  function onPublishFail (message) {
    console.error('[Red5ProPublisher] Publish Error :: ' + message);
    captureButton.disabled = false;
  }

  /**
    **
    * Protocol Configuration
    **
  **/
  var protocol = serverSettings.protocol;
  var isSecure = protocol == 'https';
  function getSocketLocationFromProtocol () {
    return !isSecure
      ? {protocol: 'ws', port: serverSettings.wsport}
      : {protocol: 'wss', port: serverSettings.wssport};
  }

  /**
    **
    * Start making stream to the server
    **
  **/
  function setupPublisher (mediaStream) {

    var config = Object.assign({},
                        configuration,
                        {
                          streamMode: configuration.recordBroadcast ? 'record' : 'live'
                        },
                        getAuthenticationParams());

    var rtcConfig = Object.assign({}, config, {
                        protocol: getSocketLocationFromProtocol().protocol,
                        port: getSocketLocationFromProtocol().port,
                        streamName: config.stream1,
                        bandwidth: {
                          video: 3000
                        },
                        keyFramerate: 3000
                      });
    var streamTitle = 'xprowebinarscreenshare';


    new red5prosdk.RTCPublisher()
      .initWithStream(rtcConfig, mediaStream)
      .then(function (publisherImpl) {
        streamTitle = configuration.stream1;
        targetPublisher = publisherImpl;
        targetPublisher.on('*', onPublisherEvent);
        return targetPublisher.publish();
      })
      .then(function () {
        onPublishSuccess(targetPublisher);        
        // setupAudio();
      })
      .catch(function (error) {
        console.log(error);
        var jsonError = typeof error === 'string' ? error : JSON.stringify(error, null, 2);
        console.error('[Red5ProPublisher] :: Error in publishing - ' + jsonError);
        onPublishFail(jsonError);
      });
  }

  /**
    **
    * Capture/Get User Media Web Camera
    **
  **/
  function capture (cb) {
    console.log(cb);
    var vw = 640;
    var vh = 480;
    var fr = 24;
    var config = {
        audio: false,
        video: {
          width: vw,
          height: vh,
          frameRate: fr
        }
    };
    var p = undefined
    p = navigator.mediaDevices.getUserMedia(config)
    p.then(cb).catch(function (error) {
      console.error(error);
    });
    $.alert({
      title: 'Attention!',
      content:'Connection in progress! Please stand by! Click ok to continue!',
    });
    // document.getElementById("presenter").style.display = "none";
    if(!$('#xprowebinarPublisherCamera').length){
      $( "#xprowebinarSubscriberCamera" ).css('display', 'none');
      $('.selectvidimage').before('<video id="xprowebinarPublisherCamera" class="red5pro-media red5pro-media-background" autoplay controls muted></video>');

      if(!$('#selectvidimage').length){
        $( "#selectvidimage" ).css('display', 'initial');
      }else{
        $( "#selectvidimage" ).css('display', 'none');
      }
      
    }
  }

  /**
    **
    * Shutdown Admin Web Camera
    **
  **/
  function unpublish (publisher) {
    return new Promise(function (resolve, reject) {
      publisher.unpublish()
        .then(function () {
          onUnpublishSuccess();
          resolve();
        })
        .catch(function (error) {
          var jsonError = typeof error === 'string' ? error : JSON.stringify(error, 2, null);
          reject(error);
        });
    });
  }

  var shuttingDown = false;
  function shutdown() {
    if (shuttingDown) return;
    shuttingDown = true;
    function clearRefs () {
      if (targetPublisher) {
        targetPublisher.off('*', onPublisherEvent);
      }      
      targetPublisher = undefined;      
    }
    unpublish(targetPublisher)
      .then(function () {
        return true;
      })
      .then(clearRefs).catch(clearRefs);
  }
  window.addEventListener('pagehide', shutdown);
  window.addEventListener('beforeunload', shutdown);

  /**
    **
    * Connect Secondary admin as subscriber
    * if primary admin already started the stream
    **
  **/
  function connectToStream(){
    (function (red5prosdk) {
      // Create a new instance of the WebRTC subcriber.
      var subscriber = new red5prosdk.RTCSubscriber();
      // Initialize
      subscriber.init({
        protocol: 'wss',
        port: 443,
        host: 'xprowebinar.com',
        app: 'live',
        streamName: 'adminstream',
        rtcConfiguration: {
          iceServers: [{urls: 'stun:stun2.l.google.com:19302'}],
          iceCandidatePoolSize: 2,
          bundlePolicy: 'max-bundle'
        }, // See https://developer.mozilla.org/en-US/docs/Web/API/RTCPeerConnection/RTCPeerConnection#RTCConfiguration_dictionary
        mediaElementId: 'xprowebinar-subscriber',
        subscriptionId: 'adminstream' + Math.floor(Math.random() * 0x10000).toString(16),
        videoEncoding: 'NONE',
        audioEncoding: 'NONE',
        autoLayoutOrientation: true
      })
      .then(function(subscriber) {
        
        return subscriber.subscribe();
      })
      .then(function(subscriber) {
      })
      .catch(function(error) {
        return subscriber.subscribe();
      });
    })(window.red5prosdk);

    // Camera Share
    (function (red5prosdk2) {  
      var subscriber = new red5prosdk.RTCSubscriber();
      subscriber.init({
      protocol: 'wss',
      port: 443,
      host: 'xprowebinar.com',
      app: 'live',
      streamName: 'xprowebinarscreenshare',
      rtcConfiguration: {
        iceServers: [{urls: 'stun:stun2.l.google.com:19302'}],
        iceCandidatePoolSize: 2,
        bundlePolicy: 'max-bundle'
      },
      mediaElementId: 'xprowebinarSubscriberCamera',
      subscriptionId: 'admincamera' + Math.floor(Math.random() * 0x10000).toString(16),
      videoEncoding: 'NONE',
      audioEncoding: 'NONE',
      autoLayoutOrientation: true
      })
      .then(function(subscriber) {
        return subscriber.subscribe();
      })
      .then(function(subscriber) {
      })
      .catch(function(error) {
        return subscriber.subscribe();
      });

    })(window.red5prosdk);
  }

  function connectAdminStream(){
    (function (red5prosdk2) {  
      var subscriber = new red5prosdk.RTCSubscriber();
      subscriber.init({
      protocol: 'wss',
      port: 443,
      host: 'xprowebinar.com',
      app: 'live',
      streamName: 'xprowebinarscreenshare',
      rtcConfiguration: {
        iceServers: [{urls: 'stun:stun2.l.google.com:19302'}],
        iceCandidatePoolSize: 2,
        bundlePolicy: 'max-bundle'
      },
      mediaElementId: 'xprowebinarSubscriberCamera',
      subscriptionId: 'admincamera' + Math.floor(Math.random() * 0x10000).toString(16),
      videoEncoding: 'NONE',
      audioEncoding: 'NONE',
      autoLayoutOrientation: true
      })
      .then(function(subscriber) {
        liveScreenShare();
        return subscriber.subscribe();
      })
      .then(function(subscriber) {
      })
      .catch(function(error) {
        console.log("No Stream Found!");
      });

    })(window.red5prosdk);
  }

  function liveScreenShare(){
    (function (red5prosdk) {
      var subscriber = new red5prosdk.RTCSubscriber();
      subscriber.init({
        protocol: 'wss',
        port: 443,
        host: 'xprowebinar.com',
        app: 'live',
        streamName: 'adminstream',
        rtcConfiguration: {
          iceServers: [{urls: 'stun:stun2.l.google.com:19302'}],
          iceCandidatePoolSize: 2,
          bundlePolicy: 'max-bundle'
        },
        mediaElementId: 'xprowebinar-subscriber',
        subscriptionId: 'adminstream' + Math.floor(Math.random() * 0x10000).toString(16),
        videoEncoding: 'NONE',
        audioEncoding: 'NONE',
        autoLayoutOrientation: true
      })
      .then(function(subscriber) {
        return subscriber.subscribe();
      })
      .then(function(subscriber) {
      })
      .catch(function(error) {
        console.log("No Available Stream");
      });
    })(window.red5prosdk);
  }

});