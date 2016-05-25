<?php 
/**
 * $result->display_name
 *        ->title
 *        ->department
 *        ->email
 *        ->phone
 *        ->building
 *        ->room
 *        ->mail_stop
 *        ->department_nid
 *        ->persona_url
 *        ->is_faculty
 *        ->is_staff
 *        ->department_url
 *        ->department_nid
 *        ->entity_id
 *        ->type
 */
if ($result->room) {
  $room = ', ';
  $room_letters_stripped = preg_replace('/[A-Za-z]+/', '', $result->room);
  $letter_count = strlen($result->room) - strlen($room_letters_stripped);
  if ($letter_count < 2 ) {
    $room .= 'room '; 
  } 
  $room .= $result->room;
} else {
  $room = '';
}

$base_directory_url = '/directory/%2A';
$department_search_url = $base_directory_url . '?department=' . $result->department_nid;
?>

<div class="general-info">
  <?php if($result->title): ?>
    <div class="title"><span class="value"><?php echo $result->title; ?></span></div>
  <?php endif; ?>
  <?php if($result->department): ?>
  <div class="department">
  	<span class="value">
      <a href="<?php echo $department_search_url; ?>"><?php echo $result->department; ?></a>
      <span class="additional-links">
        | <a href="<?php echo $result->department_url; ?>">Visit Dept Page</a>
      </span>
    </span>
  </div>
<?php endif; ?>
</div>
<div class="contact-info">
  <div class="through-wires-and-cables">
    <?php if($result->phone): ?>
      <div class="phone"><span class="value"><?php echo $result->phone; ?></span></div>
    <?php endif; ?>
    <?php if($result->email): ?>
      <div class="the-internet-is-a-series-of-tubes"><span class="value"><a href="mailto:<?php echo $result->email; ?>" ><?php echo $result->email; ?></a></span></div>
    <?php endif; ?>
  </div>
  <div class="physical-location">
    <?php if($result->building): ?>
      <div class="building"><span class="value"><?php echo $result->building; ?><?php echo $room; ?></span></div> 
    <?php endif; ?>
    <?php if($result->mail_stop): ?>
      <div class="mail-stop"><span class="label">Mail stop:</span><span class="value"><?php echo $result->mail_stop; ?></span></div>
    <?php endif; ?>
  </div>
</div>
<div class="actions">
	<a href="/report-incorrect-name-title-or-department?uid=<?php echo $result->entity_id; ?>" class="report-discrepancy" data-discrepancy-id="<?php echo $result->entity_id; ?>">Report Discrepancy</a>
</div>