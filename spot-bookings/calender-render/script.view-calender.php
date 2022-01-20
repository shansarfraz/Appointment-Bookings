<script>
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
      selectable: false,
      selectMirror: true,
      displayEventTime: false,
      displayEventEnd: false,
      droppable: false,
      draggable: false,
      disableDragging: true,
      eventClick: function(arg) {
           console.log(arg.event.start);
       },
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      events:'<?php echo json_encode(get_events(get_current_user_id())) ?>'
    });
    
    //var event = calendar.getEventById('a')
    calendar.render();
  });

</script>