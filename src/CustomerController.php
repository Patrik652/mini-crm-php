<?php

/**
 * Customer Controller
 *
 * Handles HTTP requests and coordinates between Model and Views
 * Implements CRUD operations with validation and error handling
 */

class CustomerController
{
    private CustomerModel $model;
    private int $itemsPerPage;

    /**
     * Constructor
     *
     * @param CustomerModel $model Customer model instance
     */
    public function __construct(CustomerModel $model)
    {
        $this->model = $model;
        $this->itemsPerPage = (int) (getenv('ITEMS_PER_PAGE') ?: 10);
    }

    /**
     * Display list of customers
     *
     * @return void
     */
    public function index(): void
    {
        try {
            // Get page number from query string
            $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
            $offset = ($page - 1) * $this->itemsPerPage;

            // Get search query if present
            $search = isset($_GET['search']) ? trim($_GET['search']) : null;

            // Fetch customers
            if ($search) {
                $customers = $this->model->search($search, $this->itemsPerPage, $offset);
                $totalCustomers = $this->model->count($search);
            } else {
                $customers = $this->model->getAll($this->itemsPerPage, $offset);
                $totalCustomers = $this->model->count();
            }

            // Calculate pagination
            $totalPages = ceil($totalCustomers / $this->itemsPerPage);

            // Pass data to view
            $data = [
                'customers' => $customers,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalCustomers' => $totalCustomers,
                'search' => $search,
                'success' => $_GET['success'] ?? null,
                'error' => $_GET['error'] ?? null
            ];

            // Load view (placeholder for now)
            $this->loadView('customers/index', $data);
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    /**
     * Display create customer form
     *
     * @return void
     */
    public function create(): void
    {
        $data = [
            'error' => $_GET['error'] ?? null
        ];

        $this->loadView('customers/create', $data);
    }

    /**
     * Store new customer
     *
     * @return void
     */
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?action=create&error=' . urlencode('Invalid request method.'));
            return;
        }

        try {
            // Validate and sanitize input
            $data = $this->validateCustomerData($_POST);

            // Create customer
            $customerId = $this->model->create($data);

            // Redirect with success message
            $this->redirect('index.php?success=' . urlencode('Customer created successfully.'));
        } catch (Exception $e) {
            // Redirect back with error
            $this->redirect('index.php?action=create&error=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Display edit customer form
     *
     * @param int $id Customer ID
     * @return void
     */
    public function edit(int $id): void
    {
        try {
            $customer = $this->model->getById($id);

            if (!$customer) {
                $this->redirect('index.php?error=' . urlencode('Customer not found.'));
                return;
            }

            $data = [
                'customer' => $customer,
                'error' => $_GET['error'] ?? null
            ];

            $this->loadView('customers/edit', $data);
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    /**
     * Update existing customer
     *
     * @param int $id Customer ID
     * @return void
     */
    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("index.php?action=edit&id={$id}&error=" . urlencode('Invalid request method.'));
            return;
        }

        try {
            // Validate and sanitize input
            $data = $this->validateCustomerData($_POST);

            // Update customer
            $this->model->update($id, $data);

            // Redirect with success message
            $this->redirect('index.php?success=' . urlencode('Customer updated successfully.'));
        } catch (Exception $e) {
            // Redirect back with error
            $this->redirect("index.php?action=edit&id={$id}&error=" . urlencode($e->getMessage()));
        }
    }

    /**
     * Delete customer
     *
     * @param int $id Customer ID
     * @return void
     */
    public function delete(int $id): void
    {
        try {
            // Check if customer exists
            $customer = $this->model->getById($id);

            if (!$customer) {
                $this->redirect('index.php?error=' . urlencode('Customer not found.'));
                return;
            }

            // Delete customer
            $this->model->delete($id);

            // Redirect with success message
            $this->redirect('index.php?success=' . urlencode('Customer deleted successfully.'));
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    /**
     * Export customers to CSV
     *
     * @return void
     */
    public function export(): void
    {
        try {
            // Get search query if present
            $search = isset($_GET['search']) ? trim($_GET['search']) : null;

            // Fetch all customers (no pagination for export)
            if ($search) {
                $customers = $this->model->search($search, 10000, 0);
            } else {
                $customers = $this->model->getAll(10000, 0);
            }

            // Set CSV headers
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="customers_' . date('Y-m-d_H-i-s') . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');

            // Open output stream
            $output = fopen('php://output', 'w');

            // Write UTF-8 BOM for Excel compatibility
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Write CSV header
            fputcsv($output, ['ID', 'Name', 'Email', 'Phone', 'Created At', 'Updated At']);

            // Write customer data
            foreach ($customers as $customer) {
                fputcsv($output, [
                    $customer['id'],
                    $customer['name'],
                    $customer['email'],
                    $customer['phone'] ?? '',
                    $customer['created_at'],
                    $customer['updated_at']
                ]);
            }

            fclose($output);
            exit;
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    /**
     * Validate customer data
     *
     * @param array $data Input data
     * @return array Validated and sanitized data
     * @throws Exception If validation fails
     */
    private function validateCustomerData(array $data): array
    {
        $validated = [];

        // Validate name (required, not empty)
        if (empty($data['name']) || trim($data['name']) === '') {
            throw new Exception('Name is required and cannot be empty.');
        }
        $validated['name'] = htmlspecialchars(trim($data['name']), ENT_QUOTES, 'UTF-8');

        // Validate email (required, valid format)
        if (empty($data['email']) || trim($data['email']) === '') {
            throw new Exception('Email is required and cannot be empty.');
        }

        $email = filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL);
        if ($email === false) {
            throw new Exception('Invalid email format.');
        }
        $validated['email'] = $email;

        // Validate phone (optional)
        if (isset($data['phone']) && trim($data['phone']) !== '') {
            $validated['phone'] = htmlspecialchars(trim($data['phone']), ENT_QUOTES, 'UTF-8');
        } else {
            $validated['phone'] = null;
        }

        return $validated;
    }

    /**
     * Load view file
     *
     * @param string $view View name (e.g., 'customers/index')
     * @param array $data Data to pass to view
     * @return void
     */
    private function loadView(string $view, array $data = []): void
    {
        // Extract data to variables
        extract($data);

        // Build view path
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        // Check if view exists
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // View not found - display placeholder
            echo "<h1>View Not Found</h1>";
            echo "<p>View file <code>{$viewPath}</code> does not exist.</p>";
            echo "<p><a href='index.php'>← Back to list</a></p>";
            echo "<hr>";
            echo "<h2>Data:</h2>";
            echo "<pre>" . htmlspecialchars(print_r($data, true)) . "</pre>";
        }
    }

    /**
     * Redirect to URL
     *
     * @param string $url Target URL
     * @return void
     */
    private function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Handle error and redirect to index
     *
     * @param string $message Error message
     * @return void
     */
    private function handleError(string $message): void
    {
        error_log("CustomerController Error: {$message}");
        $this->redirect('index.php?error=' . urlencode($message));
    }
}
