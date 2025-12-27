/**
 * ============================================
 * FILE: js/navigation.js
 * ============================================
 */
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mainNav = document.getElementById('main-nav');
    
    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mainNav.contains(event.target) && !menuToggle.contains(event.target)) {
                mainNav.classList.remove('active');
            }
        });

        // Handle submenu toggle on mobile
        const submenuToggles = mainNav.querySelectorAll('.menu-item-has-children > a');
        submenuToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                // Only prevent default and toggle on mobile
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const parentLi = toggle.parentElement;
                    parentLi.classList.toggle('active');
                }
            });
        });

        // Close menu when clicking a top-level link (without submenu)
        const topLevelLinks = mainNav.querySelectorAll('ul:first-child > li:not(.menu-item-has-children) > a');
        topLevelLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    mainNav.classList.remove('active');
                }
            });
        });
    }
    
    // Search Overlay Toggle
    const searchToggle = document.getElementById('search-toggle');
    const searchOverlay = document.getElementById('search-overlay');
    const searchClose = document.getElementById('search-close');
    const searchField = searchOverlay ? searchOverlay.querySelector('.search-field') : null;
    
    if (searchToggle && searchOverlay) {
        searchToggle.addEventListener('click', function() {
            searchOverlay.classList.add('active');
            // Focus search field when overlay opens
            if (searchField) {
                setTimeout(function() {
                    searchField.focus();
                }, 100);
            }
            // Prevent body scroll when overlay is open
            document.body.style.overflow = 'hidden';
        });
    }
    
    function closeSearchOverlay() {
        if (searchOverlay) {
            searchOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    if (searchClose) {
        searchClose.addEventListener('click', closeSearchOverlay);
    }
    
    // Close overlay when clicking outside
    if (searchOverlay) {
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                closeSearchOverlay();
            }
        });
    }
    
    // Close overlay with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchOverlay && searchOverlay.classList.contains('active')) {
            closeSearchOverlay();
        }
    });
});
