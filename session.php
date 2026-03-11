<?php
/**
 * Session helpers for MovieStreamDB_19
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Redirect if not logged in.
 */
function require_login(string $redirectTo = '../index.php'): void
{
    if (empty($_SESSION['user_id'])) {
        header("Location: $redirectTo");
        exit;
    }
}

/**
 * Redirect unless the user has one of the allowed roles.
 */
function require_role(array $allowed, string $redirectTo = '../index.php'): void
{
    require_login($redirectTo);
    if (!in_array($_SESSION['role'] ?? '', $allowed, true)) {
        header("Location: $redirectTo");
        exit;
    }
}

/**
 * Is the user logged in?
 */
function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

/**
 * Current user role (or empty string).
 */
function current_role(): string
{
    return $_SESSION['role'] ?? '';
}
