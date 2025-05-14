<?php
// Cache configuration
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600); // 1 hour

// Initialize cache with configuration
require_once 'cache.php';
$cache = Cache::getInstance();
$cache->setConfig(CACHE_ENABLED, CACHE_LIFETIME);

// Function to clear all cache
function clear_all_cache() {
    $cache = Cache::getInstance();
    return $cache->clear();
}

// Function to clear specific cache
function clear_cache($key) {
    $cache = Cache::getInstance();
    return $cache->delete($key);
}

// Function to perform cached Supabase queries
function cached_supabase_query($table, $method, $data = null, $params = [], $cache_lifetime = CACHE_LIFETIME) {
    // Generate a cache key based on the query parameters
    $cache = Cache::getInstance();
    $cache_key = $cache->generateKey('supabase_' . $table . '_' . $method . '_' . json_encode($params));
    
    // Try to get from cache first
    $cached_result = $cache->get($cache_key);
    if ($cached_result !== false && CACHE_ENABLED) {
        return $cached_result;
    }
    
    // If not in cache or cache disabled, perform the actual query
    $result = supabase_query($table, $method, $data, $params);
    
    // Store in cache if successful and caching is enabled
    if ($result && CACHE_ENABLED) {
        $cache->set($cache_key, $result, $cache_lifetime);
    }
    
    return $result;
}
?>