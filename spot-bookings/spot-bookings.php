<?php
/*
Plugin Name: On Spot Bookings
Plugin URI: 
Description: Allows employers to book candidate
Version: 0.1
Author: Shaun Sylver
Author URI: 
*/

define( 'CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'CD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


add_action( 'init', 'create_availability_type' );
function create_availability_type() {

    register_post_type( 'availability',
        array(
            'labels' => array(
                'name' => __( 'Candidate Availabilities' ),
                'singular_name' => __( 'availability' ),
                'add_new' => __( 'Add New availability' ),
                'add_new_item' => __( 'Add New availability' ),
                'edit' => __( 'Edit availability' ),             
                'edit_item' => __( 'Edit availability' ),                
                'new_item' => __( 'Add New availability' ),              
                'view' => __( 'View availability' ),         
                'view_item' => __( 'View availability' ),                    
                'search_items' => __( 'Search availabilitys' ),  
                'not_found' => __( 'No availabilitys Found' ),
                'not_found_in_trash' => __( 'No availabilitys found in Trash' ),                                         
            ),
            'description' => __('availabilitys to be shown in Resources section.'),
            'public' => false,
            'show_ui' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'menu_position' => 20,
            'supports' => array('title'),
            'capabilities' => array(
				'edit_post'          => 'update_core',
				'read_post'          => 'update_core',
				'delete_post'        => 'update_core',
				'edit_posts'         => 'update_core',
				'edit_others_posts'  => 'update_core',
				'delete_posts'       => 'update_core',
				'publish_posts'      => 'update_core',
				'read_private_posts' => 'update_core'
			)     
        )
    ); 
    //remove_post_type_support('availability','editor'); 
}

function my_manage_columns( $columns ) {
	if(get_post_type() == 'availability'){
		unset($columns['date']);
		//$columns['avail'] = __( 'Availbility', 'your_text_domain' );
	}
	return $columns;
  }
  
  function my_column_init() {
	add_filter( 'manage_posts_columns' , 'my_manage_columns' );
  }
  add_action( 'admin_init' , 'my_column_init' );

  function calender_scripts_styles() {

    $in_footer = false; 

    wp_enqueue_script( 'calender-core-script', CD_PLUGIN_URL . '/packages/core/main.js', array( 'jquery' ), '', $in_footer );
    wp_enqueue_script( 'calender-interaction-script', CD_PLUGIN_URL . '/packages/interaction/main.js', array( 'jquery' ), '', $in_footer );
    wp_enqueue_script( 'calender-daygrid-script', CD_PLUGIN_URL . '/packages/daygrid/main.js', array( 'jquery' ), '', $in_footer );
    wp_enqueue_script( 'calender-timegrid-script', CD_PLUGIN_URL . '/packages/timegrid/main.js', array( 'jquery' ), '', $in_footer );
    wp_enqueue_script( 'bootstrap-popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array( 'jquery' ), '', false );
    wp_enqueue_script( 'bootstrap-min-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ), '', false );
    wp_enqueue_style( 'bootstrap-min-css','https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', array() );
    wp_enqueue_style( 'custom-css', CD_PLUGIN_URL . '/packages/custom.css', array(),'0.5');
    wp_enqueue_style( 'calender-main-css', CD_PLUGIN_URL . '/packages/core/main.css', array() );
    wp_enqueue_style( 'calender-daygrid-css', CD_PLUGIN_URL . '/packages/daygrid/main.css', array() );
    wp_enqueue_style( 'calender-timegrid-css', CD_PLUGIN_URL . '/packages/timegrid/main.css', array() );	
  }

add_action( 'wp_ajax_save_event', 'save_event' );
add_action( 'wp_ajax_nopriv_save_event', 'save_event' );

function save_event(){

    //print_r($_POST);
    //die;

    $postarr = array(
        'post_type' => 'availability',
        'post_title' => $_POST['title'],
        'post_author' => $_POST['user'],
        'post_status' => 'publish' 
        );

    $_post_id = wp_insert_post($postarr);

    if($_post_id){

        update_post_meta($_post_id, 'avail_date', date('Y-m-d',strtotime($_POST['date'])));
        
        $event_date = get_post_meta($_post_id,'avail_date',true);
        echo json_encode(array('title'=>get_the_title($_post_id), 'date'=>$event_date));

    }

    

    wp_die();   

}

add_action( 'wp_ajax_delete_event', 'delete_event' );
add_action( 'wp_ajax_nopriv_delete_event', 'delete_event' );

function delete_event(){

    wp_delete_post($_POST['event_id']);

    echo $_POST['event_id'];

    wp_die();   

}

add_action( 'wp_ajax_show_tooltip', 'show_tooltip' );
add_action( 'wp_ajax_nopriv_show_tooltip', 'show_tooltip' );

function show_tooltip(){
     
   $event_id = $_POST['event_id'];

   $event = get_post( $event_id );

   $user_data = get_userdata($event->post_author);

 // echo $user_data->display_name;

  echo 'asdasdasdasdasdasdas';

   wp_die();   

}


add_action( 'wp_ajax_update_event', 'update_event' );
add_action( 'wp_ajax_nopriv_update_event', 'update_event' );

function update_event(){
     
    $postarr = array(
        'post_type' => 'availability',
        'ID'        => $_POST['event_id'],
        'post_title' => $_POST['title'],
        'post_author' => $_POST['user'],
        'post_status' => 'publish' 
        );
   
   $_post_id = wp_insert_post($postarr);

   update_post_meta($_post_id, 'avail_date', $_POST['date']);

   echo $_post_id ;

   wp_die();   

}



function get_events($user_id){

    $events = array();

    $event = array();
  
    $args = array(
  
          'post_type' => array('availability'),
  
          'post_status' => array('publish'),
  
          'author'         => $user_id,
  
          'order' => 'DESC',
  
          'posts_per_page'=> '-1'
  
      );
  
    $ajaxposts =  get_posts( $args ); 
  
    foreach ( $ajaxposts as  $ajaxpost){
  
    
    $event['id'] = $ajaxpost->ID;
  
    $event['title'] = $ajaxpost->post_title;
  
    $event['start'] = date('Y-m-d',strtotime(get_post_meta($ajaxpost->ID, 'avail_date', true)));
  
    $event['color'] = '#635b8f';
  
     $events[] = $event;

    }

    return $events;

   

}
  add_filter( 'page_template', 'wpa3396_page_template' );
  function wpa3396_page_template( $page_template )
  {
      if ( is_page( 'edit-calendar' ) ) {

          add_action( 'wp_enqueue_scripts', 'calender_scripts_styles' );  
          $page_template = dirname( __FILE__ ) . '/templates/edit-calender.php';
      }

      elseif ( is_page( 'view-calendar' ) ) {

          add_action( 'wp_enqueue_scripts', 'calender_scripts_styles' );   
          $page_template = dirname( __FILE__ ) . '/templates/view-calender.php';
      }
      
      return $page_template;
  }
?>