// Function to open Google Maps with directions
function openDirections(address) {
    if (!address) {
        alert('Address is not available');
        return;
    }
    
    // Get user's current location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                const origin = userLat + ',' + userLng;
                
                // Create Google Maps URL with directions
                const mapsUrl = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${encodeURIComponent(address)}&travelmode=driving`;
                
                // Open in a new tab
                window.open(mapsUrl, '_blank');
            },
            function(error) {
                // If geolocation fails, just use the destination
                const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
                window.open(mapsUrl, '_blank');
                console.error("Error getting location: ", error);
            }
        );
    } else {
        // Fallback if geolocation is not supported
        const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
        window.open(mapsUrl, '_blank');
    }
}

// Add event listeners to direction buttons
document.addEventListener('DOMContentLoaded', function() {
    const directionButtons = document.querySelectorAll('.direction-btn');
    
    directionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const address = this.getAttribute('data-address');
            openDirections(address);
        });
    });
});