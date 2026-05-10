document.addEventListener('DOMContentLoaded', function() {
    const navSearch = document.querySelector('.nav-search input');
    if (navSearch) {
        navSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !this.value.trim()) {
                e.preventDefault();
            }
        });
    }
    
    const shareButtons = document.querySelectorAll('.share-buttons a');
    shareButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            window.open(url, '_blank', 'width=600,height=400');
        });
    });
    
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.style.setProperty('--transition-speed', '0');
    }
});