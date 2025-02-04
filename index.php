<?php
get_header(); ?>

<main class="main">
    <div class="main__wrapper container">
        <section class="posts__wrapper">
            <div class="posts__title">
                Статьи
            </div>
            <div class="posts__container">
                <div class="posts__list">
                    <?php
                    $args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 10,
                    );
                    $query = new WP_Query($args);
                    if ($query->have_posts()) :
                        while ($query->have_posts()) : $query->the_post(); ?>
                            <div class="post__item">
                                <div class="post__image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php else : ?>
                                        <img src="https://placehold.co/600x400" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="post__content">
                                    <div class="post__item-title">
                                        <?php the_title(); ?>
                                    </div>
                                    <div class="post__item-description">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <div class="post__item-info">
                                        <div class="post__item-author">Автор: <span><?php the_author(); ?></span></div>
                                        <div class="post__item-rating" data-post-id="<?php the_ID(); ?>">
                                            <button class="btn" data-vote="1"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_7217_9)">
                                                        <path d="M11 22C17.0751 22 22 17.0751 22 11C22 4.92487 17.0751 0 11 0C4.92487 0 0 4.92487 0 11C0 17.0751 4.92487 22 11 22Z" fill="#43B05C" />
                                                        <path d="M11 5.72V16.72" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M16.5 11H5.5" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_7217_9">
                                                            <rect width="22" height="22" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg></button>
                                            <span class="rating_value">0</span>
                                            <button class="btn" data-vote="-1"><svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_7217_14)">
                                                        <path d="M11 22C17.0751 22 22 17.0751 22 11C22 4.92487 17.0751 0 11 0C4.92487 0 0 4.92487 0 11C0 17.0751 4.92487 22 11 22Z" fill="#ED8A19" />
                                                        <path d="M16.72 11H5.28" stroke="white" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_7217_14">
                                                            <rect width="22" height="22" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                        custom_pagination();
                        wp_reset_postdata();
                    else : ?>
                        <p><?php esc_html_e('No posts found.'); ?></p>
                    <?php endif; ?>
                </div>


            </div>

        </section>
        <aside>
            Sidebar
        </aside>
    </div>
</main>

<?php
get_footer();
