<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Scanned Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Scanned Data</h1>
    <div class="container">
        <table class="table table-bordered bg-light">
            <colgroup>
                <col width="60%">
                <col width="35%">
                <col width="5%">
            </colgroup>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="order-items">
                
            </tbody>
        </table>
    </div>

    <script>
        // Function to add scanned data to the table
        const addScannedDataToTable = (scannedData) => {
            const orderItemsTable = document.getElementById('order-items');
            const row = document.createElement('tr');
            const nameCell = document.createElement('td');
            const priceCell = document.createElement('td');
            const emptyCell = document.createElement('td');

            nameCell.textContent = scannedData.name;
            priceCell.textContent = scannedData.price;

            row.appendChild(nameCell);
            row.appendChild(priceCell);
            row.appendChild(emptyCell);

            orderItemsTable.appendChild(row);
        };

        // Function to parse the query parameter and display the scanned data
        const displayScannedData = () => {
            const urlParams = new URLSearchParams(window.location.search);
            const dataParam = urlParams.get('data');

            if (!dataParam) {
                // If no data parameter is found, display an error message
                displayErrorMessage('No data found.');
                return;
            }

            try {
                // Attempt to parse the data parameter as JSON
                const scannedDataArray = JSON.parse(decodeURIComponent(dataParam));

                // Check if the scannedData is an array
                if (!Array.isArray(scannedDataArray)) {
                    throw new Error('Invalid data format.');
                }

                // Display the scanned data in the HTML table
                scannedDataArray.forEach(scannedData => {
                    addScannedDataToTable(scannedData);
                });
            } catch (error) {
                // If an error occurs during parsing or displaying data, show an error message
                displayErrorMessage('Error displaying scanned data.');
            }
        };

        // Function to display an error message
        const displayErrorMessage = (message) => {
            const errorElement = document.createElement('p');
            errorElement.textContent = message;
            document.body.appendChild(errorElement);
        };

        // Call the function to display scanned data when the page loads
        displayScannedData();
    </script>
</body>
</html>
