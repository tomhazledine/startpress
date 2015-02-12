<?php include"header.php"; ?>

<section id="homeContent" class="homeContent">
    <?php if (have_posts()) :
        while (have_posts()) :
            the_post();

            the_content();
        
        endwhile;
    endif; ?>
</section>

<?php include"footer.php"; ?>