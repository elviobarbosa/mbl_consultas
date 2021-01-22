<?php
require THEMEPATH . '/vendor/autoload.php';

$scopes ="https://www.googleapis.com/auth/calendar"; 	
// $cred = new Google_Auth_AssertionCredentials(	 
// 	'33255808685-qtdttmb2es32qjkl7c6lsm59o7mflf9o.apps.googleusercontent.com',	 	 
// 	array($scopes),	 	
// 	'fDLGtNcQb-XFBJju44Pd5rgY'	 	 
// 	);


$client = new Google\Client();
$client->setDeveloperKey('AIzaSyAzieEovufQbUXige45az3C0is4OZEhkKk');
//$client->setAuthConfig(THEMEPATH .'/client_secret.json');

putenv('GOOGLE_APPLICATION_CREDENTIALS='.THEMEPATH .'/mbl-consultas-302321-bd6bef0be7f4.json');
$client->setApprovalPrompt('force');
$client->useApplicationDefaultCredentials();
$client->setScopes(Google_Service_Calendar::CALENDAR);
$client->setSubject('juridico@unito.med.br');
//$client->setAuthConfig(THEMEPATH .'/credentials.json');
//print_r($client);
// if($client->getAuth()->isAccessTokenExpired()) {	 	
// 	$client->getAuth()->refreshTokenWithAssertion($cred);	 	
// }	 	
// $service = new Google_Service_Calendar($client);  


// $calendarList->listCalendarList();

// make an HTTP request
//$response = $httpClient->get('https://www.googleapis.com/auth/calendar');

$client->setApplicationName("Mbl Consultas API KEY");
//$client->setDeveloperKey("AIzaSyAOPt1snP64_XPqLbdv6Z4eGBwRoJsZsK8");



// if ($credentials_file = checkServiceAccountCredentialsFile()) {
//   // set the location manually
//   $client->setAuthConfig($credentials_file);
// } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
//   // use the application default credentials
//   $client->useApplicationDefaultCredentials();
// } else {
//   echo missingServiceAccountDetailsWarning();
//   return;
// }

// $client->setApplicationName("Client_Library_Examples");
// $client->setScopes(['https://www.googleapis.com/auth/books']);
 //$service = new Google_Service_Calendar($client);


$service = new Google_Service_Calendar($client);

$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Teste 12',
  'location' => '1455 João Brígido, Fortaleza, CE',
  'description' => 'Testando criar meeting',
  'start' => array(
    'dateTime' => '2021-01-26T09:00:00',
    'timeZone' => 'America/Fortaleza',
  ),
  'end' => array(
    'dateTime' => '2021-01-26T10:00:00',
    'timeZone' => 'America/Fortaleza',
  ),
  'recurrence' => array(
    'RRULE:FREQ=DAILY;COUNT=1'
  ),
 
  'attendees' => array(
    array('email' => 'juridico@unito.med.br'),
    array('email' => 'elviobarbosa@gmail.com'),
  ),
  "conferenceDataVersion" => 1,
  'conferenceData' => array(
  	//'conferenceId' => "aaa-bbbb-ccc",

  	// 'conferenceSolution' => array(
  	// 	'key' => array(
  	// 		'type' => 'hangoutsMeet'
  	// 	),
  	// 	'name' => 'Hangout'
  	// ),
	'createRequest'  => array(
		 'requestId' => '7qxalsvy0e',
	  	'conferenceSolutionKey' => array(
	  		'type' => 'hangoutsMeet'

	  	),
	  	
	  	'status' => array(
	  		'statusCode' => 'pending'
	  	)
	  ),
	),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 24 * 60),
      array('method' => 'popup', 'minutes' => 10),
    ),
  ),
));

$calendarId = 'primary';
print_r('<pre>');
//print_r($service->events);
print_r('</pre>');

$event = $service->events->insert($calendarId, $event);
printf('Event created: %s\n', $event->htmlLink);

$conference = new \Google_Service_Calendar_ConferenceData();
$conferenceRequest = new \Google_Service_Calendar_CreateConferenceRequest();
$conferenceRequest->setRequestId('randomString123');
$conference->setCreateRequest($conferenceRequest);
$event->setConferenceData($conference);

$eventI = $service->events->patch($calendarId, $event->id, $event, ['conferenceDataVersion' => 1]);

printf('<br>Conference created: %s', $eventI->hangoutLink);

$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => true,
  'timeMin' => date('c'),
);
$results = $service->events->listEvents($calendarId, $optParams);
$events = $results->getItems();
if (empty($events)) {
    print "No upcoming events found.\n";
} else {
    print "Upcoming events:\n";
    foreach ($events as $event) {
        $start = $event->start->dateTime;
        if (empty($start)) {
            $start = $event->start->date;
        }
        printf("%s (%s)\n", $event->getSummary(), $start);
    }
}



?>