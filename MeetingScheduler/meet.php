<?php

$address = urlencode($_POST['https://www.googleapis.com/calendar/v3/freeBusy']);
$request = file_get_contents("http://www.google.com/calendar/feeds/{rafael.rodriguez.lozano@gmail.com}@group.calendar.google.com/public/basic?orderby=starttime&sortorder=ascending&futureevents=true&alt=json");

$json = json_decode($request, true);
?>