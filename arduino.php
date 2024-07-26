<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>RFID UID Display</title>
    <script>
        function updateUID(uid) {
            document.getElementById('uidInput').value = uid;
        }

        // Create an EventSource to listen for server-sent events
        const eventSource = new EventSource('/rfid-uid');

        // Listen for messages from the server
        eventSource.onmessage = function(event) {
            updateUID(event.data);
        };
    </script>
</head>
<body>
    <input type="text" class="form-control form-control-sm" name="UID" id="uidInput" value="" readonly>
</body>
</html>
