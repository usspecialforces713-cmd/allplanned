<?php
/**
 * Environment Configuration Loader
 * Loads variables from .env file and validates required values
 */

function loadEnv($path = __DIR__ . '/../.env') {
    if (!file_exists($path)) {
        throw new RuntimeException(".env file not found at: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Set environment variable if not already set
            if (!empty($key) && !getenv($key)) {
                putenv("$key=$value");
            }
        }
    }
}

/**
 * Get environment variable with optional default value
 */
function env($key, $default = null) {
    $value = getenv($key);
    return $value !== false ? $value : $default;
}

/**
 * Require that an environment variable is set
 */
function requireEnv(...$keys) {
    $missing = [];
    foreach ($keys as $key) {
        if (!getenv($key)) {
            $missing[] = $key;
        }
    }
    
    if (!empty($missing)) {
        throw new RuntimeException("Missing required environment variables: " . implode(', ', $missing));
    }
}

// Load .env file automatically
loadEnv();
?>
