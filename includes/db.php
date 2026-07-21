<?php
// Shared PDO database connection for the Obituary Management Platform.
// Default values match a fresh XAMPP install (root user, no password).

$db_host = 'localhost';
$db_name = 'obituary_platform';
$db_user = 'root';
$db_pass = '';

$dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    die('<h1>Database connection failed</h1><p>Please make sure MySQL is running and the '
        . '<code>obituary_platform</code> database has been imported from database/schema.sql.</p>'
        . '<p><strong>Details:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>');
}

function slugify(string $name): string
{
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

// Parses a DD/MM/YYYY string into a DateTime, returning null if it isn't a real calendar date.
function parse_dmy_date(string $value): ?DateTime
{
    $date = DateTime::createFromFormat('!d/m/Y', trim($value));
    if (!$date) {
        return null;
    }
    $errors = DateTime::getLastErrors();
    if ($errors && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
        return null;
    }
    return $date;
}

// Formats a MySQL Y-m-d date string for display as DD/MM/YYYY.
function format_dmy(string $sqlDate): string
{
    $date = DateTime::createFromFormat('Y-m-d', $sqlDate);
    return $date ? $date->format('d/m/Y') : $sqlDate;
}
