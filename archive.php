<?php get_header(); ?>

<!-- Category Hero -->
<section class="category-hero">
    <div class="container">
        <h1 class="category-title">
            <?php
            if (is_category()) {
                single_cat_title();
            } elseif (is_tag()) {
                single_tag_title();
            } elseif (is_archive()) {
                the_archive_title();
            }
            ?>
        </h1>
        
        <?php if (category_description()) : ?>
        <div class="category-intro">
            <?php echo category_description(); ?>
        </div>
        <?php endif; ?>
        
        <?php
        // Get pillar post link (custom field in category)
        $category = get_queried_object();
        $pillar_post_id = get_term_meta($category->term_id, 'pillar_post', true);
        
        if ($pillar_post_id) :
        ?>
        <div class="pillar-link">
            <a href="<?php echo get_permalink($pillar_post_id); ?>" class="internal-pillar-link">
                → <?php _e('Read our complete guide:', 'minimal-nails'); ?> <?php echo get_the_title($pillar_post_id); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Sub-Category Links -->
<?php
$current_category = get_queried_object();
if (is_category() && $current_category->parent == 0) :
    $child_categories = get_categories(array(
        'parent' => $current_category->term_id,
        'hide_empty' => true,
    ));
    
    if (!empty($child_categories)) :
?>
<section class="subcategory-links">
    <div class="container">
        <div class="subcat-label"><?php _e('Browse by:', 'minimal-nails'); ?></div>
        <nav class="subcat-nav">
            <?php
            foreach ($child_categories as $index => $child) :
                if ($index > 0) echo ' · ';
            ?>
            <a href="<?php echo esc_url(get_category_link($child->term_id)); ?>">
                <?php echo esc_html($child->name); ?>
            </a>
            <?php endforeach; ?>
        </nav>
    </div>
</section>
<?php
    endif;
endif;
?>

<!-- Article List -->
<section class="archive-articles">
    <div class="container">
        <div class="article-list">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
            ?>
            <article class="article-item" id="post-<?php the_ID(); ?>">
                <?php if (has_post_thumbnail()) : ?>
                <div class="article-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="article-content">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) :
                    ?>
                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="article-category">
                        <?php echo esc_html($categories[0]->name); ?>
                    </a>
                    <?php endif; ?>
                    
                    <h2 class="article-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    
                    <p class="article-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                    
                    <div class="article-meta">
                        <time datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo get_the_date(); ?>
                        </time>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Read more →', 'minimal-nails'); ?></a>
                </div>
            </article>
            <?php
                endwhile;
            else :
                echo '<p>' . __('No posts found in this category.', 'minimal-nails') . '</p>';
            endif;
            ?>
        </div>
    </div>
</section>

<!-- FAQ Block -->
<?php
// Get FAQ from category meta or default FAQs
$category = get_queried_object();
$faqs = get_term_meta($category->term_id, 'category_faqs', true);

// Default FAQs if none set
if (empty($faqs)) {
    $faqs = array(
        array(
            'question' => 'What are minimal nail ideas?',
            'answer' => 'Minimal nail ideas are simple, clean nail designs that focus on understated elegance. They typically feature neutral colors, subtle patterns, or simple geometric shapes that work well for everyday wear.'
        ),
        array(
            'question' => 'How long do minimal nail designs last?',
            'answer' => 'With proper application and care, minimal nail designs can last 7-14 days for regular polish and 2-3 weeks for gel polish or press-on nails.'
        ),
        array(
            'question' => 'Are minimal nails appropriate for the office?',
            'answer' => 'Yes, minimal nail designs are perfect for professional settings. Their understated nature makes them suitable for any workplace dress code.'
        ),
        array(
            'question' => 'What colors work best for minimal nails?',
            'answer' => 'Neutral colors like nude, beige, soft pink, cream, and light grey are ideal for minimal nail designs. These shades complement any outfit and occasion.'
        ),
        array(
            'question' => 'Can I do minimal nail designs at home?',
            'answer' => 'Absolutely! Minimal nail designs are beginner-friendly and don\'t require advanced nail art skills. Most designs can be achieved with basic polish and simple tools.'
        ),
    );
}

if (!empty($faqs)) :
?>
<section class="faq-section">
    <div class="container">
        <h2 class="section-title"><?php _e('Frequently Asked Questions', 'minimal-nails'); ?></h2>
        
        <div class="faq-list" itemscope itemtype="https://schema.org/FAQPage">
            <?php foreach ($faqs as $faq) : ?>
            <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <h3 class="faq-question" itemprop="name"><?php echo esc_html($faq['question']); ?></h3>
                <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <div itemprop="text">
                        <p><?php echo esc_html($faq['answer']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Pagination -->
<div class="container">
    <div class="archive-pagination">
        <?php
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => __('← Older posts', 'minimal-nails'),
            'next_text' => __('Newer posts →', 'minimal-nails'),
        ));
        ?>
    </div>
</div>

<?php get_footer(); ?>