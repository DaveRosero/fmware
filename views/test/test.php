<?php
	$url = 'https://fmware.shop/confirm-order/'.$code.'/'.$ref;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- Include jquery-qrcode -->
    <script type="text/javascript" src="/vendor/jQueryQr/src/jquery.qrcode.js"></script>
	<script type="text/javascript" src="/vendor/jQueryQr/src/qrcode.js"></script>
</head>
<body>
	<div id="qrcode"></div>

	<script>
		jQuery('#qrcode').qrcode("<?php echo $url; ?>");
	</script>
</body>
</html>
