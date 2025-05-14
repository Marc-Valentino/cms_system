<?php
/**
 * Generate pagination HTML
 * 
 * @param int $current_page Current page number
 * @param int $total_pages Total number of pages
 * @param string $url_pattern URL pattern with %d placeholder for page number
 * @return string HTML for pagination
 */
function generate_pagination($current_page, $total_pages, $url_pattern) {
    if ($total_pages <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    // Previous button
    if ($current_page > 1) {
        $html .= sprintf(
            '<li class="page-item"><a class="page-link" href="' . $url_pattern . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>',
            $current_page - 1
        );
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
    }
    
    // Page numbers
    $start_page = max(1, $current_page - 2);
    $end_page = min($total_pages, $current_page + 2);
    
    // Always show first page
    if ($start_page > 1) {
        $html .= sprintf('<li class="page-item"><a class="page-link" href="' . $url_pattern . '">1</a></li>', 1);
        if ($start_page > 2) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
        }
    }
    
    // Page links
    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $current_page) {
            $html .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $html .= sprintf('<li class="page-item"><a class="page-link" href="' . $url_pattern . '">' . $i . '</a></li>', $i);
        }
    }
    
    // Always show last page
    if ($end_page < $total_pages) {
        if ($end_page < $total_pages - 1) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
        }
        $html .= sprintf('<li class="page-item"><a class="page-link" href="' . $url_pattern . '">' . $total_pages . '</a></li>', $total_pages);
    }
    
    // Next button
    if ($current_page < $total_pages) {
        $html .= sprintf(
            '<li class="page-item"><a class="page-link" href="' . $url_pattern . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>',
            $current_page + 1
        );
    } else {
        $html .= '<li class="page-item disabled"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

/**
 * Calculate pagination values
 * 
 * @param int $total_items Total number of items
 * @param int $items_per_page Number of items per page
 * @param int $current_page Current page number
 * @return array Pagination values
 */
function calculate_pagination($total_items, $items_per_page, $current_page = 1) {
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'current_page' => $current_page,
        'total_pages' => $total_pages,
        'items_per_page' => $items_per_page,
        'total_items' => $total_items,
        'offset' => $offset
    ];
}
?>