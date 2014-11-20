<form action="<?php echo home_url(); ?>" class="search-form clearfix" method="get">
    <input type="text" onBlur="if (this.value == '')
        this.value = this.defaultValue;" onFocus="if (this.value == this.defaultValue)
        this.value = '';" value="<?php _e( 'Enter keyworks', kopa_get_domain() ); ?>" name="s" class="search-text">
    <input type="submit" value="<?php _e( 'Go', kopa_get_domain() ); ?>" class="search-submit">
</form>