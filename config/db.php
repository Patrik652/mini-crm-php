<?php

/**
 * Database Configuration
 *
 * Provides PDO connection with error handling
 * Uses environment variables for database credentials
 */

class Database
{
    private static ?PDO $connection = null;

    /**
     * Get PDO database connection (Singleton)
     *
     * @return PDO
     * @throws PDOException
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                // Load environment variables
                $host = getenv('DB_HOST') ?: 'db';
                $port = getenv('DB_PORT') ?: '3306';
                $dbname = getenv('DB_NAME') ?: 'crm_db';
                $username = getenv('DB_USER') ?: 'root';
                $password = getenv('DB_PASSWORD') ?: 'secret';

                // DSN (Data Source Name)
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

                // PDO Options
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                // Create PDO instance
                self::$connection = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                // Log error (in production, log to file)
                error_log("Database Connection Error: " . $e->getMessage());
                throw new PDOException("Could not connect to database. Please try again later.");
            }
        }

        return self::$connection;
    }

    /**
     * Close database connection
     */
    public static function closeConnection(): void
    {
        self::$connection = null;
    }
}
