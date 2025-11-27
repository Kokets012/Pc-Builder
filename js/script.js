/*
 * PC Builder JavaScript
 * Handles real-time updates and form interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Component selection handling
    const componentSelects = document.querySelectorAll('.component-select');
    
    componentSelects.forEach(select => {
        select.addEventListener('change', function() {
            const category = this.getAttribute('data-category');
            const componentId = this.value;
            
            // Update hidden form fields
            document.getElementById('categoryInput').value = category;
            
            // Submit form
            document.getElementById('pcBuilderForm').submit();
        });
    });
    
    // Update summary display
    updateSummaryDisplay();
});

// Function to update the build summary
function updateSummaryDisplay() {
    const componentsList = document.getElementById('componentsList');
    const totalPriceElement = document.getElementById('totalPrice');
    
    // This would be enhanced with AJAX in a more advanced version
    // For now, it updates on page load based on PHP data
}

// Price calculation function
function calculateTotal() {
    let total = 0;
    const selects = document.querySelectorAll('.component-select');
    
    selects.forEach(select => {
        if (select.value) {
            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            total += price;
        }
    });
    
    return total;
}