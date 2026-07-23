// Theme Manager - Systeme de theme Dark/Claire
class ThemeManager {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.init();
    }

    init() {
        this.applyTheme();
        this.createToggleButton();
        this.bindEvents();
    }

    applyTheme() {
        const body = document.body;
        const isDark = this.theme === 'dark';

        // Appliquer la classe au body
        body.classList.toggle('dark-theme', isDark);
        body.classList.toggle('light-theme', !isDark);

        // Sauvegarder la preference
        localStorage.setItem('theme', this.theme);
    }

    createToggleButton() {
        // Creer le bouton toggle s'il n'existe pas deja
        if (!document.getElementById('theme-toggle')) {
            const toggleBtn = document.createElement('button');
            toggleBtn.id = 'theme-toggle';
            toggleBtn.className = 'btn btn-outline-secondary btn-sm';
            toggleBtn.innerHTML = '<i class="fas fa-moon"></i>';
            toggleBtn.title = 'Basculer vers le theme sombre';

            // Trouver la navbar pour y ajouter le bouton
            const navbarNav = document.querySelector('.navbar-nav');
            if (navbarNav) {
                const navItem = document.createElement('li');
                navItem.className = 'nav-item';
                navItem.appendChild(toggleBtn);
                navbarNav.appendChild(navItem);
            }
        }

        this.updateToggleButton();
    }

    updateToggleButton() {
        const toggleBtn = document.getElementById('theme-toggle');
        if (toggleBtn) {
            const isDark = this.theme === 'dark';
            toggleBtn.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            toggleBtn.title = isDark ? 'Basculer vers le theme clair' : 'Basculer vers le theme sombre';
            toggleBtn.className = `btn ${isDark ? 'btn-outline-warning' : 'btn-outline-secondary'} btn-sm`;
        }
    }

    toggleTheme() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
        this.applyTheme();
        this.updateToggleButton();
    }

    bindEvents() {
        const toggleBtn = document.getElementById('theme-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleTheme());
        }
    }
}

// Initialiser le gestionnaire de theme quand le DOM est charge
document.addEventListener('DOMContentLoaded', () => {
    new ThemeManager();
});