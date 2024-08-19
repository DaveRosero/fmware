$(document).ready(function () {
  const video = document.getElementById("video");
  const scannedImage = document.getElementById("scannedImage");
  const qrDataLabel = document.getElementById("qrData");
  const webcamButton = document.getElementById("webcamButton");
  const fileInput = document.getElementById("fileInput");
  const fileInputButton = document.getElementById("fileInputButton");

  // Variable to store selected order_ref
  let selectedOrderRef = '';

  // Function to set the selected order_ref
  function setSelectedOrderRef(orderRef) {
    selectedOrderRef = orderRef;
    console.log('Selected order_ref set to:', selectedOrderRef); // Debugging line
  }

  function showWebcam() {
    qrDataLabel.textContent = "";
    qrDataLabel.style.display = "none";
    scannedImage.style.display = "none";
    video.style.display = "block";
    startWebcamScanning();
  }

  function startWebcamScanning() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices
        .getUserMedia({ video: { facingMode: "environment" } })
        .then(function (stream) {
          video.srcObject = stream;
          video.play();

          video.onloadedmetadata = function () {
            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext('2d', { willReadFrequently: true });

            const intervalId = setInterval(() => {
              if (video.videoWidth > 0 && video.videoHeight > 0) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = ctx.getImageData(
                  0,
                  0,
                  canvas.width,
                  canvas.height
                );
                const code = jsQR(
                  imageData.data,
                  imageData.width,
                  imageData.height
                );

                if (code) {
                  const qrData = code.data;
                  qrDataLabel.textContent = "QR Code Data: " + qrData;
                  qrDataLabel.style.display = "block";
                  clearInterval(intervalId);
                  const tracks = video.srcObject.getTracks();
                  tracks.forEach((track) => track.stop());
                  scannedImage.src = canvas.toDataURL();
                  scannedImage.style.display = "block";
                  video.style.display = "none";

                  if (isUrl(qrData)) {
                    const qrOrderRef = extractOrderRef(qrData);
                    if (qrOrderRef === selectedOrderRef) {
                      console.log('Redirecting to:', qrData); // Debugging line
                      window.location.href = qrData;
                    } else {
                      alert('QR code does not match the selected order.');
                    }
                  } else {
                    qrDataLabel.textContent = "Invalid QR code URL: " + qrData;
                  }
                }
              }
            }, 300);
          };
        })
        .catch(function (error) {
          qrDataLabel.textContent = "Error accessing camera: " + error;
        });
    } else {
      qrDataLabel.textContent = "getUserMedia is not supported";
    }
  }

  function stopVideoStream() {
    const stream = video.srcObject;
    if (stream) {
      const tracks = stream.getTracks();
      tracks.forEach((track) => track.stop());
      video.srcObject = null;
    }
  }

  function isUrl(str) {
    try {
      const url = new URL(str);
      return url.protocol === 'http:' || url.protocol === 'https:';
    } catch (e) {
      return false;
    }
  }

  function extractOrderRef(url) {
    const parts = url.split('/');
    return parts[parts.length - 1];
  }

  fileInput.addEventListener("change", function (event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function (event) {
      const image = new Image();
      image.onload = function () {
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        canvas.width = image.width;
        canvas.height = image.height;
        ctx.drawImage(image, 0, 0);
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
          qrDataLabel.textContent = "QR Code Data: " + code.data;
          qrDataLabel.style.display = "block";
          scannedImage.src = event.target.result;
          scannedImage.style.display = "block";
          video.style.display = "none";

          if (isUrl(code.data)) {
            const qrOrderRef = extractOrderRef(code.data);
            if (qrOrderRef === selectedOrderRef) {
              console.log('Redirecting to:', code.data); // Debugging line
              window.location.href = code.data;
            } else {
              alert('QR code does not match the selected order.');
            }
          } else {
            qrDataLabel.textContent = "Invalid QR code URL: " + code.data;
          }
        } else {
          qrDataLabel.textContent = "No QR code found in the selected image";
          qrDataLabel.style.display = "block";
        }
      };
      image.src = event.target.result;
    };
    reader.readAsDataURL(file);
  });

  fileInputButton.addEventListener("click", function () {
    fileInput.click();
  });

  webcamButton.addEventListener("click", function () {
    $("#qr-scanner-modal").modal("show");
    showWebcam();
  });

  $("#qr-scanner-modal").on("hidden.bs.modal", function () {
    stopVideoStream();
  });

  // Add event listener to view-order buttons
  $(document).on('click', '.view-order-btn', function () {
    const orderRef = $(this).data('order-ref');
    setSelectedOrderRef(orderRef);
  });
});
