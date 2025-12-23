<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-input-wrapper">
        <input 
            type="search" 
            class="search-field" 
            placeholder="<?php echo esc_attr_x('Search for nail ideas...', 'placeholder', 'minimal-nails'); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
            required
        />
        <button type="submit" class="search-submit">
            <span class="search-icon">🔍</span>
            <span class="search-text"><?php echo esc_attr_x('Search', 'submit button', 'minimal-nails'); ?></span>
        </button>
    </div>
</form>
