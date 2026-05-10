document.addEventListener('DOMContentLoaded', function() {
    // Navigation search
    const navSearch = document.querySelector('.nav-search input');
    if (navSearch) {
        navSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !this.value.trim()) {
                e.preventDefault();
            }
        });
    }
    
    // Share buttons
    const shareButtons = document.querySelectorAll('.share-buttons a');
    shareButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            window.open(url, '_blank', 'width=600,height=400');
        });
    });
    
    // Mobile menu toggle
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const navList = document.querySelector('.nav-list');
    if (mobileToggle && navList) {
        mobileToggle.addEventListener('click', function() {
            navList.classList.toggle('active');
        });
    }
    
    // Dark mode toggle
    window.toggleTheme = function() {
        const body = document.body;
        const icon = document.getElementById('theme-icon');
        
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            if (icon) icon.className = 'fa-solid fa-sun';
            localStorage.setItem('theme', 'dark');
        } else {
            if (icon) icon.className = 'fa-solid fa-moon';
            localStorage.setItem('theme', 'light');
        }
    };
    
    // Load saved theme
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
        const icon = document.getElementById('theme-icon');
        if (icon) icon.className = 'fa-solid fa-sun';
    }
    
    // Reduced motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.style.setProperty('--transition-speed', '0');
    }
});