<?php

the_post();
get_header();
?>
<section class="center">

    <div class="postitPost">Blog</div>
    <button class="bgPink border left " onclick="window.location = '<?php echo get_post_type_archive_link('blogcolab'); ?>'" src="">VOLTAR</button>
<br><br>
        <div class="allPosts bgPink width">
            <div class="boxBlog unique">
              <p><h3><?php the_title(); ?></h3></p>
              <?php the_post_thumbnail('suplentes-thumbnail'); ?>
              <?php the_content(); ?>
              <p><a class="button bgPink back-top" onclick="document.getElementById('gototop_button').click()">TOPO</a></p>
            </div>
        </div>
        <button class="bgPink border left" onclick="window.location = '<?php echo get_post_type_archive_link('blogcolab'); ?>'" src="">VOLTAR</button>
</section>
<?php get_footer(); ?>
