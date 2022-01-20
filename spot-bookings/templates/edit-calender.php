<?php get_header();

$user = wp_get_current_user();

$needles = array('candidate','administrator');

if(count(array_intersect($needles,$user->roles)) > 0):

?>

<div class="container">
  <div id='calendar'></div>
</div>

<?php 
  require_once( CD_PLUGIN_PATH . 'modals/modal.save-event.php' );
  require_once( CD_PLUGIN_PATH . 'modals/modal.update-event.php' );
?>
<div class="spinner-border text-muted"></div>

<?php 
  require_once( CD_PLUGIN_PATH . 'calender-render/script.edit-calender.php' );
?>

<?php
else:
  echo '<div class="container"><h3><a href="'.wp_login_url().'">Click Here</a> to login as Candidate to view this page</h3></div>';
endif;
get_footer(); 
//endif;
?>
