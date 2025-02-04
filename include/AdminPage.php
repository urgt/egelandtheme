<?php
class WP_Like_Dislike_Rating_Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_page']);
    }
    public function register_admin_page()
    {
        add_menu_page(
            'Голосования',
            'Голосования',
            'manage_options',
            'like-dislike-rating-admin',
            [$this, 'render_admin_page'],
            'dashicons-thumbs-up',
            80
        );
    }
    public function render_admin_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('У вас нет прав для доступа к этой странице.', 'text-domain'));
        }
        global $wpdb;
        $table = $wpdb->prefix . 'post_likes';
        $per_page = 5;
        $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
        $offset = ($paged - 1) * $per_page;
        $total = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table");
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table ORDER BY post_id, ip_address LIMIT %d, %d", $offset, $per_page), ARRAY_A);
        $pagination = paginate_links([
            'base' => add_query_arg('paged', '%#%'),
            'format' => '',
            'total' => ceil($total / $per_page),
            'current' => $paged,
            'prev_text' => __('«', 'text-domain'),
            'next_text' => __('»', 'text-domain'),
        ]);
?>
        <div class="wrap">
            <h1><?php esc_html_e('Голосования', 'text-domain'); ?></h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Пост ID', 'text-domain'); ?></th>
                        <th><?php esc_html_e('IP-адрес', 'text-domain'); ?></th>
                        <th><?php esc_html_e('Голос', 'text-domain'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($results)) : ?>
                        <?php foreach ($results as $row) : ?>
                            <tr>
                                <td><?php echo esc_html($row['post_id']); ?></td>
                                <td><?php echo esc_html($row['ip_address']); ?></td>
                                <td><?php echo (1 === (int)$row['vote']) ? esc_html__('Лайк', 'text-domain') : ((-1 === (int)$row['vote']) ? esc_html__('Дизлайк', 'text-domain') : esc_html__('Отменено', 'text-domain')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3"><?php esc_html_e('Нет голосов', 'text-domain'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if ($pagination) : ?>
                <div class="tablenav">
                    <div class="tablenav-pages"><?php echo wp_kses_post($pagination); ?></div>
                </div>
            <?php endif; ?>
        </div>
<?php
    }
}
if (is_admin()) {
    new WP_Like_Dislike_Rating_Admin();
}
?>