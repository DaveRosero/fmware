<?php

// Replace 'YOUR_DATA' with the actual data you want to encode
$data = 'azul';

// Replace 'CODE128' with the desired barcode type
$barcodeType = 'CODE128';

// Build the API URL
$apiUrl = "https://barcode.tec-it.com/barcode.ashx?data={$data}&type={$barcodeType}";

// Make the HTTP request to the API
$barcodeImage = file_get_contents($apiUrl);

// Check if the request was successful
if ($barcodeImage !== false) {
    // Display the barcode image
    header('Content-Type: image/png');
    echo $barcodeImage;
} else {
    // Handle the error
    echo 'Error fetching barcode from API.';
}
