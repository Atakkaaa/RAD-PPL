// Function to handle search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const studentCards = document.querySelectorAll('.student-card');
            
            studentCards.forEach(card => {
                const name = card.querySelector('.student-name').textContent.toLowerCase();
                const nim = card.querySelector('.student-nim').textContent.toLowerCase();
                const address = card.querySelector('.student-address').textContent.toLowerCase();
                
                // Check if any of the fields contain the search term
                if (name.includes(searchTerm) || nim.includes(searchTerm) || address.includes(searchTerm)) {
                    card.style.display = '';
                    
                    // Highlight matching text
                    if (searchTerm !== '') {
                        highlightText(card, '.student-name', name, searchTerm);
                        highlightText(card, '.student-nim', nim, searchTerm);
                        highlightText(card, '.student-address', address, searchTerm);
                    } else {
                        // Remove highlights if search is cleared
                        card.querySelector('.student-name').innerHTML = name;
                        card.querySelector('.student-nim').innerHTML = nim;
                        card.querySelector('.student-address').innerHTML = address;
                    }
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});

// Function to highlight matching text
function highlightText(card, selector, text, searchTerm) {
    const element = card.querySelector(selector);
    if (!element) return;
    
    // Create a new text with highlighted search term
    const regex = new RegExp('(' + searchTerm + ')', 'gi');
    const newText = text.replace(regex, '<span class="search-result-highlight">$1</span>');
    
    element.innerHTML = newText;
}