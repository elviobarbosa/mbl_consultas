<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/include/intialise.php';
$calendar = new Calendar();

if(!empty($_GET['code']))
{
    $calendar->Authenticate($_GET['code']);
    if($calendar->get_user_profile())
    {
        $_SESSION['user_info']['name']=$calendar->get_user_profile()['name'];
    }
    header('Location:index.php');
}
elseif(!empty($_POST))
{

    $client=$calendar->Authenticate();
    $days_off=array('MON');  // mention day that we don't won't to create event

    $cal_week_days=array('SU' => 'SUN', 'MO' => 'MON', 'TU' => 'TUE', 'WE' => 'WED', 'TH' => 'THU', 'FR' => 'FRI', 'SA' => 'SAT');
    // declare week days

    $cal_all_week_except_days_off = array_flip(array_diff($cal_week_days, $days_off)); //diffrent value // array fip change key value pair
    $cal_weekly_days=implode(',',$cal_all_week_except_days_off); //comma separated value

    $event_dates=explode('-',$_POST['daterange']);
    $response_data['start_date_time']=$start_time = gmdate("Y-m-d\TH:i:s\Z",strtotime($event_dates[0]));  
    $response_data['end_date_time']=gmdate("Y-m-d\TH:i:s\Z",strtotime($event_dates[0].' +1 hour'));
    $response_data['end_date']=gmdate("Y-m-d\TH:i:s\Z",strtotime($event_dates[1]));
    $response_data['summary']=$_POST['event_name'];
    $response_data['location']=$_POST['event_location'];
    $response_data['description']=$_POST['event_description'];
    $response_data['cal_weekly_days']=$cal_weekly_days;  // event occur only for this week day

    $calendar_response=$calendar->CreateEvent($client,$response_data); // Create google calendar event 

    if(!$calendar_response['code'])
    {
        echo "<h4>".$calendar_response['message']."</h4>";
        echo "<pre>".json_encode($calendar_response['data'], JSON_PRETTY_PRINT)."</pre>";
    }
    else
    {
        echo $calendar_response['message'];exit;
    }
    
    
}
else
{
    header('Location:login.php');
}

?>