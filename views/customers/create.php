<?php
/**
 * Customer Create View
 * Form for creating a new customer
 */

// Set page title
$pageTitle = 'Nový zákazník';

// Include header
require_once __DIR__ . '/../partials/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <h2 class="page-title">Nový zákazník</h2>
    <div class="page-actions">
        <a href="/index.php" class="btn btn-secondary">
            <span>←</span> Späť na zoznam
        </a>
    </div>
</div>

<!-- Customer Form -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Základné informácie</h3>
    </div>

    <div class="card-body">
        <form method="POST" action="/index.php?action=store">
            <!-- Name Field -->
            <div class="form-group">
                <label for="name" class="form-label form-label-required">Meno</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    placeholder="Zadajte meno zákazníka"
                    value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                    required
                    autofocus
                >
                <span class="form-help">Celé meno zákazníka (napr. Ján Novák)</span>
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email" class="form-label form-label-required">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    placeholder="Zadajte emailovú adresu"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    required
                >
                <span class="form-help">Platná emailová adresa (napr. jan.novak@example.com)</span>
            </div>

            <!-- Phone Field -->
            <div class="form-group">
                <label for="phone" class="form-label">Telefón</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    class="form-input"
                    placeholder="Zadajte telefónne číslo"
                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                >
                <span class="form-help">Telefónne číslo (nepovinné, napr. +421 901 234 567)</span>
            </div>

            <!-- Form Actions -->
            <div class="card-footer" style="margin: 0 -2rem -2rem; padding: 1.5rem 2rem;">
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="/index.php" class="btn btn-secondary">
                        Zrušiť
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <span>💾</span> Vytvoriť zákazníka
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Help Section -->
<div style="margin-top: 2rem; padding: 1.5rem; background-color: var(--gray-100); border-radius: var(--radius-lg);">
    <h4 style="margin-bottom: 1rem; font-size: 1rem;">ℹ️ Pomocník</h4>
    <ul style="margin: 0; padding-left: 1.5rem; color: var(--gray-600); font-size: 0.875rem;">
        <li>Polia označené <span style="color: var(--danger-color);">*</span> sú povinné</li>
        <li>Email musí byť v platnom formáte a jedinečný (nesmie existovať v databáze)</li>
        <li>Telefónne číslo je nepovinné a môže obsahovať medzery a špeciálne znaky</li>
        <li>Po vytvorení budete presmerovaní na zoznam zákazníkov</li>
    </ul>
</div>

<?php
// Include footer
require_once __DIR__ . '/../partials/footer.php';
?>
