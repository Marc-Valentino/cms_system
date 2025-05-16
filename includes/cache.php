<?php
/**
 * Cache utility class for the CMS system
 * Provides methods for storing and retrieving cached data
 */
class Cache {
    private static $instance = null;
    private $cache_dir;
    private $cache_enabled = true;
    private $cache_lifetime = 3600; // Default: 1 hour
    
    /**
     * Constructor - creates cache directory if it doesn't exist
     */
    private function __construct() {
        $this->cache_dir = dirname(__DIR__) . '/cache/';
        
        // Create cache directory if it doesn't exist
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Set cache configuration
     */
    public function setConfig($enabled = true, $lifetime = 3600) {
        $this->cache_enabled = $enabled;
        $this->cache_lifetime = $lifetime;
    }
    
    /**
     * Generate a cache key from parameters
     */
    public function generateKey($name, $params = []) {
        $key = $name;
        if (!empty($params)) {
            $key .= '_' . md5(serialize($params));
        }
        return preg_replace('/[^a-zA-Z0-9_]/', '_', $key);
    }
    
    /**
     * Check if a cache file exists and is valid
     */
    public function exists($key) {
        if (!$this->cache_enabled) {
            return false;
        }
        
        $cache_file = $this->cache_dir . $key . '.cache';
        
        if (!file_exists($cache_file)) {
            return false;
        }
        
        $modified = filemtime($cache_file);
        if ((time() - $modified) > $this->cache_lifetime) {
            $this->delete($key);
            return false;
        }
        
        return true;
    }
    
    /**
     * Get data from cache
     */
    public function get($key) {
        if (!$this->exists($key)) {
            return null;
        }
        
        $cache_file = $this->cache_dir . $key . '.cache';
        $data = file_get_contents($cache_file);
        
        return unserialize($data);
    }
    
    /**
     * Save data to cache
     */
    public function set($key, $data) {
        if (!$this->cache_enabled) {
            return false;
        }
        
        $cache_file = $this->cache_dir . $key . '.cache';
        $serialized = serialize($data);
        
        return file_put_contents($cache_file, $serialized) !== false;
    }
    
    /**
     * Delete a cache entry
     */
    public function delete($key) {
        $cache_file = $this->cache_dir . $key . '.cache';
        
        if (file_exists($cache_file)) {
            return unlink($cache_file);
        }
        
        return true;
    }
    
    /**
     * Clear all cache
     */
    public function clear() {
        $files = glob($this->cache_dir . '*.cache');
        
        foreach ($files as $file) {
            unlink($file);
        }
        
        return true;
    }
    
    /**
     * Start output buffering for page caching
     */
    public function startPageCache($key) {
        if (!$this->cache_enabled) {
            return false;
        }
        
        if ($this->exists($key)) {
            echo $this->get($key);
            return true;
        }
        
        ob_start();
        return false;
    }
    
    /**
     * End output buffering and save page cache
     */
    public function endPageCache($key) {
        if (!$this->cache_enabled) {
            return;
        }
        
        $content = ob_get_clean();
        $this->set($key, $content);
        
        echo $content;
    }
}
?>