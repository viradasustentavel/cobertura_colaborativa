<?php

/*
Template Name: Cobertura Colaborativa
*/

$args = array(
    'post_type'=>'blogcolab',
    'orderby' => 'date',
    'order' => 'DESC',
    'posts_per_page' => 5
);
$postBlog = new WP_Query($args);

the_post();
get_header(); ?>

<section class="center">
  <div class="containerAgregador">
    <div class="postit">Cobertura Colaborativa</div>
    <div class="subtitle">Use a hashtag <span>#suahashtag</span> e participe!</div>
    <?php echo get_social_posts(); ?>
  </div><!-- #container -->

  <div class="containerRight bgBlack">
    <div class="postit">Pegada Sustentável</div>

    <div class="embedVideo">
      <?php echo  htmlspecialchars_decode(stripslashes(get_option('_cob_video'))); ?>
    </div>

    <div class="parc">
      <img src="<?php bloginfo('template_url') ?>/images/agregador/parcSustentavel.jpg" alt="Tony" />
      <span class="white">PARCERIA:</span>
    </div>
  </div>

  <div class="containerRight bgGreen">
    <div class="postit">Rádio Virada</div>
    <div class="embedVideo">
      <?php echo  htmlspecialchars_decode(stripslashes(get_option('_cob_radio'))); ?>
    </div>

    <div class="parc">
      <img src="<?php bloginfo('template_url') ?>/images/agregador/parcRadio.jpg" alt="Tony" />
      <span>PARCERIA:</span>
    </div>
  </div>

  <div class="containerRight bgPink">
    <div class="postit">Blog</div>

    <div class="boxBlog">
      <?php if ($postBlog->have_posts()): while ($postBlog->have_posts()): $postBlog->the_post();  ?>
        <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
        <hr>
      <?php endwhile; endif; ?>

      <button class="bgPink border" onclick="window.location = '<?php echo get_post_type_archive_link('blogcolab'); ?>'" src="">VEJA TODOS</button>
    </div>
  </div>

  <div class="loaderImg"><img src="<?php bloginfo('template_url') ?>/images/load.gif" /></div>
  <button class="bgPink border left btnLoading">CARREGAR</button>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
