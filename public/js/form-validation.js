/**
 * Form Validation for Register Questions Pages
 * 
 * This script validates form inputs to prevent invalid special characters
 * and provides real-time feedback to users.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Define the pattern for allowed characters (alphanumeric, spaces, and common punctuation)
    const validPattern = /^[a-zA-Z0-9\s.,!?()'\-_]+$/;
    
    // Error message for invalid characters
    const errorMessage = 'Please avoid using special characters like @, #, $, %, ^, &, *, +, =, <>, {}, [], \\, |, etc.';
    
    // Function to create and show error message
    function showError(input, message) {
        // Remove any existing error message
        const existingError = input.parentNode.querySelector('.validation-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Create error element
        const errorElement = document.createElement('p');
        errorElement.className = 'validation-error text-red-500 text-sm mt-1';
        errorElement.textContent = message;
        
        // Insert after the input
        input.parentNode.insertBefore(errorElement, input.nextSibling);
        
        // Highlight the input
        input.classList.add('border-red-500');
    }
    
    // Function to remove error message
    function removeError(input) {
        const errorElement = input.parentNode.querySelector('.validation-error');
        if (errorElement) {
            errorElement.remove();
        }
        input.classList.remove('border-red-500');
    }
    
    // Function to validate text input
    function validateTextInput(input) {
        const value = input.value;
        
        // Skip validation for empty inputs or non-text inputs
        if (!value || input.type === 'number' || input.type === 'select-one') {
            removeError(input);
            return true;
        }
        
        // Check if the input matches the valid pattern
        if (!validPattern.test(value)) {
            showError(input, errorMessage);
            return false;
        } else {
            removeError(input);
            return true;
        }
    }
    
    // Get all forms on the page
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Get all text inputs and textareas in the form
        const textInputs = form.querySelectorAll('input[type="text"], textarea');
        
        // Add input event listeners to each text input for real-time validation
        textInputs.forEach(input => {
            input.addEventListener('input', function() {
                validateTextInput(this);
            });
            
            // Also validate on blur (when user leaves the field)
            input.addEventListener('blur', function() {
                validateTextInput(this);
            });
        });
        
        // Add submit event listener to the form
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Validate all text inputs before submission
            textInputs.forEach(input => {
                if (!validateTextInput(input)) {
                    isValid = false;
                }
            });
            
            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
                
                // Scroll to the first error
                const firstError = form.querySelector('.validation-error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });
    
    // Add CSS for validation styling
    const style = document.createElement('style');
    style.textContent = `
        .validation-error {
            animation: fadeIn 0.3s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    `;
    document.head.appendChild(style);
}); 