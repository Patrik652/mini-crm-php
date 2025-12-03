<?php
/**
 * Customer List View
 * Displays all customers with search, pagination, and actions
 */

// Set page title
$pageTitle = 'Zoznam zákazníkov';

// Include header
require_once __DIR__ . '/../partials/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <h2 class="page-title">Zákazníci</h2>
    <div class="page-actions">
        <a href="/index.php?action=create" class="btn btn-primary">
            <span>➕</span> Nový zákazník
        </a>
        <a href="/index.php?action=export<?php echo isset($search) && $search ? '&search=' . urlencode($search) : ''; ?>" class="btn btn-success">
            <span>📥</span> Export CSV
        </a>
    </div>
</div>

<!-- Search Bar -->
<div class="search-bar">
    <form method="GET" action="/index.php" style="display: flex; gap: 1rem; width: 100%;">
        <input
            type="text"
            name="search"
            class="form-input search-input"
            placeholder="Hľadať podľa mena alebo emailu..."
            value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>"
        >
        <button type="submit" class="btn btn-primary">
            <span>🔍</span> Hľadať
        </button>
        <?php if (isset($search) && $search): ?>
            <a href="/index.php" class="btn btn-secondary">
                <span>✕</span> Zrušiť
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Results Info -->
<?php if (isset($search) && $search): ?>
    <div style="margin-bottom: 1rem;">
        <p class="text-muted">
            Výsledky vyhľadávania pre "<strong><?php echo htmlspecialchars($search); ?></strong>":
            <?php echo $totalCustomers; ?> zákazník<?php echo $totalCustomers == 1 ? '' : ($totalCustomers < 5 ? 'i' : 'ov'); ?>
        </p>
    </div>
<?php endif; ?>

<!-- Customers Table -->
<div class="table-container">
    <?php if (!empty($customers)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Meno</th>
                    <th>Email</th>
                    <th>Telefón</th>
                    <th>Vytvorené</th>
                    <th class="text-right">Akcie</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td>
                            <span class="text-muted">#<?php echo htmlspecialchars($customer['id']); ?></span>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($customer['name']); ?></strong>
                        </td>
                        <td>
                            <a href="mailto:<?php echo htmlspecialchars($customer['email']); ?>">
                                <?php echo htmlspecialchars($customer['email']); ?>
                            </a>
                        </td>
                        <td>
                            <?php if (!empty($customer['phone'])): ?>
                                <a href="tel:<?php echo htmlspecialchars($customer['phone']); ?>">
                                    <?php echo htmlspecialchars($customer['phone']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="text-sm text-muted">
                                <?php
                                    $date = new DateTime($customer['created_at']);
                                    echo $date->format('d.m.Y H:i');
                                ?>
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a
                                    href="/index.php?action=edit&id=<?php echo $customer['id']; ?>"
                                    class="btn btn-secondary btn-sm"
                                    title="Upraviť zákazníka"
                                >
                                    ✏️ Upraviť
                                </a>
                                <a
                                    href="/index.php?action=delete&id=<?php echo $customer['id']; ?>"
                                    class="btn btn-danger btn-sm btn-delete"
                                    title="Vymazať zákazníka"
                                >
                                    🗑️ Vymazať
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- Empty State -->
        <div class="table-empty">
            <div class="table-empty-icon">📋</div>
            <p class="table-empty-text">
                <?php if (isset($search) && $search): ?>
                    Nenašli sa žiadni zákazníci pre vyhľadávací výraz "<?php echo htmlspecialchars($search); ?>"
                <?php else: ?>
                    Zatiaľ nemáte žiadnych zákazníkov
                <?php endif; ?>
            </p>
            <?php if (!isset($search) || !$search): ?>
                <a href="/index.php?action=create" class="btn btn-primary" style="margin-top: 1rem;">
                    <span>➕</span> Pridať prvého zákazníka
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <div class="pagination-info">
            Strana <?php echo $currentPage; ?> z <?php echo $totalPages; ?>
            (celkom <?php echo $totalCustomers; ?> zákazník<?php echo $totalCustomers == 1 ? '' : ($totalCustomers < 5 ? 'i' : 'ov'); ?>)
        </div>

        <div class="pagination-buttons">
            <?php if ($currentPage > 1): ?>
                <a
                    href="/index.php?page=<?php echo $currentPage - 1; ?><?php echo isset($search) && $search ? '&search=' . urlencode($search) : ''; ?>"
                    class="btn btn-secondary"
                >
                    ← Predchádzajúca
                </a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>
                    ← Predchádzajúca
                </button>
            <?php endif; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a
                    href="/index.php?page=<?php echo $currentPage + 1; ?><?php echo isset($search) && $search ? '&search=' . urlencode($search) : ''; ?>"
                    class="btn btn-secondary"
                >
                    Ďalšia →
                </a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>
                    Ďalšia →
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Statistics (optional) -->
<?php if (!empty($customers)): ?>
    <div style="margin-top: 2rem; text-align: center; color: var(--gray-500); font-size: 0.875rem;">
        <p>
            Zobrazuje sa <?php echo count($customers); ?> z <?php echo $totalCustomers; ?> zákazníkov
        </p>
    </div>
<?php endif; ?>

<?php
// Include footer
require_once __DIR__ . '/../partials/footer.php';
?>
