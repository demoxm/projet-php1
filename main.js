/**
 * main.js - Logique JavaScript pour l'application de gestion de recettes
 * Gestion des modales, formulaires, recherche et filtres
 */

// ===== Modal Management =====
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        modal.classList.add('show');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
    }
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        event.target.classList.remove('show');
    }
}

// ===== Form Validation =====
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (input.value.trim() === '') {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });

    return isValid;
}

// Remove error class on input focus
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.remove('error');
        });
    });
});

// ===== Search & Filter Functions =====
function filterRecipesByCategory(category) {
    const table = document.getElementById('recipesTable');
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const categoryCell = row.getAttribute('data-category');
        if (category === 'all' || categoryCell === category) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function searchRecipes(searchTerm) {
    const table = document.getElementById('recipesTable');
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const title = row.getAttribute('data-title') || '';
        const ingredients = row.getAttribute('data-ingredients') || '';
        
        if (title.toLowerCase().includes(term) || ingredients.toLowerCase().includes(term)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// ===== Delete Confirmation =====
function confirmDelete(recipeName) {
    return confirm('Êtes-vous sûr de vouloir supprimer la recette "' + recipeName + '" ?');
}

// ===== Modal Detail View =====
function showRecipeDetail(recipeId) {
    const modal = document.getElementById('detailModal');
    if (!modal) return;

    // Fetch recipe details via AJAX or use inline data
    const row = document.querySelector(`tr[data-recipe-id="${recipeId}"]`);
    if (row) {
        const title = row.getAttribute('data-title');
        const category = row.getAttribute('data-category');
        const ingredients = row.getAttribute('data-ingredients');
        const instructions = row.getAttribute('data-instructions');

        // Update modal content
        const detailContent = modal.querySelector('.modal-content');
        if (detailContent) {
            detailContent.innerHTML = `
                <span class="close" onclick="closeModal('detailModal')">&times;</span>
                <h2>${title}</h2>
                <p><strong>Catégorie:</strong> <span class="badge badge-${category.toLowerCase()}">${category}</span></p>
                <h3>Ingrédients</h3>
                <div class="recipe-content">${ingredients}</div>
                <h3>Instructions</h3>
                <div class="recipe-content">${instructions}</div>
                <div class="btn-group" style="margin-top: 1.5rem;">
                    <a href="modifier.php?id=${recipeId}" class="btn btn-secondary">Modifier</a>
                    <a href="supprimer.php?id=${recipeId}" class="btn btn-danger" onclick="return confirmDelete('${title}');">Supprimer</a>
                </div>
            `;
            modal.style.display = 'block';
        }
    }
}

// ===== Populate Category Select =====
function populateCategorySelect(selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;

    const categories = [
        { value: 'Entrée', label: '🥗 Entrée' },
        { value: 'Plat', label: '🍖 Plat' },
        { value: 'Dessert', label: '🍰 Dessert' }
    ];

    categories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.value;
        option.textContent = cat.label;
        select.appendChild(option);
    });
}

// ===== Character Counter for Textarea =====
function setupCharCounter(textareaId, counterId) {
    const textarea = document.getElementById(textareaId);
    const counter = document.getElementById(counterId);

    if (!textarea || !counter) return;

    textarea.addEventListener('input', function() {
        counter.textContent = this.value.length;
    });

    counter.textContent = textarea.value.length;
}

// ===== Alert Auto-Dismiss =====
function autoDismissAlerts(duration = 5000) {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, duration);
    });
}

// ===== Initialize Page =====
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    autoDismissAlerts(5000);

    // Setup category selects
    const categorySelects = document.querySelectorAll('[data-category-select="true"]');
    categorySelects.forEach(select => {
        if (select.options.length === 1) { // Only has placeholder
            populateCategorySelect(select.id);
        }
    });

    // Setup character counters
    const textareas = document.querySelectorAll('[data-char-counter="true"]');
    textareas.forEach(textarea => {
        setupCharCounter(textarea.id, textarea.id + '_count');
    });
});

// ===== Export Functions for Global Use =====
window.openModal = openModal;
window.closeModal = closeModal;
window.validateForm = validateForm;
window.filterRecipesByCategory = filterRecipesByCategory;
window.searchRecipes = searchRecipes;
window.confirmDelete = confirmDelete;
window.showRecipeDetail = showRecipeDetail;
