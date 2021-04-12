// (function (red5prosdk) {

//       // Create a new instance of the WebRTC publisher.
//       var publisher = new red5prosdk.RTCPublisher();

//       // Initialize
//       publisher.init({
//           protocol: 'wss',
//           port: 443,
//           host: 'xprowebinar.com',
//           app: 'live',
//           streamName: 'admincamera',
//           rtcConfiguration: {
//             iceServers: [{urls: 'stun:stun2.l.google.com:19302'}],
//             iceCandidatePoolSize: 2,
//             bundlePolicy: 'max-bundle'
//           }, // See https://developer.mozilla.org/en-US/docs/Web/API/RTCPeerConnection/RTCPeerConnection#RTCConfiguration_dictionary
//           streamMode: 'live',
//           mediaElementId: 'xprowebinarPublisherCamera',
//           bandwidth: {
//             audio: 56,
//             video: 512
//           },
//           mediaConstraints: {
//             audio: true,
//             video: {
//               width: {
//                 exact: 640
//               },
//               height: {
//                 exact: 480
//               },
//               frameRate: {
//                 min: 8,
//                 max: 24
//               }
//             }
//           }
//         })
//         .then(function() {
//           // Invoke the publish action.
//           return publisher.publish();
//         })
//         .catch(function(error) {
//           // A fault occurred while trying to initialize and publish the stream.
//           // console.error(error);
//           return publisher.publish();
//         });

//     })(window.red5prosdk);