<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Mini CRM' : 'Mini CRM - Customer Management'; ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Favicon (optional) -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Meta Description -->
    <meta name="description" content="Mini CRM - Simple and efficient customer management system">
</head>
<body>
    <!-- Site Header -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <h1 class="site-title">
                    <a href="/index.php">Mini CRM</a>
                </h1>

                <nav class="site-nav">
                    <ul>
                        <li><a href="/index.php">Zákazníci</a></li>
                        <li><a href="/index.php?action=create">Nový zákazník</a></li>
                        <li><a href="/index.php?action=export">Export CSV</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <?php if (isset($success) || isset($error) || isset($_GET['success']) || isset($_GET['error'])): ?>
                <div class="flash-messages">
                    <?php if (isset($success) || isset($_GET['success'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success ?? $_GET['success']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error) || isset($_GET['error'])): ?>
                        <div class="alert alert-error" role="alert">
                            <?php echo htmlspecialchars($error ?? $_GET['error']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
