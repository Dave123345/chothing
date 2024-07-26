// Function to fetch display data from scanner.php
const fetchDisplayData = async () => {
    try {
        const response = await fetch('scanner.php');
        if (!response.ok) {
            throw new Error('Failed to fetch display data');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        throw new Error(`Error fetching display data: ${error.message}`);
    }
};

// Function to update the display with fetched data
const updateDisplay = (data) => {
    // Update the display here based on the fetched data
    console.log('Display updated with data:', data);
};

// Function to handle errors in fetching display data
const handleDisplayError = (error) => {
    console.error('Error fetching display data:', error);
    // Handle error display here, e.g., show an error message to the user
};

// Function to fetch display data and update the display
const fetchAndUpdateDisplay = async () => {
    try {
        const displayData = await fetchDisplayData();
        updateDisplay(displayData);
    } catch (error) {
        handleDisplayError(error);
    }
};

// Call fetchAndUpdateDisplay when DOM is fully loaded
domReady(fetchAndUpdateDisplay);
