<?php
require_once  THEMEPATH .'/include/intialise.php';

class Calendar
{
    private $client;

    function __construct()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setAuthConfig(THEMEPATH.'/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setScopes(array('https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));
        $this->client=$client;
        $this->google_oauth =new Google_Service_Oauth2($this->client);
    }

    public function GetCalendarAccessUrl()   // Generate Google calendar access url and login url
    {
        return $this->client->createAuthUrl();
    }

    public function Authenticate($code=NULL) // Authenticate user after redirecting from google
    {
        
        if(!empty($code)) //  pass the AuthCode return by google
        {
            $this->client->authenticate($code); //  Authonticate Auth code
            $access_token = $this->client->getAccessToken(); // Get Access token
            $refresh_token=$this->client->getRefreshToken();  // Get Refresh token
          
            if(!empty($access_token)) 
            {
                $_SESSION['access_token']=$access_token;
            }
            if(!empty($refresh_token))
            {
                $_SESSION['refresh_token']=$refresh_token;
            }

        }
        $this->client->setAccessToken($_SESSION['access_token']); // set the access token

        // check the access token is expired or not if expired fetcg new one using refresh token
        if ($this->client->isAccessTokenExpired()) {
            $access_stoken =$this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        }
        
        
        
        return $this->client;
    }

    public function GetEvent($client,$calendarId='') // Get calendar event details
    {
        try {
            $service = new Google_Service_Calendar($client);
            // Print the next 10 events on the user's calendar.
         
            $results = $service->events->get('primary', $calendarId);
            $events = $results->getSummary();
            return $results;
            }
            catch (Google_Service_Exception $e ) {
                if(json_decode($e->getMessage())->error->message!='Not Found')
               {
                $response_data['code']=1;
                $response_data['message']=json_decode($e->getMessage())->error->message;
                $response_data['data']='';
                return $response_data;
               }
               
            } 
    
    }

    public function get_user_profile()  // Get user profile calendar event details
    {
        return($this->google_oauth->userinfo->get());
    }

    public function CreateEvent($client,$data)  // Create calendar event details
    {
        try {
            $service = new Google_Service_Calendar($client);
            $calendarId = 'primary';
        
            $event = new Google_Service_Calendar_Event(array(
                'summary' => $data['summary'],
                'location' => $data['location'],
                'description' => $data['description'],
                'start' => array(
                'dateTime' =>  $data['start_date_time'], //The (inclusive) start time of the event. For a recurring event, this is the start time of the first instance.
                'timeZone' => 'Asia/Kolkata',
                ),
                'end' => array(
                'dateTime' => $data['end_date_time'], //The (exclusive) end time of the event. For a recurring event, this is the end time of the first instance.
                'timeZone' => 'Asia/Kolkata',
                ),
                'recurrence' => array(
                    "RRULE:FREQ=WEEKLY;UNTIL=".date('Ymd\THis\Z',strtotime($data['end_date'])).";BYDAY=".$data['cal_weekly_days'].""
                ),
                'attendees' => array( ),
                'reminders' => array(
                'useDefault' => FALSE,
                'overrides' => array(
                    array('method' => 'email', 'minutes' => 30),
                    array('method' => 'popup', 'minutes' => 10),
                ),
                ),
            ));
            // dd($event);
            $events = $service->events->Insert($calendarId, $event);
          
            if (empty($events)) {
                $response_data['code']=1;
                $response_data['message']="Error While Creating Google calendar Event";
                $response_data['data']='';
                return $response_data;
            } else {
                $response_data['code']=0;
                $response_data['message']="Google calendar Event Created";
                $response_data['data']=$this->GetEvent($client,$events->getId());
                return $response_data;
            }
        }
        catch (Google_Service_Exception $e ) {
            $response_data['code']=1;
            $response_data['message']=json_decode($e->getMessage())->error->message;
            $response_data['data']='';
            return $response_data;
        }
    }

}
?>