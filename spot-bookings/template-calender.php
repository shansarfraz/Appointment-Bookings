<?php

if(isset($_GET['view']) && $_GET['view']==1):



  $events = array();

  $event = array();

  $args = array(

		'post_type' => array('availability'),

		'post_status' => array('publish'),

		'author'         => get_current_user_id(),

		'order' => 'DESC',

		'orderby' => 'date'

	);

  $ajaxposts =  get_posts( $args ); // changed to get_posts from wp_query, because `get_posts` returns an array

  foreach ( $ajaxposts as  $ajaxpost){

  

  $event['title'] = $ajaxpost->post_title;

  $event['start'] = date('Y-m-d',strtotime(get_post_meta($ajaxpost->ID, 'avail_date', true)));

  //$event['end'] = date('Y-m-d H:i:s',strtotime(get_post_meta($ajaxpost->ID, 'avail_date', true)));

 // $event['allday'] = false;

 //echo get_post_meta($ajaxpost->ID, 'avail_date', true); 

  $events[] = $event;

  }

echo json_encode( $events );

exit;

endif;

if(isset($_GET['id']) && $_GET['id']==1):

  
    // $events[] = $event;

     if(   isset($_REQUEST['title']) && !empty($_REQUEST['title'])  ): 
    
     $postarr = array(
                      'post_type' => 'availability',
                      'post_title' => $_REQUEST['title'],
                      'post_author' => get_current_user_id(),
                      'post_status' => 'publish' 
                    );

      $_post_id = wp_insert_post($postarr);

      update_post_meta($_post_id, 'avail_date', $_REQUEST['date']);

      //echo $_REQUEST['date'];

    endif;

  //  echo 'i am here';

else:

/**
 * Template Name: Calender Tempalte
 *
 */

get_header(); 
?><!-- Titlebar -->

<link href='<?php echo get_stylesheet_directory_uri()?>/fullcalendar/packages/core/main.css' rel='stylesheet' />
<link href='<?php echo get_stylesheet_directory_uri()?>/fullcalendar/packages/daygrid/main.css' rel='stylesheet' />
<link href='<?php echo get_stylesheet_directory_uri()?>/fullcalendar/packages/timegrid/main.css' rel='stylesheet' />
<script src='<?php echo get_stylesheet_directory_uri()?>/fullcalendar/packages/core/main.js'></script>
<script src='<?php echo get_stylesheet_directory_uri()?>/fullcalendar/packages/interaction/main.js'></script>
<script src='<?php echo get_stylesheet_directory_uri()?>/fullcalendar/packages/daygrid/main.js'></script>
<script src='<?php echo get_stylesheet_directory_uri()?>/fullcalendar/packages/timegrid/main.js'></script>
<script src='<?php echo get_stylesheet_directory_uri()?>/fullcalendar/packages/timegrid/main.js'></script>

<!-- <style>

  body {
    margin: 40px 10px;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 900px;
    margin: 0 auto;
  }

</style> -->

<?php 

$current_user = wp_get_current_user();

$selectable = true;

if(in_array('employer',$current_user->roles)){

  $selectable = false;
}


//print_r($current_user);


?>
<div class="container">
  <div id='calendar'></div>
</div>


<script>
   <?php //echo date('Y,m,d')?>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
      header: {
        left: 'prev',
        center: 'title',
        right: 'next'
      },
      timeZone: 'local',
      defaultDate: new Date('<?php echo date('Y,m,d')?>'),
      navLinks: true, // can click day/week names to navigate views
      selectable: <?php echo $selectable ?>,
      selectMirror: true,
      displayEventTime: false,
      displayEventEnd: false,
      droppable: true,
      eventDrop: function(arg) {
           
           //console.log(arg.event.remove());
           //console.log(arg.event.start);
           //calender.
       },
       eventClick: function(arg) {
           console.log(arg.event.start);
       },
      select: function(arg) {
        
        var datee = new Date(arg.end);

       var options = { month: 'long'};
       var temp = new Intl.DateTimeFormat('en-US', options).format(datee);

       var ajax_date = new Intl.DateTimeFormat('en-US').format(datee);

       var ajax_start_date = new Intl.DateTimeFormat('en-US').format(arg.start);

        var datee = new Date(arg.end);
        var title = prompt('Add My Availibility','<?php echo $current_user->user_nicename.'-'?>'+temp+' '+datee.getUTCDate());
      //  if (title === '') {
               
            //alert('Please add your availaility');
            //var title = prompt('Add My Availibility');
       // }
         // calendar.addEvent({
            //title: title,
           // start: arg.start,
          //  end: arg.end,
          //  allDay: arg.allDay
         // })
       // }
       if(title !== null){

        jQuery.ajax({

          url: '<?php echo get_permalink();?>?id=1',

          data: 'date='+ajax_start_date+'&title='+title,

          //data: 'title='+title,

          type: "GET",

          success: function(data) {

            console.log(data);
            


             //obj = JSON.parse(data);

            // alert(obj.end);

             calendar.addEvent({
                title: title,
                start: arg.start
            })

           // calendar.

        }

          });
        }
       else{   
        calendar.unselect();
       }
      },
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events:'<?php echo get_permalink();?>?view=1'
    });
    
    //var event = calendar.getEventById('a')
    calendar.render();
  });

</script>
<?php
get_footer(); 
endif;
?>
