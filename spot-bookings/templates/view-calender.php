<?php

if(isset($_GET['id']) && $_GET['id']==1):

     if(   isset($_REQUEST['title']) && !empty($_REQUEST['title'])  ): 
    
     $postarr = array(
                      'post_type' => 'availability',
                      'post_title' => $_REQUEST['title'],
                      'post_author' => get_current_user_id(),
                      'post_status' => 'publish' 
                    );

      $_post_id = wp_insert_post($postarr);

      update_post_meta($_post_id, 'avail_date', $_REQUEST['date']);


    endif;

else:

get_header(); 
?><!-- Titlebar -->
<div class="container">
  <div id='calendar'></div>
</div>
<?php
get_footer(); 
endif;
?>
