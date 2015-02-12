<?php include"head.php"; ?>

<header class="globalHeader">
    
    <div class="siteTitle">
        <a href="<?=get_site_url();?>/" title="<?php bloginfo('name'); ?>" rel="home">
            <div class="siteLogo"></div>
            <h3><?php bloginfo('name'); ?></h3>
            <p class="tagline">Luxury properties on, by or near a beach</p>
        </a>
    </div>

    <nav class="mainNav clearfix">
        <?php wp_nav_menu(array(
            'menu_class'      => 'menu clearfix',
            'theme_location'  => 'header_menu'
        )); ?>
    </nav>

</header>
