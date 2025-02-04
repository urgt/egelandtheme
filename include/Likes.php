<?php
class WP_Like_Dislike_Rating
{
    public function __construct()
    {
        add_action('init', [$this, 'maybe_create_table']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_update_rating', [$this, 'ajax_update_rating']);
        add_action('wp_ajax_nopriv_update_rating', [$this, 'ajax_update_rating']);
        add_action('wp_ajax_get_rating', [$this, 'ajax_get_rating']);
        add_action('wp_ajax_nopriv_get_rating', [$this, 'ajax_get_rating']);
    }
    public function maybe_create_table()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'post_likes';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            vote TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY post_ip (post_id, ip_address)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'like-dislike-rating',
            get_template_directory_uri() . '/dist/js/like_dislike_rating.js',
            ['jquery'],
            '1.0',
            true
        );
        wp_localize_script(
            'like-dislike-rating',
            'LDR',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('like-dislike-rating-nonce')
            ]
        );
    }
    public function ajax_update_rating()
    {
        check_ajax_referer('like-dislike-rating-nonce', 'nonce');
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $vote = isset($_POST['vote']) ? intval($_POST['vote']) : 0;
        if (!$post_id || ($vote !== 1 && $vote !== -1)) {
            wp_send_json_error('Неверные данные');
        }
        $ip_address = $_SERVER['REMOTE_ADDR'];
        global $wpdb;
        $table = $wpdb->prefix . 'post_likes';
        $existing_vote = $wpdb->get_var($wpdb->prepare(
            "SELECT vote FROM $table WHERE post_id = %d AND ip_address = %s",
            $post_id,
            $ip_address
        ));
        if (null === $existing_vote) {
            $wpdb->insert(
                $table,
                ['post_id' => $post_id, 'ip_address' => $ip_address, 'vote' => $vote],
                ['%d', '%s', '%d']
            );
        } else {
            $new_vote = (intval($existing_vote) === $vote) ? 0 : $vote;
            $wpdb->update(
                $table,
                ['vote' => $new_vote],
                ['post_id' => $post_id, 'ip_address' => $ip_address],
                ['%d'],
                ['%d', '%s']
            );
        }
        $rating = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(vote) FROM $table WHERE post_id = %d",
            $post_id
        ));
        $rating = null === $rating ? 0 : intval($rating);
        wp_send_json_success(['rating' => $rating]);
    }
    public function ajax_get_rating()
    {
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        if (!$post_id) {
            wp_send_json_error('Неверный ID поста');
        }
        global $wpdb;
        $table = $wpdb->prefix . 'post_likes';
        $rating = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(vote) FROM $table WHERE post_id = %d",
            $post_id
        ));
        $rating = null === $rating ? 0 : intval($rating);
        wp_send_json_success(['rating' => $rating]);
    }
}
new WP_Like_Dislike_Rating();
