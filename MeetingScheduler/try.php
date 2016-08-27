
<?php

    function getIfBusy(){
    if(isset($_POST['submit'])){
            
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
            }
            
        $sdt = $startdate."T".$starttime.":00+08:00";
        $edt = $enddate."T".$endtime.":00+08:00";
            $yes;
        
    if(empty($data_missing)){
    require('meeting.php');
    $pos = [];
    $cnt;
    $email = [];
    $start = [];
    $end = [];

        $emailmain2='rappogi1@gmail.com';
    if($emailmain=='rappogi1@gmail.com'){
        $emailmain='ita91lgk4o9651eaaphjk025kg@group.calendar.google.com';
    }
        
    $gFB = getFreeBusy($emailmain,$sdt,$edt);

    $pos = $gFB[0];
    $cnt = $gFB[1];
    $email = $gFB[2];
    $start = $gFB[3];
    $end = $gFB[4];


    echo 'The Emails of ('.$emailmain2.'):'.'<br>';
    foreach($email as $em){
        echo $em.'<br>';
    }
    echo $em.'<br><br>';

    echo "Is The Email (".$emailmain2.") Busy? (If there are date and time it's busy)"."<br>";
    
    echo $email[0].'<br>';    
        
    $count = 0;
        if($cnt!=0){
            $yes="Yes";
    while($count<count($start)){
        //echo $email[$p].'<br>';
        echo $start[$count].'<br>';
        echo $end[$count].'<br>';
        $count++;
        
    }
    }else{
            $yes="No";
        }
    }else {
                
                echo 'You need to enter the following data<br />';
                
                foreach($data_missing as $missing){
                    
                    echo "$missing<br />";
                    
                }
                
            }
    }
        return $yes;
    }

    echo '<br> Is the email Busy? The answer is(Yes/No): '.getIfBusy();
?>