<!-- QR Code Scanner Modal -->
<div class="modal fade" id="qr-scanner-modal" tabindex="-1" aria-labelledby="qr-scanner-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="qr-scanner-modal-label">QR Code Scanner</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
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
            <button class="button" id="webcamButton">Webcam QR Code Scanner</button>
            <!-- Label for file input -->
            <label for="fileInput">Scan QR Code from Image</label>
            <!-- File input for selecting an image file -->
            <input type="file" accept="image/*" id="fileInput">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
