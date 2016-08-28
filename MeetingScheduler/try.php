<?php
    require("tryAll.php");
    $gFB; // Initiate this first

    function init(){
        global $gFB;
        $email = array('rafael.rodriguez.lozano@gmail.com','rappogi1@gmail.com','regina_balajadia@dlsu.edu.ph','john_martin_lucas@dlsu.edu.ph');
        initiateBusy($email,'2016-08-20T09:25:00+08:00','2016-08-30T12:45:00+08:00');
        $gFB = getIBData();
    }
    
    function run(){
        global $gFB;
        setIBData($gFB);
        echo 'Is the email Busy? The answer is(Yes/No): '.checkIfBusy('rappogi1@gmail.com', '2016-08-25T05:30:00+08:00','2016-08-25T12:45:00+08:00').'<br>';
        
        
    }

    function add(){
        $email = array(
            array('email' => 'rafael.rodriguez.lozano@gmail.com'),
            //array('email' => 'regina_balajadia@dlsu.edu.ph'),
            array('email' => 'rappogi1@gmail.com'),
            //array('email' => 'john_martin_lucas@dlsu.edu.ph'),
        );
        
        $title = "Try and Try";
        $location = "Gokongwei";
        $description = "Sa likod";
        $sdt = "2016-08-30T09:25:00+08:00";
        $edt = "2016-08-30T09:45:00+08:00";
        
        //Here's the format
        addToGoogle($email, $title, $location, $description, $sdt, $edt);
    }

    init();
    run();
    add();
?>