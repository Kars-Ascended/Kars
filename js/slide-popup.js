class SlidePopup {
    constructor() {
        console.log('SlidePopup constructor called');
        this.init();
    }

    init() {
        // Find all popup buttons
        const buttons = document.querySelectorAll('.popup-button');
        console.log('Found popup buttons:', buttons.length);
        
        buttons.forEach(button => {
            const popup = button.nextElementSibling;
            console.log('Checking popup:', popup);
            
            if (popup && popup.classList.contains('slide-popup')) {
                console.log('Found valid popup');
                
                // Setup trigger button
                button.addEventListener('click', (e) => {
                    console.log('Button clicked');
                    e.preventDefault();
                    this.showPopup(popup);
                });

                // Setup close button
                const closeBtn = popup.querySelector('.close-popup');
                if (closeBtn) {
                    console.log('Found close button');
                    closeBtn.addEventListener('click', () => {
                        console.log('Close button clicked');
                        this.hidePopup(popup);
                    });
                }

                // Setup outside click
                document.addEventListener('click', (e) => {
                    if (!popup.contains(e.target) && !button.contains(e.target)) {
                        this.hidePopup(popup);
                    }
                });
            } else {
                console.log('No valid popup found for button');
            }
        });
    }

    showPopup(popup) {
        console.log('Showing popup');
        popup.classList.add('active');
        console.log('Popup classes after show:', popup.classList);
    }

    hidePopup(popup) {
        console.log('Hiding popup');
        popup.classList.remove('active');
        console.log('Popup classes after hide:', popup.classList);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new SlidePopup();
});