<?php get_header(); ?>

<article class="page-wrapper">
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- Page Header -->
    <header class="page-header">
        <div class="container-narrow">
            <h1 class="page-title"><?php the_title(); ?></h1>
            
            <?php if (has_excerpt()) : ?>
            <div class="page-excerpt">
                <?php the_excerpt(); ?>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Page Content -->
    <div class="page-content">
        <div class="container-narrow">
            <?php
            $content = get_the_content();
            
            // Insert TOC before first H2 if headings exist
            if (minimal_nails_has_headings()) {
                $content = minimal_nails_insert_toc_before_first_h2($content);
            }
            
            echo apply_filters('the_content', $content);
            ?>
            
            <?php
            // Display page links for paginated content
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'minimal-nails'),
                'after'  => '</div>',
            ));
            ?>
        </div>
    </div>

    <?php endwhile; ?>
</article>

<!-- Back to Top Button -->
<button id="back-to-top" class="back-to-top" aria-label="Back to top">
    <span class="back-to-top-icon">↑</span>
</button>

<?php get_footer(); ?>