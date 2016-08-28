<?php
require_once('meeting.php');
    $gFB;
    $emailIB;
    $busyIB;
    $ilanIB;

    function getIBData(){
        global $gFB;
        return $gFB;
    }

    function setIBData($gFB1){
        global $gFB;
        global $emailIB;
        global $busyIB;
        global $ilanIB;
        $gFB = $gFB1;
        $emailIB = $gFB[0];
        $busyIB = $gFB[1];
        $ilanIB = $gFB[2];
    }

    function checkIfBusy($emailmain, $sdt, $edt){
        global $emailIB;
        global $busyIB;
        global $ilanIB;
        $boolD = 0;
        $boolT = 0;
        $count = 0;
        $yes = 1;
        if($emailmain=='rappogi1@gmail.com'){
        $emailmain='ita91lgk4o9651eaaphjk025kg@group.calendar.google.com';
    }else if($emailmain=='regina_balajadia@dlsu.edu.ph'){
        $emailmain='dlsu.edu.ph_9u089b01752o0afi2e5ign0rlg@group.calendar.google.com';
    }else if($emailmain=='john_martin_lucas@dlsu.edu.ph'){
        $emailmain='dlsu.edu.ph_h0ghs54ph8kbuc2tiohu1gbg7k@group.calendar.google.com';
    }
        foreach($emailIB as $em){
            //if($em==$emailmain){
                //echo $em.'<br>';
                $cnt=0;
                while($cnt<$ilanIB[$count]){
                    //$sdt
                        $datetimesdt = preg_split("/T/", $sdt);
                        $datetime1sdt = explode("+", $datetimesdt[1]);
                        $datetimesdt = $datetimesdt[0].' '.$datetime1sdt[0];
                        $format = 'Y-m-d H:i:s';
                        $datesdtsdt = DateTime::createFromFormat($format, $datetimesdt);
                    //$edt
                        $datetimeedt = preg_split("/T/", $edt);
                        $datetime1edt = explode("+", $datetimeedt[1]);
                        $datetimeedt = $datetimeedt[0].' '.$datetime1edt[0];
                        $format = 'Y-m-d H:i:s';
                        $datesdtedt = DateTime::createFromFormat($format, $datetimeedt);
                    $boolD = 0;
                    $boolDo = 0;
                    $boolCount = 0;
                    foreach($busyIB[$count] as $bTemp){
                        //echo $bTemp[$cnt].'<br>';

                        $datetime = preg_split("/T/", $bTemp[$cnt]);
                        $datetime1 = explode("+", $datetime[1]);
                        $datetime = $datetime[0].' '.$datetime1[0];
                        $format = 'Y-m-d H:i:s';
                        $date = DateTime::createFromFormat($format, $datetime);
                        //echo $datesdtsdt->format('Y-m-d H:i:s') . "\n";
                        if($boolCount==0){
                            if(new DateTime($datesdtsdt->format('Y-m-d H:i:s')) >= new DateTime($date->format('Y-m-d H:i:s'))){
                                //echo 'IN<br>';
                                $boolD = 1;
                            }

                            if(new DateTime($datesdtsdt->format('Y-m-d H:i:s')) <= new DateTime($date->format('Y-m-d H:i:s'))){
                                //echo 'Out<br>';
                                $boolDo = 1;
                            }
                        }else if($boolCount==1){
                            if(new DateTime($datesdtedt->format('Y-m-d H:i:s')) <=
                               new DateTime($date->format('Y-m-d H:i:s'))){
                                //echo 'IN<br>';
                            }else{
                                $boolD = 0;
                            }

                            if(new DateTime($datesdtedt->format('Y-m-d H:i:s')) >= new DateTime($date->format('Y-m-d H:i:s'))){
                                //echo 'Out<br>';
                            }else{

                                $boolDo = 0;
                            }
                        }
                        $boolCount++;

                        //echo $date->format('Y-m-d H:i:s').'<br>';
                    }
                    if($boolD==1||$boolDo==1){
                        if($em==$emailmain){
                        $yes = 0;
                        }
                        //echo 'YES<br>';
                    }
                    $cnt++;
                }
                $count++;
            //}
        }
        return $yes;
    }

    function initiateBusy($emailmain, $sdt, $edt){
        $email = [];
        $busy = [];

        $emailmain2=$emailmain;
        
        $count=0;
        while($count<count($emailmain)){
            if($emailmain[$count]=='rappogi1@gmail.com'){
                $emailmain[$count]='ita91lgk4o9651eaaphjk025kg@group.calendar.google.com';
            }else if($emailmain[$count]=='regina_balajadia@dlsu.edu.ph'){
                $emailmain[$count]='dlsu.edu.ph_9u089b01752o0afi2e5ign0rlg@group.calendar.google.com';
            }else if($emailmain[$count]=='john_martin_lucas@dlsu.edu.ph'){
                $emailmain[$count]='dlsu.edu.ph_h0ghs54ph8kbuc2tiohu1gbg7k@group.calendar.google.com';
            }
            //echo $emailmain[$count]."<br>";
            $count++;
        }

        // foreach($emailmain as $e){
        //     if($e =='rappogi1@gmail.com'){
        //         $e ='ita91lgk4o9651eaaphjk025kg@group.calendar.google.com';
        //     }else if($e =='regina_balajadia@dlsu.edu.ph'){
        //         $e ='dlsu.edu.ph_9u089b01752o0afi2e5ign0rlg@group.calendar.google.com';
        //     }else if($e =='john_martin_lucas@dlsu.edu.ph'){
        //         $e ='dlsu.edu.ph_h0ghs54ph8kbuc2tiohu1gbg7k@group.calendar.google.com';
        //     }
        // }
        global $gFB;

        $gFB = getInitBusy($emailmain,$sdt,$edt);
        global $emailIB;
        global $busyIB;
        global $ilanIB;
        $emailIB = $gFB[0];
        $busyIB = $gFB[1];
        $ilanIB = $gFB[2];
        //return $gFB;
    }


    function getIfBusy($emailmain, $sdt, $edt){
    /*if(isset($_POST['submit'])){

            $data_missing = array();

            if(empty($_POST['email'])){
                $data_missing[] = "Email";
            } else{
                $emailmain = trim($_POST['email']);
            }

            if(empty($_POST['startdate'])){
                $data_missing[] = "Start Date";
            } else{
                $startdate = trim($_POST['startdate']);
            }

            if(empty($_POST['starttime'])){
                $data_missing[] = "Start Time";
            } else{
                $starttime = trim($_POST['starttime']);
            }

            if(empty($_POST['enddate'])){
                $data_missing[] = "End Date";
            } else{
                $enddate = trim($_POST['enddate']);
            }

            if(empty($_POST['endtime'])){
                $data_missing[] = "End Time";
            } else{
                $endtime = trim($_POST['endtime']);
            }*/

        //$sdt = $startdate."T".$starttime.":00+08:00";
        //$edt = $enddate."T".$endtime.":00+08:00";
            $yes;

    //if(empty($data_missing)){

    $pos = [];
    $cnt;
    $email = [];
    $start = [];
    $end = [];

        $emailmain2=$emailmain;
    if($emailmain=='rappogi1@gmail.com'){
        $emailmain='ita91lgk4o9651eaaphjk025kg@group.calendar.google.com';
    }else if($emailmain=='regina_balajadia@dlsu.edu.ph'){
        $emailmain='dlsu.edu.ph_9u089b01752o0afi2e5ign0rlg@group.calendar.google.com';
    }else if($emailmain=='john_martin_lucas@dlsu.edu.ph'){
        $emailmain='dlsu.edu.ph_h0ghs54ph8kbuc2tiohu1gbg7k@group.calendar.google.com';
    }

    $gFB = getFreeBusy($emailmain,$sdt,$edt);

    $pos = $gFB[0];
    $cnt = $gFB[1];
    $email = $gFB[2];
    $start = $gFB[3];
    $end = $gFB[4];


    //echo 'The Email of ('.$emailmain2.'):'.'<br>';
    foreach($email as $em){
        //echo $em.'<br>';
    }
    //echo $em.'<br><br>';

    //echo "Is The Email of (".$emailmain2.") Busy? (If there are date and time it's busy)"."<br>";

    //echo $email[0].'<br>';

    $count = 0;
        if($cnt!=0){
            $yes=0;
    while($count<count($start)){
        //echo $email[$p].'<br>';
        //echo $start[$count].'<br>';
        //echo $end[$count].'<br>';
        $count++;

    }
    }else{
            $yes=1;
        }
    /*}else {

                echo 'You need to enter the following data<br />';

                foreach($data_missing as $missing){

                    echo "$missing<br />";

                }

            }
    }*/
        return $yes;
    //}
    }

    //echo '<br> Is the email Busy? The answer is(Yes/No): '.getIfBusy();
?>
