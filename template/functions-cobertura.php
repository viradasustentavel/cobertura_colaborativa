<?php

function agregador_script_style() {
  wp_enqueue_script( 'agregador-script', get_template_directory_uri() . '/js/agregator.js', array( 'jquery', 'jquery-masonry' ), '2013-07-18', true );
  wp_enqueue_script( 'fancybox-script', get_template_directory_uri() . '/js/fancybox/jquery.fancybox.js', array( 'jquery'), '2013-07-18', true );
  wp_enqueue_style( 'fancybox-style', get_template_directory_uri() . '/js/fancybox/jquery.fancybox.css');
  wp_enqueue_style( 'agregador-style', get_template_directory_uri() . '/css/aggregator.css');
}
add_action( 'wp_enqueue_scripts', 'agregador_script_style' );

function qdi_cp_trigger_blgcolab()
{
  // array com features nativos do wp
  $support = array('title', 'thumbnail', 'excerpt', 'editor');

  register_post_type('blogcolab' ,
    array(
      'label'               => __('B. Colab'),
      'public'              => TRUE,
      'show_ui'             => TRUE,
      'query_var'           => TRUE,
      'supports'            => $support,
      'menu_position'       => 5,
      'capability_type'     => 'post',
      'hierarchical'        => TRUE,
      'exclude_from_search' => FALSE,
      'publicly_queryable'  => TRUE,
      'has_archive' => TRUE,
      'taxonomies' => array('post_tag', 'category'),
      'rewrite'             => array( 'slug' => 'blog-colaborativo' ) )
    );
}
add_action('init', 'qdi_cp_trigger_blgcolab');

function get_social_posts($limit = 10, $offset = 0) {
  global $wpdb;
  $output = '';
  $cobcolaborativa_model = new CobColaborativaDataModel();
  $query = "SELECT * from ". $cobcolaborativa_model->table_name ." ORDER BY 'created_at' desc LIMIT " . $limit . " OFFSET " . $offset;
  $result = $wpdb->get_results($query);
  //var_dump($result);
  //exit;
  $countItem = 1;
  foreach($result as $r) {
    switch($r->type) {
    case 'youtube':
      $data = '<iframe src="'.$r->data.'" frameborder="0" allowfullscreen class="video"></iframe>';
      $fancy = '<a class="fancybox linkFancy" title="'.$r->name.'" rel="group" data-fancybox-type="iframe" href="'.$r->data.'"></a>'.$data;
      $style = 'videos';
      break;
    case 'twitter':
      $data = '<p>'.$r->data.'</p>';
      $data .= '<p style="display:none" id="contTwitter'.$contTwitter.'">'.$r->data.'</p>';
      $fancy = '<a class="fancybox" rel="group" title="'.$r->name.'" href="#contTwitter'.$contTwitter.'">'.$data.'</a>';
      $style = 'photosRight';
      break;
    default:
      $data = '<img src="'.$r->data.'" alt="Wonder" />';
      $fancy = '<a class="fancybox" rel="group" title="'.$r->name.'" href="'.$r->data.'">'.$data.'</a>';
      $style = 'photosRight';
      break;
    }
    $output .= '<div rel="'.$countItem.'" class="box photo '.$style.' twitter">';
    $output .= $fancy;
    $output .= ($r->name != '') ? '<h3>Autor: <span>'.$r->name.'</span></h3>' : '';
    $output .= '<span><a href="'.$r->link.'" target="blank">Link original</a></span>';
    $output .= '</div>';
    $countItem++;
  }


  return $output;
}


function qdi_ajax_get_more_social_posts(){
  global $wpdb;

  $limit = (isset($_POST) && array_key_exists('limit', $_POST)) ? (int) $_POST['limit'] : 9;
  $offset = (isset($_POST) && array_key_exists('offset', $_POST)) ? (int) $_POST['offset'] : 0;

  $output = '';
  $cobcolaborativa_model = new CobColaborativaDataModel();
  $query = "SELECT * from ". $cobcolaborativa_model->table_name ." ORDER BY 'created_at' desc LIMIT " . $limit . " OFFSET " . $offset;
  $result = $wpdb->get_results($wpdb->prepare (
    "
                            SELECT * from $cobcolaborativa_model->table_name ORDER BY 'created_at' desc LIMIT %d OFFSET %d
                        ", array(
                        $limit,
                        $offset
                        )
                ));
    $contTwitter = 0;
    $countItem = $offset;
    foreach($result as $r) {
        switch($r->type) {
            case 'youtube':
                        $data = '<iframe src="'.$r->data.'" frameborder="0" allowfullscreen class="video"></iframe>';
                        $fancy = '<a class="fancybox linkFancy" title="'.$r->name.'" rel="group" data-fancybox-type="iframe" href="'.$r->data.'"></a>'.$data;
                        $style = 'videos';
                        break;
                    case 'twitter':
                        $data = '<p>'.$r->data.'</p>';
                        $data .= '<p style="display:none" id="contTwitter'.$contTwitter.'">'.$r->data.'</p>';
                        $fancy = '<a class="fancybox" rel="group" title="'.$r->name.'" href="#contTwitter'.$contTwitter.'">'.$data.'</a>';
                        $style = 'photosRight';
                        break;
                    default:
                        $data = '<img src="'.$r->data.'" alt="Wonder" />';
                        $fancy = '<a class="fancybox" rel="group" title="'.$r->name.'" href="'.$r->data.'">'.$data.'</a>';
                        $style = 'photosRight';
                        break;
        }

        $response .= '<div rel="'.$countItem.'" class="box photo '.$style.' twitter">';
        $response .= $fancy;
        $response .= ($r->name != '') ? '<h3>Autor: <span>'.$r->name.'</span></h3>' : '';
        $response .= '<span><a href="'.$r->link.'" target="blank">Link original</a></span>';
        $response .= '</div>';

        $countItem++;
        $contTwitter++;
    }


    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');
    echo json_encode(array('itens' => $response, 'limit' => $limit));

    die();
}
add_action('wp_ajax_nopriv_qdi_ajax_get_more_social_posts', 'qdi_ajax_get_more_social_posts');
add_action('wp_ajax_qdi_ajax_get_more_social_posts', 'qdi_ajax_get_more_social_posts');

function qdi_ajax_get_more_blog() {
  $response = '';
  $offset = (isset($_POST) && array_key_exists('offset', $_POST)) ? (int) $_POST['offset'] : 1;
  $args = array(
      'post_type'=>'blogcolab',
      'orderby' => 'date',
      'order' => 'DESC',
      'posts_per_page' => 1,
      'offset' => $offset
  );
  $query = new WP_Query($args);

  if ($query->have_posts()){
      while ($query->have_posts()){

          $query->the_post();
          $response .= '<div class="allPosts bgPink">';
          $response .= '<div class="boxBlog">';
          $response .= '<h3>';
          $response .= get_the_title();
          $response .= '</h3>';
          $response .= '<button class="bgPink border right margin" onclick="window.location = '."'".get_the_permalink()."'".'">CONTINUAR LENDO</button>';
          $response .= '</div>';
          $response .= '</div>';

      }
  }
  wp_reset_query();



  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json');
  echo json_encode(array('itens' =>$response, 'offset' => 1));

  die();
}
add_action('wp_ajax_nopriv_qdi_ajax_get_more_blog', 'qdi_ajax_get_more_blog');
add_action('wp_ajax_qdi_ajax_get_more_blog', 'qdi_ajax_get_more_blog');
