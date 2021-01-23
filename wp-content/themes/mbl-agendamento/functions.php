<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
define("URLTEMA", get_stylesheet_directory_uri());
define("SITEURL", get_bloginfo("url") );
$tema = get_template_directory();
$tema = str_replace("storefront", "mbl-agendamento", $tema) . '/api';
define("THEMEPATH", $tema );


function sf_child_theme_dequeue_style() {  
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}
// include custom jQuery
function shapeSpace_include_custom_jquery() {
	wp_deregister_script('jquery');
    
    wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600&display=swap',false,'1.1','all');

	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array(), null, false);
   
    wp_enqueue_script( 'functions',  get_stylesheet_directory_uri() . '/assets/js/functions.js', array ( 'jquery' ), 1.1, true);

}
add_action('wp_enqueue_scripts', 'shapeSpace_include_custom_jquery');


add_action("after_setup_theme", "storefront_child_header_remove");
function storefront_child_header_remove()
{
    remove_action("storefront_header","storefront_site_branding",20);
    remove_action("storefront_header","storefront_secondary_navigation",30);
    remove_action("storefront_header","storefront_product_search",40);
    remove_action("storefront_header","storefront_primary_navigation_wrapper",42);
    remove_action("storefront_header","storefront_primary_navigation",50);
    remove_action("storefront_header","storefront_header_cart",60);
    remove_action("storefront_header","storefront_primary_navigation_wrapper_close",68);
   	//add_action("storefront_before_footer","newsletter",35);
   	add_action("storefront_footer","custom_footer",35);
   	//add_action("storefront_after_footer","dados",35);
}

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Inputa o numero do telefone no checkout 
function custom_override_checkout_fields( $fields ) {
  global $woocommerce;
  $fields['billing']['billing_phone']['default'] = $woocommerce->session->get( 'billing_phone');
     
  return $fields;
}

//================================================================
//******************** ALERTAS E LEMBRETES ***********************
//================================================================

// --------------------- CANCELAMENTO --------------------------//

//ADICIONA EMAIL DO CONSUMIDOR NO CANCELAMENTO

add_action('woocommerce_order_status_changed', 'custom_send_email_notifications', 10, 4 );
function custom_send_email_notifications( $order_id, $old_status, $new_status, $order ){
    if ( $new_status == 'cancelled' || $new_status == 'failed' ){
        $wc_emails = WC()->mailer()->get_emails(); // Get all WC_emails objects instances
        $customer_email = $order->get_billing_email(); // The customer email
        print_r($customer_email);
    }

    if ( $new_status == 'cancelled' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_Cancelled_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_Cancelled_Order']->trigger( $order_id );

        //envie mensagem de cancelamento para o consumidor

    } 
    elseif ( $new_status == 'failed' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_failed_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_failed_Order']->trigger( $order_id );
    } 
}


// --------------------- NOVO PEDIDO --------------------------//
function new_order_msg( $order_id ){
    // $order = wc_get_order( $order_id );
    // $data = json_decode($order);
    
    // $numberto = $data->billing->phone;
}

// --------------------- PEDIDO EM PROCESSAMENTO --------------------------//
function order_processing( $order_id) {
   $order = wc_get_order( $order_id );
  $order->update_status('completed');
}
add_action( 'woocommerce_order_status_processing', 'order_processing', 10, 1 );

//--------------------- PEDIDO CONCLUIDO -----------------------------//
function order_completed( $order_id) {
  //recupera dados do pedido no woocommerce
  $order = wc_get_order( $order_id );
  $obj_order = json_decode($order);

  $phone = $obj_order->billing->phone;
  $cliente = $obj_order->billing->first_name;
  $email = $obj_order->billing->email;

  //guarda ID da consulta
  $appt = get_appointment_msg($order_id, 'booked_approval_email_content');

  //Pega a data em Timestamp da consulta
  $data = get_post_meta($appt['appt_id'], '_appointment_timestamp');
  $dia =  date('Y-m-d', $data[0]);
  $inicio = date('H:i:s', $data[0]);

  //pega o horario da consulta no formato 0000-0000
  $time = get_post_meta($appt['appt_id'], '_appointment_timeslot');
  $timeslot = explode("-", $time[0]);
  $final = substr($timeslot[1], 0, 2) . ":" . substr($timeslot[1], -2). ":00";
  
  //texto padrão da confirmação
  $t = strip_tags($appt['text']);
  $t = urlencode($t);
  //remove caractere que quebra whatsapp
  $s = str_replace("%26ndash%3B", ' até ', $t);
  $text = urldecode($s);

  $itemN = "";

  //checa itens da compra
  foreach ( $order->get_items() as $item_id => $item ) {
    $itemN .= ($item->get_name());
  }

  $itemName = str_replace("Video", "Vídeo", $itemN);
  $meet = (strpos($itemName, "Vídeo") === false ) ? false : true;
  $local = (strpos($itemName, "Presencial") === false) ? false : true;

  $address = ($local) ? "Av. Dom Luís, 300 - L2, Aldeota, Fortaleza, CE" : "";

  if ($local){
    $text = str_replace("{--------------}", "*Local: " . $address . "*", $text);
  } 
  

  //GOOGLE CALENDAR

  require THEMEPATH . '/vendor/autoload.php';

  $scopes ="https://www.googleapis.com/auth/calendar";  

  $client = new Google\Client();
  $client->setDeveloperKey('AIzaSyAzieEovufQbUXige45az3C0is4OZEhkKk');
  
  putenv('GOOGLE_APPLICATION_CREDENTIALS='.THEMEPATH .'/mbl-consultas-302321-bd6bef0be7f4.json');
  $client->setApprovalPrompt('force');
  $client->useApplicationDefaultCredentials();
  $client->setScopes(Google_Service_Calendar::CALENDAR);
  $client->setSubject('juridico@unito.med.br');
  $client->setApplicationName("Mbl Consultas API KEY");
  
  $service = new Google_Service_Calendar($client);

  $args = array(
    'summary' => 'Consulta com: ' . $cliente,
    'location' => $address,
    'description' => $itemName,
    'start' => array(
      'dateTime' => $dia . 'T' . $inicio,
      'timeZone' => 'America/Fortaleza',
    ),
    'end' => array(
      'dateTime' => $dia . 'T' . $final,
      'timeZone' => 'America/Fortaleza',
    ),
    'recurrence' => array(
      'RRULE:FREQ=DAILY;COUNT=1'
    ),
   
    'attendees' => array(
      array('email' => 'juridico@unito.med.br'),
      array('email' => $email),
    ),
    'reminders' => array(
      'useDefault' => FALSE,
      'overrides' => array(
        array('method' => 'email', 'minutes' => 24 * 60),
        array('method' => 'popup', 'minutes' => 10),
      ),
    ),
  );
 // print_r('<pre>');
 //  print_r($args);
 // print_r('</pre>');
  $event = new Google_Service_Calendar_Event($args);

  $calendarId = 'primary';
  $event = $service->events->insert($calendarId, $event);
  
  if ($meet):
    $conference = new \Google_Service_Calendar_ConferenceData();
    $conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
    $conferenceRequest->setRequestId('randomString123');
    $conference->setCreateRequest($conferenceRequest);
    $event->setConferenceData($conference);
    $eventI = $service->events->patch($calendarId, $event->id, $event, ['conferenceDataVersion' => 1]);
    add_post_meta( $order_id, '_beta_digital_meet_link', $eventI->hangoutLink );

    $text = str_replace("{--------------}", "*Link da Reunião: " . $eventI->hangoutLink . "*", $text);
  
  endif;

  sendWhatsApp($phone, $text);

}
add_action( 'woocommerce_order_status_completed', 'order_completed', 10, 1 );


function cancelled_order($order_id){
  $order = wc_get_order( $order_id );
  $obj_order = json_decode($order);

  $phone = $obj_order->billing->phone;
  $cliente = $obj_order->billing->first_name;
  $email = $obj_order->billing->email;

  $text = "Olá, " . $cliente . "\n";
  $text .= "Até o presente momento não identificamos o seu pagamento, por essa razão o agendamento da sua consulta não foi concluído. No entanto, encorajamos a nos procurar para o que precisar.\n\n";
  $text .= "Estamos à disposição,\nMBL Advogados";


  sendWhatsApp($phone, $text);
}

add_action( 'woocommerce_order_status_cancelled', 'cancelled_order', 21, 1 );

function do_approval($order_id){
  global $woocommerce;

  $order = wc_get_order($order_id);
  
  $data = json_decode($order);

  $phone = $data->billing->phone;
  $cliente = $data->billing->first_name;
  $email = $data->billing->email;

  $appt = get_appointment_msg($order_id, 'booked_approval_email_content');

  //Pega a data em Timestamp da consulta
  $data = get_post_meta($appt['appt_id'], '_appointment_timestamp');
  $dia =  date('Y-m-d', $data[0]);
  $inicio = date('H:i:s', $data[0]);

  //pega o horario da consulta no formato 0000-0000
  $time = get_post_meta($appt['appt_id'], '_appointment_timeslot');
  $timeslot = explode("-", $time[0]);
  $final = substr($timeslot[1], 0, 2) . ":" . substr($timeslot[1], -2). ":00";
   
  $itemName = "";

  foreach ( $order->get_items() as $item_id => $item ) {
    $itemName .= ($item->get_name());
  }
  $itemName = str_replace("Video", "Vídeo", $itemName);
  $meet = (strpos($itemName, "Vídeo") === false ) ? false : true;
  $local = (strpos($itemName, "Presencial") === false) ? false : true;

  $address = ($local) ? "Av. Dom Luís, 300 - L2, Aldeota, Fortaleza, CE" : "";
  $t = $appt['text'];
  $addText = "Local: " . $address . "<br>Cordialmente,<br>" ;

  if ($local):
   // $t = str_replace("Cordialmente,", $addText, $t);
  endif;
  
  $s = str_replace("\n", '%0D%0A', $t);


  //GOOGLE CALENDAR

  require THEMEPATH . '/vendor/autoload.php';

  $scopes ="https://www.googleapis.com/auth/calendar";  

  $client = new Google\Client();
  $client->setDeveloperKey('AIzaSyAzieEovufQbUXige45az3C0is4OZEhkKk');
  
  putenv('GOOGLE_APPLICATION_CREDENTIALS='.THEMEPATH .'/mbl-consultas-302321-bd6bef0be7f4.json');
  $client->setApprovalPrompt('force');
  $client->useApplicationDefaultCredentials();
  $client->setScopes(Google_Service_Calendar::CALENDAR);
  $client->setSubject('juridico@unito.med.br');
  $client->setApplicationName("Mbl Consultas API KEY");
  
  $service = new Google_Service_Calendar($client);

  $args = array(
    'summary' => 'Consulta com: ' . $cliente,
    'location' => $address,
    'description' => $itemName,
    'start' => array(
      'dateTime' => $dia . 'T' . $inicio,
      'timeZone' => 'America/Fortaleza',
    ),
    'end' => array(
      'dateTime' => $dia . 'T' . $final,
      'timeZone' => 'America/Fortaleza',
    ),
    'recurrence' => array(
      'RRULE:FREQ=DAILY;COUNT=1'
    ),
   
    'attendees' => array(
      array('email' => 'juridico@unito.med.br'),
      array('email' => $email),
    ),
    'reminders' => array(
      'useDefault' => FALSE,
      'overrides' => array(
        array('method' => 'email', 'minutes' => 24 * 60),
        array('method' => 'popup', 'minutes' => 10),
      ),
    ),
  );
 print_r('<pre>');
  print_r($args);
 print_r('</pre>');
  $event = new Google_Service_Calendar_Event($args);

  $calendarId = 'primary';
  $event = $service->events->insert($calendarId, $event);
  
  if ($meet):
    $conference = new \Google_Service_Calendar_ConferenceData();
    $conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
    $conferenceRequest->setRequestId('randomString123');
    $conference->setCreateRequest($conferenceRequest);
    $event->setConferenceData($conference);
    $eventI = $service->events->patch($calendarId, $event->id, $event, ['conferenceDataVersion' => 1]);
  endif;

}


//Obtem a mensagem de confirmação de consulta
function get_appointment_msg($order_id, $getMessage){
  $args = array(
    'post_type' => 'booked_appointments',
    'posts_per_page' => 1,
    'post_status' => array('publish','future'),
    'meta_query' => array(
      array(
        'key'     => '_booked_wc_appointment_order_id',
        'value'   => $order_id
      )
    )
  );

  $bookedAppointments = new WP_Query($args);
  
  if($bookedAppointments->have_posts()):
    //print_r($bookedAppointments);
    
    while ($bookedAppointments->have_posts()):

      $bookedAppointments->the_post();
      global $post;

      $appt_id = $post->ID;
      $email_content = get_option($getMessage,false);
      
      if ($email_content):

        //$token_replacements = booked_get_appointment_tokens( $appt_id );
        //print_r($token_replacements);
        //$email_content = booked_token_replacement( $email_content,$token_replacements );
        return array('text'=>$email_content, 'appt_id'=> $appt_id);
        
        endif;
    endwhile;
  else:
    return array('text'=>'', 'appt_id'=> 0);
  endif;

  wp_reset_postdata();
}

function sendWhatsApp($phone, $text){
  $token = '666283b487f5a0223a967e82e739d0bc600808fd29737';
  $number = '558597947644';
  $phoneT = '55' . preg_replace("/[^0-9]/", "", $phone);
  $numberto = $phoneT;

  $date = current_time('timestamp');
 // print_r('<br>' . urlencode($text) );


  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://www.waboxapp.com/api/send/chat");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "token=". $token . "&uid=" . $number . "&to=". $numberto ."&custom_uid=mbl-".$date."&text=" . $text);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 20);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 25);

  $response = curl_exec($ch);
  $info = curl_getinfo($ch);
  curl_close ($ch);
  print_r("<pre>");
  print_r($info);
  print_r($response);
  print_r("<br>token=". $token . "&uid=" . $number . "&to=". $numberto ."&custom_uid=mbl-".$date."&text=" . urlencode($text));
   print_r("</pre>");
}

add_action( 'woocommerce_thankyou', 'new_order_msg',  10, 1  );



//CRON JOBS - LEMBRETES

function user_reminders(){

  $user_reminder_buffer = 40;//get_option('booked_reminder_buffer',30);

  $start_timestamp = current_time('timestamp');
  $end_timestamp = strtotime(date_i18n('Y-m-d H:i:s',current_time('timestamp')).' + '.$user_reminder_buffer.' minutes');

  $args = array(
    'post_type' => 'booked_appointments',
    'posts_per_page' => 500,
    'post_status' => array('publish','future'),
    'meta_query' => array(
      array(
        'key'     => '_appointment_timestamp',
        'value'   => array( $start_timestamp, $end_timestamp ),
        'compare' => 'BETWEEN',
      )
    )
  );



  $bookedAppointments = new WP_Query($args);
  //print_r($bookedAppointments);
  if($bookedAppointments->have_posts()):
    
    while ($bookedAppointments->have_posts()):

      $bookedAppointments->the_post();
      global $post;

      $appt_id = $post->ID;
      $reminder_sent = get_post_meta($appt_id,'_appointment_user_reminder_sent',true);
      $order_id = get_post_meta($appt_id, '_booked_wc_appointment_order_id');
      print_r($order_id[0]);
      $order = wc_get_order( $order_id[0] );
      //print_r($order);
      $data = json_decode($order);
    
      $numberto = $data->billing->phone;


      $send_mail = true;
      //if ( !$reminder_sent && apply_filters( 'booked_prepare_sending_reminder', true, $appt_id ) ):

        $email_content = get_option('booked_reminder_email',false);
        $email_subject = get_option('booked_reminder_email_subject',false);

        if ($email_content && $email_subject):

          //$token_replacements = booked_get_appointment_tokens( $appt_id );
          //print_r($token_replacements);
          //$email_content = booked_token_replacement( $email_content,$token_replacements );
          //$email_subject = booked_token_replacement( $email_subject,$token_replacements );


          $text = "Oi " . $data->billing->first_name . '%0D%0A';
          //$text .= $email_content;
          print_r($numberto);
          print_r($text);
          sendWhatsApp($numberto, $text);

          //update_post_meta($appt_id,'_appointment_user_reminder_sent',true);

          //do_action( 'booked_reminder_email', $token_replacements['email'], $email_subject, $email_content );

        endif;

      //endif;

    endwhile;

  endif;

  wp_reset_postdata();

}


//================================================================
//****************** FIM ALERTAS E LEMBRETES *********************
//================================================================

//============= CUSTOM FUNCTIONS ===============//

// CUSTOM FUNCTIONS //


