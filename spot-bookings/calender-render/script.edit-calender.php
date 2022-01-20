<?php $current_user_id = get_current_user_id()?>
<script>
  jQuery('.spinner-border').hide();
  jQuery('#saveModal').modal('hide');
  jQuery('#updateModal').modal('hide');
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
      selectable: true,
      selectMirror: false,
      displayEventTime: false,
      displayEventEnd: false,
      droppable: true,
     // selectConstraint:{
     // start: '00:01', 
     // end: '23:59', 
    //},
      eventRender: function (info) {

          jQuery.ajax({

          url: '<?php echo admin_url('admin-ajax.php');?>',

          data: 'action=show_tooltip&event_id='+info.event.id,

          type: "POST",

          success: function(data) {

            var tooltip = data;
            var element = info.el;
            jQuery(element).attr("data-original-title", tooltip);
            jQuery(element).tooltip({container: "body",html:true})
          }

          });           
           
      },
      eventDrop: function(arg) {
        jQuery('#updateModal').modal('show');
        jQuery('#update_title').val(arg.event.title);

        var ajax_start_date = new Intl.DateTimeFormat('en-US').format(arg.event.start);

        jQuery("#delete").click(function(e){
          jQuery('#updateModal').modal('hide');
          e.preventDefault();
          jQuery.ajax({

                  url: '<?php echo admin_url('admin-ajax.php');?>',

                  data: 'action=delete_event&event_id='+arg.event.id,

                  type: "POST",

                  success: function(data) {
                    
                    var event_data = calendar.getEventById( data );
                    
                    var updated_title = jQuery('#update_title').val();

                    event_data.remove();

                  },
                  beforeSend: function(){
                  // Code to display spinner
                  jQuery('.spinner-border').show();
                  },
                  complete: function(){
                  // Code to hide spinner.
                  jQuery('.spinner-border').hide();
                  }

                  });
       
       
        });  

        jQuery("#update").click(function(e){
          jQuery('#updateModal').modal('hide');
          e.preventDefault();
          
          var event_title = jQuery('#update_title').val();
          
          jQuery.ajax({

          url: '<?php echo admin_url('admin-ajax.php');?>',

          data: 'action=update_event&title='+event_title+'&date='+ajax_start_date+'&event_id='+arg.event.id+'&user='+<?php echo $current_user_id ?>,

          type: "POST",

          success: function(data) {
            
            var event_data = calendar.getEventById( data );
            
            var updated_title = jQuery('#update_title').val();

            event_data.setProp( 'title', updated_title );
            event_data.setProp( 'start', ajax_start_date );

          },
          beforeSend: function(){
          // Code to display spinner
          jQuery('.spinner-border').show();
          },
          complete: function(){
          // Code to hide spinner.
          jQuery('.spinner-border').hide();
          }

          });
        });  
       },
       eventClick: function(arg) {

        jQuery('#updateModal').modal('show');
        jQuery('#update_title').val(arg.event.title);

        var ajax_start_date = new Intl.DateTimeFormat('en-US').format(arg.event.start);

        jQuery('#event_date').val(ajax_start_date);
        jQuery('#event_id').val(arg.event.id);

        jQuery("#delete").click(function(e){
          jQuery('#updateModal').modal('hide');
          e.preventDefault();
          jQuery.ajax({

                  url: '<?php echo admin_url('admin-ajax.php');?>',

                  data: 'action=delete_event&event_id='+arg.event.id,

                  type: "POST",

                  success: function(data) {
                    
                    var event_data = calendar.getEventById( data );
                    
                    var updated_title = jQuery('#update_title').val();

                    event_data.remove();

                  },
                  beforeSend: function(){
                  // Code to display spinner
                  jQuery('.spinner-border').show();
                  },
                  complete: function(){
                  // Code to hide spinner.
                  jQuery('.spinner-border').hide();
                  }

                  });
       
       
        });  

        jQuery("#update").click(function(e){
          jQuery('#updateModal').modal('hide');
          e.preventDefault();
          
          var event_title = jQuery('#update_title').val();
          var event_date = jQuery('#event_date').val();
          var event_id = jQuery('#event_id').val();
          
          jQuery.ajax({

          url: '<?php echo admin_url('admin-ajax.php');?>',

          data: 'action=update_event&title='+event_title+'&date='+ajax_start_date+'&event_id='+arg.event.id+'&user='+<?php echo $current_user_id ?>,

          type: "POST",

          success: function(data) {
            
            var event_data = calendar.getEventById( data );
            
            var updated_title = jQuery('#update_title').val();

            var updated_date = jQuery('#event_date').val();

            event_data.setProp( 'title', updated_title );
            event_data.setProp( 'start', updated_date );

          },
          beforeSend: function(){
          // Code to display spinner
          jQuery('.spinner-border').show();
          },
          complete: function(){
          // Code to hide spinner.
          jQuery('.spinner-border').hide();
          }

          });
        });  

       },

      select: function(arg) {
        jQuery(document).ready(function(){
          jQuery('#saveModal').modal('show');
       });

       var ajax_start_date = new Intl.DateTimeFormat('en-US').format(arg.start);


      // jQuery('#event_date').val();


      // if (arg !== "undefined")
       var clickHandler = function(e){

         

          jQuery('#save').attr('disabled', 'disabled');
          
          var title = jQuery('#save_title').val();

          jQuery(document).ready(function(){
            jQuery('#saveModal').modal('hide');
          });

        //if(title !== ''){
         
          jQuery.ajax({

          
            url: '<?php echo admin_url('admin-ajax.php');?>',

            data: 'action=save_event&title='+title+'&date='+ajax_start_date+'&user='+<?php echo get_current_user_id()?>,

            type: "POST",

            async: true,
           // dataType: 'json',
            //enctype: 'multipart/form-data',
            cache: false,

            success: function(data) {
              
              jQuery('#save').removeAttr('disabled');
              jQuery('#save').one('click', clickHandler);

              var my_data = JSON.parse(data)
              console.log(data);

              calendar.addEvent({
              title:  my_data.title,
              start:  my_data.date,
              end:   my_data.date,
              color: '#635b8f'
            });

            //
            
            },

            beforeSend: function(){
            // Code to display spinner
            jQuery('.spinner-border').show();
            },

            complete: function(){
            // Code to hide spinner.
            jQuery('.spinner-border').hide();

            }

            });
      
          e.stopImmediatePropagation();
          return false;

          }
          jQuery('#save').one('click', clickHandler);
           
         // else{
       // calendar.unselect();
       // alert('Title can\'t be empty');
       //}   
         // calendar.unselect();
        //}
        //}
      // else{   
        //calendar.unselect();
       //}
      },
      editable: true,
      eventLimit: true,
      events: <?php echo json_encode(get_events(get_current_user_id())) ?>
    });
  
    calendar.render();
  });

</script>
