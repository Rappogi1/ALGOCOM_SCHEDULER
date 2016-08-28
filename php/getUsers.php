<?php
// Require our Event class and datetime utilities
require dirname(__FILE__) . '/userModel.php';


// Read and parse our events JSON file into an array of event data arrays.
$json = file_get_contents(dirname(__FILE__) . '/../json/user.json');
$input_arrays = json_decode($json, true);
$users = array();

  foreach ($input_arrays as $key1 => $value1) {
    $user = new User($input_arrays[$key1]["Name"],$input_arrays[$key1]["Email"], true);
    // echo $user->name."<br>";
    // echo $user->email."<br>";

    array_push($users, $user);
  }

  // Send JSON to the client.
  //echo json_encode($users);
?>
