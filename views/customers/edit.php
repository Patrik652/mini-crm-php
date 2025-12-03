<?php
/**
 * Customer Edit View
 * Form for editing an existing customer
 */

// Set page title
$pageTitle = 'Upraviť zákazníka';

// Include header
require_once __DIR__ . '/../partials/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <h2 class="page-title">Upraviť zákazníka</h2>
    <div class="page-actions">
        <a href="/index.php" class="btn btn-secondary">
            <span>←</span> Späť na zoznam
        </a>
    </div>
</div>

<!-- Customer Info Card -->
<div style="margin-bottom: 1.5rem; padding: 1rem 1.5rem; background-color: var(--primary-light); border-left: 4px solid var(--primary-color); border-radius: var(--radius-md);">
    <p style="margin: 0; color: var(--gray-700); font-size: 0.875rem;">
        <strong>Upravujete:</strong> <?php echo htmlspecialchars($customer['name']); ?>
        <span class="text-muted">(ID: #<?php echo $customer['id']; ?>)</span>
    </p>
</div>

<!-- Customer Form -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Základné informácie</h3>
    </div>

    <div class="card-body">
        <form method="POST" action="/index.php?action=update&id=<?php echo $customer['id']; ?>">
            <!-- Name Field -->
            <div class="form-group">
                <label for="name" class="form-label form-label-required">Meno</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    placeholder="Zadajte meno zákazníka"
                    value="<?php echo htmlspecialchars($customer['name']); ?>"
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
                    value="<?php echo htmlspecialchars($customer['email']); ?>"
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
                    value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>"
                >
                <span class="form-help">Telefónne číslo (nepovinné, napr. +421 901 234 567)</span>
            </div>

            <!-- Metadata -->
            <div style="padding: 1rem; background-color: var(--gray-50); border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                <h4 style="font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--gray-700);">
                    📊 Metadata
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.8125rem; color: var(--gray-600);">
                    <div>
                        <strong>Vytvorené:</strong><br>
                        <?php
                            $createdDate = new DateTime($customer['created_at']);
                            echo $createdDate->format('d.m.Y H:i:s');
                        ?>
                    </div>
                    <div>
                        <strong>Posledná aktualizácia:</strong><br>
                        <?php
                            $updatedDate = new DateTime($customer['updated_at']);
                            echo $updatedDate->format('d.m.Y H:i:s');
                        ?>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="card-footer" style="margin: 0 -2rem -2rem; padding: 1.5rem 2rem;">
                <div style="display: flex; gap: 1rem; justify-content: space-between; align-items: center;">
                    <a
                        href="/index.php?action=delete&id=<?php echo $customer['id']; ?>"
                        class="btn btn-danger btn-delete"
                        title="Vymazať zákazníka"
                    >
                        <span>🗑️</span> Vymazať
                    </a>

                    <div style="display: flex; gap: 1rem;">
                        <a href="/index.php" class="btn btn-secondary">
                            Zrušiť
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span>💾</span> Uložiť zmeny
                        </button>
                    </div>
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
        <li>Email musí byť v platnom formáte a jedinečný (nesmie existovať u iného zákazníka)</li>
        <li>Telefónne číslo je nepovinné a môže obsahovať medzery a špeciálne znaky</li>
        <li>Po uložení budete presmerovaní na zoznam zákazníkov</li>
        <li>Vymazanie zákazníka je permanentné a nie je možné ho vrátiť späť</li>
    </ul>
</div>

<?php
// Include footer
require_once __DIR__ . '/../partials/footer.php';
?>
