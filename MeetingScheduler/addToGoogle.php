<?php
    if(isset($_POST['submit'])){

            $data_missing = array();

            if(empty($_POST['email'])){
                $data_missing[] = "Email";
            } else{
                $email = trim($_POST['email']);
            }

            if(empty($_POST['title'])){
                $data_missing[] = "Title";
            } else{
                $title = trim($_POST['title']);
            }

            if(empty($_POST['location'])){
                $data_missing[] = "Location";
            } else{
                $location = trim($_POST['location']);
            }

            if(empty($_POST['description'])){
                $data_missing[] = "Description";
            } else{
                $description = trim($_POST['description']);
            }

            if(empty($_POST['sdt'])){
                $data_missing[] = "Start Time";
            } else{
                $sdt = trim($_POST['sdt']);
            }
        
            if(empty($_POST['edt'])){
                $data_missing[] = "End Time";
            } else{
                $edt = trim($_POST['edt']);
            }


    if(empty($data_missing)){

        //Here's the format
        addToGoogle($email, $title, $location, $description, $sdt, $edt);
        
    }else {

                echo 'You need to enter the following data<br />';

                foreach($data_missing as $missing){

                    echo "$missing<br />";

                }

            }
    }
?>