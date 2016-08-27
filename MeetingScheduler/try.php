<?php
    require("tryAll.php");
    $email = array('rafael.rodriguez.lozano@gmail.com','rappogi1@gmail.com','regina_balajadia@dlsu.edu.ph','john_martin_lucas@dlsu.edu.ph');
initiateBusy($email,'2016-08-20T09:25:00+08:00','2016-08-30T12:45:00+08:00');
    echo 'Is the email Busy? The answer is(Yes/No): '.checkIfBusy('rappogi1@gmail.com', '2016-08-25T05:30:00+08:00','2016-08-25T12:45:00+08:00');
?>