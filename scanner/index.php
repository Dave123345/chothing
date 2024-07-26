<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Webcam Barcode Scanner</title>
<!-- Include ZXing JavaScript library -->
<script src="https://unpkg.com/@zxing/library@latest"></script>
</head>
<body>
<h1>Webcam Barcode Scanner</h1>
<div id="barcode-result"></div>
<video id="video" width="640" height="480" autoplay></video>
<script>
// Initialize ZXing barcode reader
const codeReader = new ZXing.BrowserBarcodeReader();

// Select the video element
const videoElement = document.getElementById('video');

// Add listener for barcode detection
codeReader.getVideoInputDevices()
    .then(videoInputDevices => {
        // Get the rear camera if available
        const rearCamera = videoInputDevices.find(device => device.kind === 'videoinput');
        if (rearCamera) {
            codeReader.decodeFromVideoDevice(rearCamera.deviceId, videoElement, (result, err) => {
                if (result) {
                    console.log('Barcode detected:', result.text);
                    document.getElementById('barcode-result').innerText = 'Barcode detected: ' + result.text;
                }
                if (err) {
                    console.error('Barcode decoding error:', err);
                }
            });
        } else {
            console.error('No video input devices found');
        }
    })
    .catch(err => {
        console.error('Error accessing video input devices:', err);
    });
</script>
</body>
</html>
