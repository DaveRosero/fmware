<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delivery Verification</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="container">
          <!-- Video element for displaying webcam stream -->
          <video id="video" playsinline></video>
          <!-- Image element for displaying scanned QR code -->
          <img id="scannedImage">
          <!-- Div element for displaying QR code data -->
          <div id="qrData"></div>
          <!-- Div element for containing buttons -->
          <div id="buttons">
            <!-- Button for initiating webcam QR code scanning -->
            <button type="button" class="btn btn-primary" id="webcamButton">Scan QR Code</button>
            <!-- Button for selecting an image file -->
            <!-- Button for selecting an image file -->
            <button type="button" class="btn btn-secondary" id="fileButton">Scan QR code from image</button>
            <!-- File input for selecting an image file -->
            <input type="file" accept="image/*" id="fileInput" style="display: none;">


          </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
        <script>
          // Get reference to the video element in the HTML document
          const video = document.getElementById('video');
          // Get reference to the scannedImage element in the HTML document
          const scannedImage = document.getElementById('scannedImage');
          // Get reference to the qrDataLabel element in the HTML document
          const qrDataLabel = document.getElementById('qrData');
          // Get reference to the webcamButton element in the HTML document
          const webcamButton = document.getElementById('webcamButton');
          // Get reference to the fileButton and fileInput elements in the HTML document
          const fileButton = document.getElementById('fileButton');
          const fileInput = document.getElementById('fileInput');
     // Global variable for interval ID
     let intervalId = null;
          // Function to show webcam scanning
          function showWebcam() {
            // Clear any previous QR code data and hide scanned image
            qrDataLabel.textContent = ''; // Clear the text content of qrDataLabel
            qrDataLabel.style.display = 'none'; // Hide qrDataLabel
            scannedImage.style.display = 'none'; // Hide scannedImage
            // Show the video element
            video.style.display = 'block';
            // Start webcam scanning
            startWebcamScanning();
          }

          // Function to start webcam scanning
          function startWebcamScanning() {
            // Check if navigator supports getUserMedia
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
              // Request access to the user's camera
              navigator.mediaDevices.getUserMedia({
                video: {
                  facingMode: 'environment'
                }
              }).then(function(stream) {
                // Set the video source to the stream
                video.srcObject = stream;
                // Play the video
                video.play();
              }).catch(function(error) {
                // If there's an error accessing the camera, display the error message
                qrDataLabel.textContent = 'Error accessing camera: ' + error;
              });
            } else {
              // If getUserMedia is not supported by the browser, display a message
              qrDataLabel.textContent = 'getUserMedia is not supported';
            }
          }

          // Continuously check for QR code in each frame
           intervalId = setInterval(() => {
            // Create a canvas element to draw video frames onto it
            const canvas = document.createElement('canvas');
            // Get the 2D rendering context of the canvas
            const ctx = canvas.getContext('2d');
            // Set the dimensions of the canvas to match the video dimensions
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            // Draw the current frame of the video onto the canvas
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            // Get the image data of the canvas
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            // Use jsQR library to decode the QR code from the image data
            const code = jsQR(imageData.data, imageData.width, imageData.height);
            // If QR code is detected
            if (code) {
              // Extract the data encoded in the QR code
              const qrData = code.data;
              // Display the QR code data
              qrDataLabel.textContent = 'QR Code Data: ' + qrData;
              qrDataLabel.style.display = 'block';
              // Stop the interval (stop scanning)
              clearInterval(intervalId);
              // Close camera stream
              const tracks = video.srcObject.getTracks();
              tracks.forEach(track => track.stop());
              // Display the scanned image
              scannedImage.src = canvas.toDataURL();
              scannedImage.style.display = 'block';
              // Hide the video element
              video.style.display = 'none';
              // If the scanned QR code is a URL
              if (isUrl(qrData)) {
                // Open the URL in a new tab
                window.open(qrData, '_blank');
              }
            }
          }, 100); // Adjust the interval as needed (milliseconds)

          // Function to check if string is a URL
          function isUrl(str) {
            return str.startsWith('http://') || str.startsWith('https://');
          }

          // When a file is selected, attempt to decode QR code from it
          fileInput.addEventListener('change', function(event) {
            // Get the selected file
            const file = event.target.files[0];
            // Create a new FileReader object to read the contents of the file
            const reader = new FileReader();
            // When the file is loaded
            reader.onload = function(event) {
              // Create a new Image object
              const image = new Image();
              // When the image is loaded
              image.onload = function() {
                // Create a canvas element
                const canvas = document.createElement('canvas');
                // Get the 2D rendering context for the canvas
                const ctx = canvas.getContext('2d');
                // Set the canvas dimensions to match the image dimensions
                canvas.width = image.width;
                canvas.height = image.height;
                // Draw the image onto the canvas
                ctx.drawImage(image, 0, 0);
                // Get the image data from the canvas
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                // Attempt to decode QR code from the image data
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                // If a QR code is detected
                if (code) {
                  // Display the QR code data
                  qrDataLabel.textContent = 'QR Code Data: ' + code.data;
                  qrDataLabel.style.display = 'block';
                  // Display the scanned image
                  scannedImage.src = event.target.result;
                  scannedImage.style.display = 'block';
                  // Hide the video element
                  video.style.display = 'none'; // Hide video
                  // If the QR code data is a URL
                  if (isUrl(code.data)) {
                    // Open the URL in a new tab
                    window.open(code.data, '_blank');
                  }
                } else {
                  // If no QR code is found in the image
                  qrDataLabel.textContent = 'No QR code found in the selected image';
                  qrDataLabel.style.display = 'block';
                }
              };
              // Set the source of the image to the data URL of the selected file
              image.src = event.target.result;
            };
            // Read the contents of the selected file as a data URL
            reader.readAsDataURL(file);
          });

          // Event listener for fileButton click
          fileButton.addEventListener('click', function() {
            // Trigger file input click
            fileInput.click();
          });

          // Event listener for webcam button
          webcamButton.addEventListener('click', showWebcam);

          // Function to stop the webcam and clear the interval
          function stopWebcam() {
            if (video.srcObject) {
              const tracks = video.srcObject.getTracks();
              tracks.forEach(track => track.stop());
              video.srcObject = null;
            }
            if (intervalId) {
              clearInterval(intervalId);
            }
          }

          // Event listener to stop webcam when modal is closed
          const qrModal = document.getElementById('qrModal');
          qrModal.addEventListener('hidden.bs.modal', function() {
            stopWebcam();
          });
        </script>
      </div>
    </div>
  </div>
</div>