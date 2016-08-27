<?php
  


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
  echo $duration.'<br/>';

  $dayStart = str_replace('-', '/', $startDate);
  $dayEnd = str_replace('-', '/', $endDate);

  $startDateTime = date('Y-m-d H:i:s', strtotime("$dayStart $startTime"));
  $endDateTime = date('Y-m-d H:i:s', strtotime("$dayEnd $endTime"));
  $endDateTime = date('Y-m-d H:i:s', strtotime($endDateTime) - $duration * 60);

  /*Acceptance Function*/
  function accept($newSolution, $oldSolution, $temperature){
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
    // $dayStart = str_replace('-', '/', $dayStart);
    // $dayEnd = str_replace('-', '/', $dayEnd);
    // $stampDayStart = strtotime($dayStart);
    // $stampDayEnd = strtotime($dayEnd);
    // $stampTimeStart = strtotime($timeStart);
    // $stampTimeEnd = strtotime($timeEnd);
    // $stampDuration = strtotime($duration);
    //
    // $startDateTime = date('Y-m-d H:i:s', strtotime("$dayStart $timeStart"));
    // $endDateTime = date('Y-m-d H:i:s', strtotime("$dayEnd $timeEnd"));
    // $endDateTime = date('Y-m-d H:i:s', strtotime($endDateTime) - $duration * 60);

    // echo "dateTime:".$startDateTime."<br>";
    // echo "dateTime:".$endDateTime."<br>";

    //Get a random day date from day scope
    $dayRand = mt_rand(strtotime($startDateTime), strtotime($endDateTime));

    // $timeStartStamp = strtotime($timeStart, $dayRand);
    // $timeEndStamp = strtotime($timeEnd, $dayRand);

    $timeSlot = date('Y-m-d H:i:s', $dayRand);

    //echo "Timeslot".$timeSlot."<br>";
    $roundedTimeSlot = roundTime($timeSlot);

    //echo "roundedTimeSlot".$roundedTimeSlot."<br>";
    return $roundedTimeSlot;
}

  require dirname(__FILE__) . '/../MeetingScheduler/tryAll.php';

  /*this is rap's function*/
  function score($timeStart, $timeEnd){
    //"2016-08-23T09:30:00+08:00"
      session_start();

    $gmt = "+08:00";
    $dat = date('Y-m-d', strtotime($timeStart));
    $tme = date('H:i:s',strtotime($timeStart));

    $datEnd = date('Y-m-d', strtotime($timeEnd));
    $tmeEnd = date('H:i:s',strtotime($timeEnd));

    $timeStartGoogle = $dat."T".$tme.$gmt;
    $timeEndGoogle = $datEnd."T".$tmeEnd.$gmt;

    $users = $_SESSION['users'];
      
    $score=0;
    foreach($users as $user){
      $isBusy = getIfBusy($users, $timeStartGoogle, $timeEndGoogle);
      if($isBusy == 1){
        $score = $score + 3;
      }
    }
    echo "Score:".$score;
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

   $oldSolution = getRandomTime($startDateTime, $endDateTime);
   $endTimeOld = date('Y-m-d H:i:s', strtotime($oldSolution) + $duration * 60);
   score($oldSolution, $endTimeOld);
   echo "Old Solution:".$oldSolution."<br>";
   echo "Neighbor Solution:".getNeighboringSolution($oldSolution)."<br>";

  // /*Annealing*/
  // function anneal(){
  //   $temperature = 1000;
  //   $temp_min = 1;
  //   $coolRate = 0.9;
  //
  //   $oldSolution = getRandomTime($startDate, $endDate, $startTime, $endTime, $duration);
  //   $endTimeOld = date('Y-m-d H:i:s', strtotime($oldSolution) + $duration * 60);
  //   $oldSolutionScore = score($oldSolution, $endTimeOld);
  //
  //  while ($temperature > $temp_min) {
  //      i = 1
  //      while (i <= 10) {
  //          $newSolution = getNeighboring($startDate, $endDate, $startTime, $endTime, $duration);
  //          $newSolutionScore = score($newSolution);
  //          $ap = acceptance_probability($newSolutionScore, $oldSolutionScore, $temperature);
  //          if ($ap > random()){
  //              $oldSolution = $newSolution;
  //              $oldSolutionScore = $newSolutionScore;
  //          }
  //          $i += 1;
  //      $temperature = $temperature * $coolRate;
  //    }
  //    return $oldSolution;
  // }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Organization Event</title>

    <style>
    	body, th, td{
    		/*margin: 40px 10px;
    		padding: 0; */
    		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    		font-size: 16px;
    	}
      #loading {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
      }
    	#calendar {
    		/*max-width: 900px;*/
    		margin: 0 auto;
    	}

      .glyphicon:before {
       visibility: visible;
      }
      .glyphicon.glyphicon-star-empty:checked:before {
         content: "\e006";
      }
      input[type=checkbox].glyphicon{
          visibility: hidden;

      }
      div > .best{
        background-color: green;
        color: #fff;
      }
      .card-header{
        text-align: center;
      }
      .card-body{
        padding-left: 10px;
      }
      .participants{
        font-size: 16px;
      }
    </style>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
    <link href="../css/bootstrap.min.css" rel="stylesheet">

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

<div class="container">
  <div class="row">
      <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail">
            <div class="card-header best">
              <h3>Timeslot</h3>
            </div>
              <div class="card-body">
                  <h4><strong>Date:</strong> August 17, 2016</h4>
                  <h4><strong>Time:</strong> 15:30 - 18:30</h4>
                  <h4><strong>Available:</strong></h4>
                  <ul>
                    <li class="participants">Regina Claire Balajadia</li>
                    <li class="participants">John Martin Lucas</li>
                  </ul>
                  <h4><strong>Not Available: </strong></h4>
                  <ul>
                    <li class="participants">Rafael Lozano</li>
                  </ul>
              </div>
              <div class="bookEvent">
                  <a href="#" class="btn btn-primary btn-md btn-block">
                     Book Event
                  </a>
              </div>
          </div>
      </div>
  </div>
</div>
</body>
</html>
