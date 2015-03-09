<?php

function colab_up_del_new_field($field)
{
  update_option($field, $_REQUEST[$field]);

  if (isset($_REQUEST[$field])) {
    update_option($field, htmlspecialchars($_REQUEST[$field]));
  } else {
    delete_option($field);
  }
}


// Add admin page
function colab_main_admin_add() {
  add_menu_page(
    "Aggregator",
    "Aggregator",
    'manage_options',
    basename(__FILE__),
    'acba_admin_page',
    '',
    4
  );
}

// Print page
function acba_admin_page() {
  // Form control
  $form_sent = false;

  if (isset($_REQUEST['save_options']) && $_REQUEST['save_options'] == 'Y') {
    colab_up_del_new_field('_cob_video');
    colab_up_del_new_field('_cob_radio');
    $form_sent = true;
  }

  $_cob_video = get_option('_cob_video');
  $_cob_radio = get_option('_cob_radio');
?>

<form class="admin-wrap admin-main" name="adm-main-form" method="post" action="">
  <input type="hidden" name="save_options" value="Y" >

  <h1>Cobertua Colaborativa</h1>

  <?php if ($form_sent): ?>
    <p class="form-feedback">Alterações salvas com sucesso</p>
  <?php endif; ?>

  <hr>

  <h2>Embed</h2>
  <p>
    <label><strong>Campo de embed de vídeo</strong></label><br>
    <textarea name="_cob_video" rows="8" cols="40"><?php echo $_cob_video; ?></textarea>
    <p><small>Cole o HTTML</small></p>
  </p>

  <h2>Tag de busca</h2>
  <p>
    <label><strong>Campo de embed da rádio</strong></label><br>
    <textarea name="_cob_radio" rows="8" cols="40"><?php echo $_cob_radio; ?></textarea>
    <p><small>Cole o HTML</small></p>
  </p>

  <p class="submit"><button type="submit"><?php _e('Salvar', '42i'); ?></button></p>
</form> <!-- .admin-wrap -->
<?php
}

add_action('admin_menu' , 'colab_main_admin_add');
?>
