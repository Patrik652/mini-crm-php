<?php

/**
 * Customer Model
 *
 * Handles all database operations for customers
 * Uses PDO Prepared Statements for security
 */

class CustomerModel
{
    private PDO $db;

    /**
     * Constructor
     *
     * @param PDO $db PDO database connection
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get all customers with pagination
     *
     * @param int $limit Number of records per page
     * @param int $offset Starting record
     * @return array List of customers
     */
    public function getAll(int $limit = 10, int $offset = 0): array
    {
        try {
            $sql = "SELECT id, name, email, phone, created_at, updated_at
                    FROM customers
                    ORDER BY created_at DESC
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error fetching customers: " . $e->getMessage());
            throw new Exception("Failed to retrieve customers.");
        }
    }

    /**
     * Get customer by ID
     *
     * @param int $id Customer ID
     * @return array|null Customer data or null if not found
     */
    public function getById(int $id): ?array
    {
        try {
            $sql = "SELECT id, name, email, phone, created_at, updated_at
                    FROM customers
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching customer by ID: " . $e->getMessage());
            throw new Exception("Failed to retrieve customer.");
        }
    }

    /**
     * Search customers by name or email
     *
     * @param string $query Search query
     * @param int $limit Number of records
     * @param int $offset Starting record
     * @return array Matching customers
     */
    public function search(string $query, int $limit = 10, int $offset = 0): array
    {
        try {
            $sql = "SELECT id, name, email, phone, created_at, updated_at
                    FROM customers
                    WHERE name LIKE :query1 OR email LIKE :query2
                    ORDER BY created_at DESC
                    LIMIT :limit OFFSET :offset";

            $stmt = $this->db->prepare($sql);
            $searchTerm = "%{$query}%";
            $stmt->bindValue(':query1', $searchTerm, PDO::PARAM_STR);
            $stmt->bindValue(':query2', $searchTerm, PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error searching customers: " . $e->getMessage());
            throw new Exception("Failed to search customers.");
        }
    }

    /**
     * Create new customer
     *
     * @param array $data Customer data (name, email, phone)
     * @return int Last inserted ID
     * @throws Exception If validation fails or database error occurs
     */
    public function create(array $data): int
    {
        // Validate required fields
        if (empty($data['name']) || empty($data['email'])) {
            throw new Exception("Name and email are required.");
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Check if email already exists
        if ($this->emailExists($data['email'])) {
            throw new Exception("Email already exists.");
        }

        try {
            $sql = "INSERT INTO customers (name, email, phone)
                    VALUES (:name, :email, :phone)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':phone', $data['phone'] ?? null, PDO::PARAM_STR);
            $stmt->execute();

            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error creating customer: " . $e->getMessage());
            throw new Exception("Failed to create customer.");
        }
    }

    /**
     * Update existing customer
     *
     * @param int $id Customer ID
     * @param array $data Updated customer data
     * @return bool True on success
     * @throws Exception If validation fails or database error occurs
     */
    public function update(int $id, array $data): bool
    {
        // Validate required fields
        if (empty($data['name']) || empty($data['email'])) {
            throw new Exception("Name and email are required.");
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Check if email exists for another customer
        if ($this->emailExistsForAnotherCustomer($data['email'], $id)) {
            throw new Exception("Email already exists for another customer.");
        }

        try {
            $sql = "UPDATE customers
                    SET name = :name, email = :email, phone = :phone
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':phone', $data['phone'] ?? null, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating customer: " . $e->getMessage());
            throw new Exception("Failed to update customer.");
        }
    }

    /**
     * Delete customer
     *
     * @param int $id Customer ID
     * @return bool True on success
     */
    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM customers WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting customer: " . $e->getMessage());
            throw new Exception("Failed to delete customer.");
        }
    }

    /**
     * Get total count of customers
     *
     * @param string|null $query Optional search query
     * @return int Total count
     */
    public function count(?string $query = null): int
    {
        try {
            if ($query) {
                $sql = "SELECT COUNT(*) as total FROM customers
                        WHERE name LIKE :query1 OR email LIKE :query2";
                $stmt = $this->db->prepare($sql);
                $searchTerm = "%{$query}%";
                $stmt->bindValue(':query1', $searchTerm, PDO::PARAM_STR);
                $stmt->bindValue(':query2', $searchTerm, PDO::PARAM_STR);
            } else {
                $sql = "SELECT COUNT(*) as total FROM customers";
                $stmt = $this->db->prepare($sql);
            }

            $stmt->execute();
            $result = $stmt->fetch();

            return (int) ($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Error counting customers: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Check if email exists
     *
     * @param string $email Email to check
     * @return bool True if exists
     */
    private function emailExists(string $email): bool
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM customers WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Check if email exists for another customer (for update validation)
     *
     * @param string $email Email to check
     * @param int $excludeId Customer ID to exclude from check
     * @return bool True if exists for another customer
     */
    private function emailExistsForAnotherCustomer(string $email, int $excludeId): bool
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM customers
                    WHERE email = :email AND id != :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
