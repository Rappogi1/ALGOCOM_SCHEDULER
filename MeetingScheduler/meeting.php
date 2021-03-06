<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '/path/to/google-api-php-client/src');
    require_once __DIR__ . '/google-api-php-client/src/Google/autoload.php';

    define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
    define('CREDENTIALS_PATH', '~/.credentials/calendar-php-quickstart.json');
    define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');

    define('AUTH_CODE', __DIR__ . '4/euXj9y4o0gYoA8MO2tkGv9tKbmkF8kLxCFc4omeJHVY');

    define('SCOPES',
        implode(' ', array(
            Google_Service_Calendar::CALENDAR)
        )
    );


function getInitBusy($em,$sdt,$edt){
    $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
  }
    $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfigFile(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = str_replace('~', realpath($homeDirectory), CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = file_get_contents($credentialsPath);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = AUTH_CODE;

    // Exchange authorization code for an access token.
    $accessToken = $client->authenticate($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, $accessToken);
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->refreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, $client->getAccessToken());
  }
    $calendarService = new Google_Service_Calendar( $client );
  $calendarList = $calendarService->calendarList->listCalendarList();

 $calendarArray = [];

  // Put together our calendar array
  while(true) {
      foreach ($calendarList->getItems() as $calendarListEntry) {
          //if($calendarListEntry->id==$em){
            $calendarArray[] = ['id' => $calendarListEntry->id ];
          //}
      }
      $pageToken = $calendarList->getNextPageToken();
      if ($pageToken) {
          $optParams = array('pageToken' => $pageToken);
          $calendarList = $calendarService->calendarList->listCalendarList($optParams);
      } else {
          break;
      }
  }

$idArray = [];
$count = 0;
    //echo '<br>';
foreach($calendarList->getItems() as $calendarListEntry){
    $temp = $calendarListEntry->id;
    foreach($em as $emTemp){
        if($temp == $emTemp){
            $idArray[$count] = $temp;
            //echo $idArray[$count].'<br>';
            $count++;
        }
    }
}

    $freebusy = new Google_Service_Calendar_FreeBusyRequest();
    $freebusy->setTimeMin($sdt);//'2016-08-20T18:00:00+08:00');
  $freebusy->setTimeMax($edt);//'2016-08-30T18:00:00+08:00');
  $freebusy->setTimeZone('Asia/Manila');
  $freebusy->setItems( $calendarArray );
  $createdReq = $calendarService->freebusy->query($freebusy);

    //var_dump($createdReq);


$arr = $createdReq->getCalendars();
//var_dump($arr);
$count = 0;
$cnt = 0;
$busy = [];
$start = [];
$end = [];
$ilan = [];

$count = 0;
    //echo 'IN<br>';
foreach ($arr as $calendarListEntry){
    //echo 'INSIDE<br>';
    $arr_arr =$calendarListEntry->getBusy();
    //var_dump($arr_arr);
    $cnt = 0;
    foreach ($arr_arr as $calendarListEntry){
        //echo 'LIST<br>';
        $start[$cnt] = $calendarListEntry->getStart();
        $end[$cnt] = $calendarListEntry->getEnd();
        $cnt++;
    }
    if($arr_arr!=null){
        //echo $count." ".$cnt." ";
        $busy[$count] = array($start,$end);
        $ilan[$count] = $cnt;
        //echo $ilan[$count].'Yes<br>';
        //echo '<br>';
        $count++;
    }else{
        $ilan[$count] = 0;
    }
}
//echo $ilan[1];

return array($idArray,$busy, $ilan);
}





function addToGoogle($users, $summary, $location, $description, $sdt, $edt){
    $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
  }
    $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->addScope("https://www.googleapis.com/auth/calendar");
  $client->setAuthConfigFile(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = str_replace('~', realpath($homeDirectory), CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = file_get_contents($credentialsPath);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = AUTH_CODE;

    // Exchange authorization code for an access token.
    $accessToken = $client->authenticate($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, $accessToken);
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->refreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, $client->getAccessToken());
  }

    
    $service = new Google_Service_Calendar( $client );
  $event = new Google_Service_Calendar_Event(array(
  'summary' => $summary,
  'location' => $location,
  'description' => $description,
  'start' => array(
    'dateTime' => $sdt,
    'timeZone' => 'Asia/Manila',
  ),
  'end' => array(
    'dateTime' => $edt,
    'timeZone' => 'Asia/Manila',
  ),
  'recurrence' => array(
    'RRULE:FREQ=DAILY;COUNT=1'
  ),
  'attendees' => array(
    array('email' => 'rafael.rodriguez.lozano@gmail.com'),
      array('email' => 'regina_balajadia@dlsu.edu.ph'),
      array('email' => 'rappogi1@gmail.com'),
      array('email' => 'john_martin_lucas@dlsu.edu.ph'),
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
    
$event = $service->events->insert($calendarId, $event);
printf('Event created: %s\n', $event->htmlLink);
}






function ifErrorAuth(){
        $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
  }
    $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->addScope("https://www.googleapis.com/auth/calendar");
  $client->setAuthConfigFile(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = str_replace('~', realpath($homeDirectory), CREDENTIALS_PATH);

    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = AUTH_CODE;

    // Exchange authorization code for an access token.
    $accessToken = $client->authenticate($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, $accessToken);
    printf("Credentials saved to %s\n", $credentialsPath);

  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->refreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, $client->getAccessToken());
  }
}







function getFreeBusy($em,$sdt,$edt){
    $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
  }
    $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfigFile(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = str_replace('~', realpath($homeDirectory), CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = file_get_contents($credentialsPath);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = AUTH_CODE;

    // Exchange authorization code for an access token.
    $accessToken = $client->authenticate($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, $accessToken);
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->refreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, $client->getAccessToken());
  }
    $calendarService = new Google_Service_Calendar( $client );
  $calendarList = $calendarService->calendarList->listCalendarList();

 $calendarArray = [];

  // Put together our calendar array
  while(true) {
      foreach ($calendarList->getItems() as $calendarListEntry) {
          if($calendarListEntry->id==$em){
            $calendarArray[] = ['id' => $calendarListEntry->id ];
          }
      }
      $pageToken = $calendarList->getNextPageToken();
      if ($pageToken) {
          $optParams = array('pageToken' => $pageToken);
          $calendarList = $calendarService->calendarList->listCalendarList($optParams);
      } else {
          break;
      }
  }

//$hackCount = count($calendarArray);
//$calendarArray[$hackCount]='rappogi1@gmail.com';

    $freebusy = new Google_Service_Calendar_FreeBusyRequest();
    $freebusy->setTimeMin($sdt);//'2016-08-20T18:00:00+08:00');
  $freebusy->setTimeMax($edt);//'2016-08-30T18:00:00+08:00');
  $freebusy->setTimeZone('Asia/Manila');
  $freebusy->setItems( $calendarArray );
  $createdReq = $calendarService->freebusy->query($freebusy);

    //var_dump($createdReq);

    //echo $createdReq->getKind().'<br>';
//echo $createdReq->getTimeMin().'<br>'; // works
//echo $createdReq->getTimeMax().'<br>'; // works
$arr = $createdReq->getCalendars();
//var_dump($arr);
//echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
//$arr2 = $createdReq->getGroups();var_dump($arr2);
//$arr3 = $createdReq->();var_dump($arr3);
//echo $createdReq;
//$inCount = 0;
$email = [];
foreach ($calendarList->getItems() as $calendarListEntry) {
        if($calendarListEntry->id==$em){
            //if($calendarListEntry->id=="ita91lgk4o9651eaaphjk025kg@group.calendar.google.com"){
                $email[] =$calendarListEntry->id ;
            //}else{
              //  $email[] =$calendarListEntry->id ;
            //}
        }
            //$inCount++;
    }

$count = 0;
$inCount = 0;
$cnt = 0;
$pos = [];
$start = [];
$end = [];

foreach ($arr as $calendarListEntry){
    $arr_arr =$calendarListEntry->getBusy();
    //echo $calendarListEntry->getId();
    //var_dump($arr_arr);
    foreach ($arr_arr as $calendarListEntry){
        //echo $calendarListEntry->getId();
        if($calendarListEntry->getStart()!=null){
            //$pos[$inCount] = $count;
            //echo $cnt;//$calendarListEntry->getStart();
            $start[$cnt] = $calendarListEntry->getStart();
            $end[$cnt] = $calendarListEntry->getEnd();
            //$inCount++;
            $cnt++;
        }
        //echo $calendarListEntry->getStart().'<br>';
        //echo $calendarListEntry->getEnd();
    }

    //$count++;
}

/*foreach($email as $em){
    if($em=="ita91lgk4o9651eaaphjk025kg@group.calendar.google.com")
    $em='rappogi1@gmail.com';
}*/

//$cnt = 0;
/*foreach($pos as $p){
    echo $email[$p]."<br>";
    echo $start[$cnt]."<br>";
    echo $end[$cnt]."<br>";
    $cnt++;
}*/
return array($pos,$cnt,$email,$start,$end);
}


    /*if (count($query->getItems()) == 0) {
  //print "No upcoming events found.\n";
} else {
  //print "Upcoming events:\n";
  foreach ($query->getItems() as $event) {
    $start = $event->start->date;
    if (empty($start)) {
      $start = $event->start->date;
    }
    echo printf("%s (%s)\n", $event->getSummary(), $start);
  }
}*/


?>
