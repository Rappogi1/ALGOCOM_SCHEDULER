<?php
    session_start();
  // $users = unserialize($_SESSION["users"]);
  // Require our Event class and datetime utilities
  require dirname(__FILE__) . '/userModel.php';
  require dirname(__FILE__) . '/timeslot.php';
  require dirname(__FILE__) . '/../MeetingScheduler/tryAll.php';

  // Read and parse our events JSON file into an array of event data arrays.
  $json = file_get_contents(dirname(__FILE__) . '/../json/user.json');
  $input_arrays = json_decode($json, true);
  $users = array();
  $userEmails = array();

    foreach ($input_arrays as $key1 => $value1) {
      $user = new User($input_arrays[$key1]["Name"],$input_arrays[$key1]["Email"], true, false);
      // echo $user->name."<br>";
      // echo $user->email."<br>";
      array_push($userEmails, $input_arrays[$key1]["Email"]);
      array_push($users, $user);
    }

  $eventName = $_POST["eventName"];
  $location = $_POST["location"];
  $startDate = $_POST["startDate"];
  $endDate = $_POST["endDate"];
  $startTime = $_POST["startTime"];
  $endTime = $_POST["endTime"];
  $duration = $_POST["eventDuration"];

  echo $eventName.'<br/>';
  echo $location.'<br/>';
  echo "Date".$startDate.'<br/>';
  echo $endDate.'<br/>';
  echo "Time".$startTime.'<br/>';
  echo $endTime.'<br/>';
  echo "Duration: ".$duration.'<br/>';

  $dayStart = str_replace('-', '/', $startDate);
  $dayEnd = str_replace('-', '/', $endDate);

  $startDateTime = date('Y-m-d H:i:s', strtotime("$dayStart $startTime"));
  $endDateTime = date('Y-m-d H:i:s', strtotime("$dayEnd $endTime"));
  $endDateTime = date('Y-m-d H:i:s', strtotime($endDateTime) - $duration * 60);

  /*Acceptance Function*/
  function acceptance_probability($newSolution, $oldSolution, $temperature){
      if($newSolution > $oldSolution){
        return 1.0;
      }
      return exp(($newSolution-$oldSolution)/$temperature);
  }
  /*Rounds time to the nearest quarter time*/
  function roundTime($time){
    return date('Y-m-d H:i:s',round(strtotime($time) / (15 * 60)) * (15 * 60));
  }
  /*Random Date and Time*/
  function getRandomTime($startDateTime, $endDateTime){
    //Get a random day date from day scope
    $dayRand = mt_rand(strtotime($startDateTime), strtotime($endDateTime));
    $timeSlot = date('Y-m-d H:i:s', $dayRand);
    //echo "Timeslot".$timeSlot."<br>";
    $roundedTimeSlot = roundTime($timeSlot);
    //echo "roundedTimeSlot".$roundedTimeSlot."<br>";
    return $roundedTimeSlot;
  }
  function toGoogleFormat($time){
    $gmt = "+08:00";
    $dat = date('Y-m-d', strtotime($time));
    $tme = date('H:i:s',strtotime($time));

    $timeGoogle = $dat."T".$tme.$gmt;

    return $timeGoogle;
  }

  /*this is rap's function*/
  function score($timeStart, $timeEnd, $users){
    //echo "iteration: Start: ".$timeStart."End: ".$timeEnd."<br>";
    //"2016-08-23T09:30:00+08:00"
    $timeStartGoogle = toGoogleFormat($timeStart);
    $timeEndGoogle = toGoogleFormat($timeEnd);

    $score=0;
    $available = array();
    $notavailable = array();

    foreach($users as $user){
      //echo $user->email."<br>";
      $isBusy = checkIfBusy($user->email, $timeStartGoogle, $timeEndGoogle);
      if($isBusy == 1){
        $score = $score + 3;
        array_push($available, $user);
      }
      else{
        array_push($notavailable, $user);
      }
    }
    // echo "available"."<br>";
    // foreach($available as $avail){
    //   echo $avail->name."<br>";
    // }
    //
    // echo "not Available"."<br>";
    // foreach($notavailable as $notAvail){
    //   echo $notAvail->name."<br>";
    // }
    $timeResults = new Timeslot($timeStart, $timeEnd, $score, $available, $notavailable);

    return $timeResults;
    // echo "Score:".$score."<br>";
  }

  function getNeighboringSolution($oldSolution){
      $doWhat = mt_rand(1,4);
      switch($doWhat){
        /*Add one Day*/
        case 1: $newSolution = date('Y-m-d H:i:s',date(strtotime("+1 day", strtotime($oldSolution))));
                //echo "1"."<br>";
                break;
        /*Minus one Day*/
        case 2: $newSolution = date('Y-m-d H:i:s',date(strtotime("-1 day", strtotime($oldSolution))));
                //echo "2"."<br>";
                break;
        /*Add 30 minutes*/
        case 3: $newSolution = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($oldSolution)));
                //echo "3"."<br>";
                break;
        /*Minus 30 minutes*/
        case 4: $newSolution = date('Y-m-d H:i:s', strtotime('-30 minutes', strtotime($oldSolution)));
                //echo "4"."<br>";
                break;
      }
      return $newSolution;
  }
  /*Annealing*/
  function anneal($startDateTime, $endDateTime, $duration, $users){
    $timeSlots = array();
    $temperature = 1.2;
    $temp_min = 1;
    $coolRate = 0.9;

    /*initializes a random solution*/
    $randomStartTime = getRandomTime($startDateTime, $endDateTime);
    /*this add the duration in seconds to the start time in $oldSolution*/
    $randomEndTime = date('Y-m-d H:i:s', strtotime($randomStartTime) + $duration * 60);
    /*this is the score of the timeslot, along with the available participants*/
    $randomTimeslot= score($randomStartTime, $randomEndTime, $users);

     while ($temperature > $temp_min) {
         $i = 1;
         while ($i <= 2) {
             $newRandomStartTime = getNeighboringSolution($startDateTime, $endDateTime);
             $newRandomEndTime = date('Y-m-d H:i:s', strtotime($newRandomStartTime) + $duration * 60);
             $newRandomTimeslot = score($newRandomStartTime, $newRandomEndTime, $users);
             $ap = acceptance_probability($newRandomTimeslot->score, $randomTimeslot->score, $temperature);
             if ($ap > rand()){
                 $randomTimeslot = $newRandomTimeslot;
             }
             $i += 1;
         $temperature = $temperature * $coolRate;
       }
       array_push($timeSlots, $randomTimeslot);
    }
    return $timeSlots;
  }

  initiateBusy($userEmails, toGoogleFormat($startDateTime), toGoogleFormat($endDateTime));

  $timeSlots = array();
  $timeSlots = anneal($startDateTime,$endDateTime,  $duration, $users);

  if (is_array($timeSlots) || is_object($timeSlots))
  {
    foreach($timeSlots as $time){
      echo "timestart: ".$time->datetimeStart."<br>";
      echo "timeend: ".$time->datetimeEnd."<br>";
      echo "score: ".$time->score."<br>";
      echo "Available: <br>";

      foreach($time->available as $avail){
            echo $avail->name."<br>";
      }
      echo "Not Available: <br>";
      foreach($time->notAvailable as $notAvail){
        echo $notAvail->name."<br>";
      }
    }
  }
  else{
    echo "timeslots is not an array <br>";
  }
  // $_SESSION['timeslotResult'] = serialize($timeslotResult);

?>
