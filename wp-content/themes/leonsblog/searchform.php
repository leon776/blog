<form action="/" method="get" class="search-form">
	<i class="icon-search"></i>
    <input type="search" name="s" value="<?php the_search_query(); ?>" 
    onfocus="this.parentNode.className='search-form search-form-on'" 
    onblur="this.parentNode.className='search-form'" />
</form>