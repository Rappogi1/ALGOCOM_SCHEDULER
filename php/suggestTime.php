<?php

  $eventName = $_POST["eventName"];
  $startDate = $_POST["startDate"];
  $endDate = $_POST["endDate"];
  $startTime = $_POST["startTime"];
  $endTime = $_POST["endTime"];
  $duration = $_POST["duration"];

  /*Annealing*/
  function anneal(){
    $Temperature = 1.0
    $Temp_min = 0.001
    $alpha = 0.9

    $oldSolution = getNewSolution($startDate, $endDate, $startTime, $endTime, $duration);
    $oldSolutionScore = score($oldSolution);


   while $Temperature > $Temp_min{
       i = 1
       while i <= 50{
           $newSolution = getNewSolution($startDate, $endDate, $startTime, $endTime, $duration);
           $newSolutionScore = cost($oldSolutionScore)
           $ap = acceptance_probability($oldSolutionScore, $newSolutionScore, $Temperature)
           if $ap > random():
               $oldSolution = $newSolution
               $oldSolutionScore = $newSolutionScore
           $i += 1
       $Temperature = $Temperature*$alpha
     }
     return $oldSolution, $oldSolutionScore
  }

?>
