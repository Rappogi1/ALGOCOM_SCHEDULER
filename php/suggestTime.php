<?php
    session_start();
  // $users = unserialize($_SESSION["users"]);
  // Require our Event class and datetime utilities
    $gFB; // Initiate this first
  require dirname(__FILE__) . '/userModel.php';
  require dirname(__FILE__) . '/timeslot.php';
  require dirname(__FILE__) . '/../MeetingScheduler/tryAll.php';

  // // Read and parse our events JSON file into an array of event data arrays.
  // $json = file_get_contents(dirname(__FILE__) . '/../json/user.json');
  // $input_arrays = json_decode($json, true);
  $users = array();
  $userEmails = array();

  $eventName = $_POST["eventName"];
  $location = $_POST["location"];
  $startDate = $_POST["startDate"];
  $endDate = $_POST["endDate"];
  $startTime = $_POST["startTime"];
  $endTime = $_POST["endTime"];
  $duration = $_POST["eventDuration"];
  $invited = $_POST['invited'];
  $priority = $_POST['priority'];
  //
  // echo $eventName.'<br/>';
  // echo $location.'<br/>';
  // echo "Date".$startDate.'<br/>';
  // echo $endDate.'<br/>';
  // echo "Time".$startTime.'<br/>';
  // echo $endTime.'<br/>';
  // echo "Duration: ".$duration.'<br/>';


  // echo "invited <br>";
  foreach ($invited as $person) {
      // echo $person;

      if(in_array($person, $priority)){
        // echo "priority"."<br>";
        $user = new User($person,$person, 1);
      }
      else{
        $user = new User($person,$person, 0);
      }
      array_push($userEmails, $person);
      array_push($users, $user);
  }

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
    $startDay = date('M-d-Y',strtotime($startDateTime));
    $endDay = date('M-d-Y', strtotime($endDateTime));
    $startTime= date('H:i', strtotime($startDateTime));
    $endTime = date('H:i', strtotime($endDateTime));
    // echo "start day:".$startDay."start time:".$startTime."<br>";
    // echo "end day:".$endDay."end time:".$endTime."<br>";

    $randomDay = date('M-d-Y', mt_rand(strtotime($startDay),strtotime($endDay)));
    $randomTime = date('H:i', mt_rand(strtotime($startTime),strtotime($endTime)));

    // echo "random day:".$randomDay."random time:".$randomTime."<br>";

    $timeSlot = date('Y-m-d H:i:s', strtotime("$randomDay $randomTime"));

    $roundedTimeSlot = roundTime($timeSlot);
    // echo "randomtime: ".$roundedTimeSlot;
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
      global $gFB;
        setIBData($gFB);
    $timeStartGoogle = toGoogleFormat($timeStart);
    $timeEndGoogle = toGoogleFormat($timeEnd);

    $score=0;
    $available = array();
    $notavailable = array();

    foreach($users as $user){
      //echo $user->email."<br>";
      $isBusy = checkIfBusy($user->email, $timeStartGoogle, $timeEndGoogle);
      if($isBusy == 1){
        if($user->isPriority == 1){
          $score = $score + 3;
        }
        else{
          $score = $score + 1;
        }
        array_push($available, $user);
      }
      else{
        array_push($notavailable, $user);
      }
    }
    $timeResults = new Timeslot($timeStart, $timeEnd, $score, $available, $notavailable);
    return $timeResults;
    // echo "Score:".$score."<br>";
  }

  function getNeighboringSolution($oldSolution, $startDateTime, $endDateTime, $duration){

    while(1){
      $doWhat = mt_rand(1,4);
      switch($doWhat){
        /*Add one Day*/
        case 1: $newSolution = date('Y-m-d H:i:s',date(strtotime("+1 day", strtotime($oldSolution))));
                echo "1"."<br>";
                break;
        /*Minus one Day*/
        case 2: $newSolution = date('Y-m-d H:i:s',date(strtotime("-1 day", strtotime($oldSolution))));
                echo "2"."<br>";
                break;
        /*Add 30 minutes*/
        case 3: $newSolution = date('Y-m-d H:i:s', strtotime('+'.$duration.'minutes', strtotime($oldSolution)));
                echo "3"."<br>";
                break;
        /*Minus 30 minutes*/
        case 4: $newSolution = date('Y-m-d H:i:s', strtotime('-'.$duration.'minutes', strtotime($oldSolution)));
                echo "4"."<br>";
                break;
      }
      if(strtotime($startDateTime) >= strtotime($newSolution) && strtotime($newSolution) <= strtotime($endDateTime)){
        echo "valid neighbor";
        return $newSolution;
      }
    }
  }
  function random0_1(){
     $randomValue = (float) mt_rand() / (float) mt_getrandmax();
    //  echo "randomValue:".$randomValue."<br>";
    return $randomValue;
  }
  /*Annealing*/
  function anneal($startDateTime, $endDateTime, $duration, $users){
    $timeSlots = array();
    $temperature = 3;
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
            //  $newRandomStartTime = getNeighboringSolution($randomStartTime, $startDateTime, $endDateTime, $duration);
             $newRandomStartTime = getRandomTime($startDateTime, $endDateTime);
             $newRandomEndTime = date('Y-m-d H:i:s', strtotime($newRandomStartTime) + $duration * 60);
             $newRandomTimeslot = score($newRandomStartTime, $newRandomEndTime, $users);
             $ap = acceptance_probability($newRandomTimeslot->score, $randomTimeslot->score, $temperature);
             if ($ap > random0_1()){
                 $randomTimeslot = $newRandomTimeslot;
             }
             $i += 1;
         $temperature = $temperature * $coolRate;
       }
       array_push($timeSlots, $randomTimeslot);
    }
    return $timeSlots;
  }
    global $gFB;

  initiateBusy($userEmails, toGoogleFormat($startDateTime), toGoogleFormat($endDateTime));
  $gFB = getIBData();
  $timeSlots = array();
  $timeSlots = anneal($startDateTime,$endDateTime,  $duration, $users);

  // if (is_array($timeSlots) || is_object($timeSlots)){
  //   foreach($timeSlots as $time){
  //     echo "timestart: ".$time->datetimeStart."<br>";
  //     echo "timeend: ".$time->datetimeEnd."<br>";
  //     echo "score: ".$time->score."<br>";
  //     echo "Available: <br>";
  //
  //     foreach($time->available as $avail){
  //           echo $avail->name."<br>";
  //     }
  //     echo "Not Available: <br>";
  //     foreach($time->notAvailable as $notAvail){
  //       echo $notAvail->name."<br>";
  //     }
  //   }
  // }
  // else{
  //   echo "timeslots is not an array <br>";
  // }

  function date_sort($a, $b)
  {
      if ( $a->datetimeStart < $b->datetimeStart) return -1;
      if ( $a->datetimeStart > $b->datetimeStart ) return 1;
      return 0;
  }

  uasort($timeSlots, 'date_sort');
  // var_dump($timeSlots);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Organization Event</title>
    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <style>
    	body, th, td{
    		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    		font-size: 16px;
    	}
      .panel-header{
        text-align: center;
        font-size: 20px;
      }
      .panel-body{
        padding-left: 10px;
        font-weight: bold;
      }
      .participants{
        font-size: 16px;
      }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Organization</a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="index.php">View All Events</a></li>
          <li class="active"><a href="addNewEvent.php">Create New Event  <span class="sr-only">(current)</span></a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
<div class="container">
  <div class="row">
      <?php foreach($timeSlots as $time){ ?>
        <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail panel panel-default">
            <div class="panel-header">
              <strong class="text-success"><?php echo date('M-d-Y',strtotime($time->datetimeStart))." :: ".date('H:i',strtotime($time->datetimeStart))."-".date('H:i',strtotime($time->datetimeEnd))
              ?></strong>
            </div>
              <div class="panel-body">
                <h4><strong>Participants: <?php echo count($time->available)+count($time->notAvailable);?> </strong></h4>
                <hr/>
                  <h4><strong>Available: <?php echo count($time->available);?> </strong></h4>
                  <ul>
                    <?php foreach($time->available as $avail){ ?>
                      <li class="participants <?php if($avail->isPriority == 1){ echo "text-info";}?>"><?php echo $avail->name ?>
                      </li>
                    <?php } ?>
                  </ul>
                  <h4><strong>Not Available: <?php echo count($time->notAvailable);?>  </strong></h4>
                  <ul>
                    <?php foreach($time->notAvailable as $notAvail){ ?>
                      <li class="participants <?php if($avail->isPriority == 1){ echo "text-info";}?> "><?php echo $notAvail->name ?>
                      </li>
                    <?php } ?>
                  </ul>
              </div>
              <div class="bookEvent">
                  <a href="#" class="btn btn-primary btn-md btn-block">
                     Book Event
                  </a>
              </div>
          </div>
        </div>
      <?php } ?>
  </div>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src='../js/jquery-3.1.0.min.js'></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
