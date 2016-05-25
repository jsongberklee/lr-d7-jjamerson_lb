<?php
// grab the user's ID
if ( isset($node->field_user_profile) && !empty($node->field_user_profile)) {
  $user_profile_field = array_shift($node->field_user_profile);
  $user_profile_item = array_shift($user_profile_field);
  if ( isset($user_profile_item['target_id']) ) {
    $uid = $user_profile_item['target_id'];
    $user_profile = user_load($uid);
    // if there's a phone field, grab it:
    if ( is_object($user_profile) && isset($user_profile->field_user_phone) ) {
      $phone_field = array_shift($user_profile->field_user_phone);
      $phone_item = array_shift($phone_field);
      $phone = $phone_item['safe_value'];  
      if ($phone) {
        // create a pseudo-field to be rendered below:
        $content['field_phone'] = array(
          '#theme' => 'field',
          '#weight' => 5,
          '#title' => 'Phone',
          '#access' => TRUE,
          '#label_display' => 'hidden',
          '#view_mode' => 'full',
          '#language' => 'und',
          '#field_name' => 'field_phone',
          '#field_type' => 'text',
          '#field_translatable' =>  '0',
          '#entity_type' => 'node',
          '#items' => array('0' => $phone),
          '#bundle' =>  'personas',
          '0' => array('#markup' => $phone),
        );  
      }
    }
    $email = $user_profile->mail;
    if ($email) {
      $email_link = "<a href='mailto:{$email}'>{$email}</a>";
      // create an email pseudo-field, to be rendered below:
      $content['field_email'] = array(
        '#theme' => 'field',
        '#weight' => 4,
        '#title' => 'Email',
        '#access' => TRUE,
        '#label_display' => 'hidden',
        '#view_mode' => 'full',
        '#language' => 'und',
        '#field_name' => 'field_email',
        '#field_type' => 'email',
        '#field_translatable' =>  '0',
        '#entity_type' => 'node',
        '#items' => array('0' => $email),
        '#bundle' =>  'personas',
        '0' => array('#markup' => $email_link),
      );  
    }
  }
}
?><div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php print $user_picture; ?>
  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>
  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      print render($content);
    ?>
  </div>
  <?php print render($content['links']); ?>
  <?php print render($content['comments']); ?>
</div>