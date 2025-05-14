<?php
// Supabase API credentials
define('SUPABASE_URL', 'https://zdqaglewecydgxffsjqp.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InpkcWFnbGV3ZWN5ZGd4ZmZzanFwIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDcxMjYwODYsImV4cCI6MjA2MjcwMjA4Nn0.yY1hT0rjN0GPFCXtQZI_flfKvNoDT1jrq8KZzX0gPCg');

/**
 * Function to make API calls to Supabase
 * 
 * @param string $table Table name
 * @param string $method HTTP method (GET, POST, PATCH, DELETE)
 * @param array|null $data Data to send (for POST, PATCH)
 * @param array|null $params Query parameters
 * @return array|bool Response data or false on error
 */
// Include cache class
require_once 'cache.php';

function supabase_query($table, $method = 'GET', $data = null, $params = []) {
    global $supabase_url, $supabase_key;
    
    // Get cache instance
    $cache = Cache::getInstance();
    
    // Only cache GET requests
    $use_cache = ($method === 'GET');
    
    // Generate cache key
    if ($use_cache) {
        $cache_key = $cache->generateKey("query_{$table}", [
            'method' => $method,
            'params' => $params
        ]);
        
        // Check if we have a cached result
        if ($cache->exists($cache_key)) {
            return $cache->get($cache_key);
        }
    }
    
    $url = SUPABASE_URL . '/rest/v1/' . $table;
    
    // Add query parameters if provided
    if ($params) {
        $queryString = [];
        foreach ($params as $key => $value) {
            $queryString[] = $key . '=' . urlencode($value);
        }
        if (!empty($queryString)) {
            $url .= '?' . implode('&', $queryString);
        }
    }
    
    // Initialize cURL
    $ch = curl_init();
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    // Set headers
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    // Add data for POST and PATCH requests
    if (($method === 'POST' || $method === 'PATCH') && $data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    // Execute cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Debug information
    error_log("Supabase API Call: $method $url");
    error_log("HTTP Code: $httpCode");
    error_log("Response: $response");
    
    // Check for cURL errors
    if ($response === false) {
        error_log("cURL Error: " . curl_error($ch));
        curl_close($ch);
        return false;
    }
    
    // Close cURL
    curl_close($ch);
    
    // Parse response
    $result = json_decode($response, true);
    
    // Store result in cache if it's a GET request
    if ($use_cache && $result !== false) {
        $cache->set($cache_key, $result);
    }
    
    return $result;
}

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>