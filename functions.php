<?php

require_once get_template_directory() . '/include/Likes.php';
require_once get_template_directory() . '/include/AdminPage.php';



function egeland_enqueue_assets()
{

    wp_enqueue_script(
        'egeland-index-script',
        get_template_directory_uri() . '/dist/js/index.js',
        [],
        null,
        true
    );

    wp_enqueue_style(
        'egeland-main-style',
        get_template_directory_uri() . '/dist/css/main.css',
        [],
        null
    );
}
add_action('wp_enqueue_scripts', 'egeland_enqueue_assets');


function custom_pagination($total_pages = null)
{
    global $wp_query;
    $total_pages = $total_pages ?: $wp_query->max_num_pages;
    if ($total_pages <= 1) return;
    $current_page = max(1, get_query_var('paged'));
    $output = '<div class="pagination">';
    $output .= '<a href="' . esc_url(get_pagenum_link($current_page - 1)) . '" class="prev' . ($current_page === 1 ? ' disabled' : '') . '"> <span class="arrow">❮</span> <span class="text">Назад</span> </a>';
    if ($current_page > 2) {
        $output .= '<a href="' . esc_url(get_pagenum_link(1)) . '">1</a>';
    }
    if ($current_page > 3) {
        $output .= '<span class="dots">...</span>';
    }
    for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++) {
        $output .= ($i === $current_page)
            ? '<a href="#" class="active">' . $i . '</a>'
            : '<a href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a>';
    }
    if ($current_page < $total_pages - 2) {
        $output .= '<span class="dots">...</span>';
    }
    if ($current_page < $total_pages - 1) {
        $output .= '<a href="' . esc_url(get_pagenum_link($total_pages)) . '">' . $total_pages . '</a>';
    }
    $output .= '<a href="' . esc_url(get_pagenum_link($current_page + 1)) . '" class="next' . ($current_page === $total_pages ? ' disabled' : '') . '"> <span class="text">Вперед</span> <span class="arrow">❯</span> </a>';
    $output .= '</div>';
    echo $output;
}
