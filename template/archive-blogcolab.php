<?php

$args = array(
  'post_type'=>'blogcolab',
  'orderby' => 'date',
  'order' => 'DESC',
  'posts_per_page' => 1,
  'offset' => 0
);
$query = new WP_Query($args);

get_header(); ?>

<section class="center">
  <div class="postitPost">Blog</div>

  <div class="dadosBlog">
    <?php if ($query->have_posts()): while ($query->have_posts()): $query->the_post();  ?>

    <div class="allPosts bgPink">
      <div class="boxBlog">
        <h3><?php the_title(); ?></h3>
        <button class="bgPink border right margin" onclick="window.location = '<?php the_permalink(); ?>'" src="">CONTINUAR LENDO</button>
      </div>
    </div>
    <?php endwhile; endif; ?>
    <?php wp_reset_query(); ?>
  </div>

  <div class="loaderImg2"><img src="<?php bloginfo('template_url') ?>/images/loader.gif" /></div>

  <button class="bgPink border left btnLoadingPost">CARREGAR</button>
</section>

<?php get_footer(); ?>
