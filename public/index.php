<?php

/**
 * Front Controller - Entry Point
 *
 * Handles all incoming requests and routes them to appropriate controllers
 * Implements simple query-string based routing
 */

// Enable error reporting (development mode)
if (getenv('APP_ENV') === 'development' || getenv('APP_DEBUG') === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Set default timezone
date_default_timezone_set('Europe/Bratislava');

// Start session
session_start();

// Load configuration and classes
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/CustomerModel.php';
require_once __DIR__ . '/../src/CustomerController.php';

try {
    // Initialize database connection
    $db = Database::getConnection();

    // Initialize Model
    $customerModel = new CustomerModel($db);

    // Initialize Controller
    $controller = new CustomerController($customerModel);

    // Get action from query string (default: index)
    $action = $_GET['action'] ?? 'index';

    // Route to appropriate controller method
    switch ($action) {
        case 'index':
            // List all customers
            $controller->index();
            break;

        case 'create':
            // Show create form
            $controller->create();
            break;

        case 'store':
            // Store new customer
            $controller->store();
            break;

        case 'edit':
            // Show edit form
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                throw new Exception('Invalid customer ID.');
            }
            $controller->edit($id);
            break;

        case 'update':
            // Update customer
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                throw new Exception('Invalid customer ID.');
            }
            $controller->update($id);
            break;

        case 'delete':
            // Delete customer
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            if ($id <= 0) {
                throw new Exception('Invalid customer ID.');
            }
            $controller->delete($id);
            break;

        case 'export':
            // Export customers to CSV
            $controller->export();
            break;

        default:
            // Unknown action - redirect to index
            header('Location: index.php');
            exit;
    }
} catch (PDOException $e) {
    // Database connection error
    error_log('Database Error: ' . $e->getMessage());

    // Display user-friendly error
    http_response_code(500);
    echo '<h1>Database Connection Error</h1>';
    echo '<p>Unable to connect to the database. Please try again later.</p>';

    if (getenv('APP_ENV') === 'development') {
        echo '<hr>';
        echo '<h2>Debug Information:</h2>';
        echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    }
} catch (Exception $e) {
    // General application error
    error_log('Application Error: ' . $e->getMessage());

    // Redirect to index with error message
    header('Location: index.php?error=' . urlencode($e->getMessage()));
    exit;
}
