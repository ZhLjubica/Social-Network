<?php

//declaring variables to prevent errors
$fname = ""; //first name
$lname = ""; //last name
$em = ""; //email
$em2 = ""; //email 2
$password = ""; //password
$password2 = ""; // password 2
$date = ""; // sign up date
$error_array = array(); // holds error messsages

if (isset($_POST['register_button'])) {
    //First name
    $fname = strip_tags($_POST['reg_fname']);
    //strip tags remove html elements
    $fname = str_replace(' ', '', $fname);
    //remove spaces
    $fname = ucfirst(strtolower($fname));
    //all letters to lowercase, exept the first one
    $_SESSION['reg_fname'] = $fname;
    //stores first name into session variable


    //Last name
    $lname = strip_tags($_POST['reg_lname']);
    //strip tags remove html elements
    $lname = str_replace(' ', '', $lname);
    //remove spaces
    $lname = ucfirst(strtolower($lname));
    //all letters to lowercase, exept the first one
    $_SESSION['reg_lname'] = $lname;
    //stores last name into session variable

    // Email
    $em = strip_tags($_POST['reg_email']);
    //strip tags remove html elements
    $em = str_replace(' ', '', $em);
    //remove spaces
    // $em = ucfirst(strtolower($em));
    //all letters to lowercase, exept the first one
    $_SESSION['reg_email'] = $em;
    //stores email into session variable


    //Email2
    $em2 = strip_tags($_POST['reg_email2']);
    //strip tags remove html elements
    $em2 = str_replace(' ', '', $em2);
    //remove spaces
    // $em2 = ucfirst(strtolower($em2));
    //all letters to lowercase, exept the first one
    $_SESSION['reg_email2'] = $em2; 
    //stores email2 into session variable

    //Password
    $password = strip_tags($_POST['reg_password']);
    //Password2
    $password2 = strip_tags($_POST['reg_password2']);
    //strip tags remove html elements

    $date = date("Y-m-d"); //current date

    if ($em == $em2) {
        //check if email is in valid format
        if (filter_var($em, FILTER_VALIDATE_EMAIL)) {
            $em = filter_var($em, FILTER_VALIDATE_EMAIL);
            //check if email already exist
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");
            //count number of rows returned
            $num_rows = mysqli_num_rows($e_check);
            if ($num_rows>0) {
                array_push($error_array, "Email already in use!<br>");
            }
            
        }else{
            array_push($error_array, "Invalid email format<br>");
        }
    }
    else{
        array_push($error_array, "Emails don't match<br>");
    }
    if(strlen($fname)>25 || strlen($fname)<2){
        array_push($error_array, "Your first name must be between 2 and 25 characters!<br>");
    }
    if(strlen($lname)>25 || strlen($lname)<2){
        array_push($error_array, "Your last name must be between 2 and 25 characters!<br>");
    }
    if ($password!=$password2) {
        array_push($error_array, "Your password do not match!<br>");
    }else{
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Your password can only contain english characters or numbers!<br>");
        }
    }

    if (strlen($password>30 || strlen($password)<5)) {
        array_push($error_array, "Your password must be between 5 or 30 characters!<br>");
    }

    if (empty($error_array)) {
        $password = md5($password);//encrypt password before sending to database
        //generate username by concatenating first name and last name
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        $i = 0;
        //if username exist add number to username
        while(mysqli_num_rows($check_username_query) !=0){
            $i++; //add 1 to i
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
        }
        //profile picture assigment
        $rand = rand(1,2); //random number between 1 or 2
        if ($rand = 1) {
            $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
        }else{
            $profile_pic = "assets/images/profile_pics/defaults/head_nephritis.png";
        } 
        $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");
        
        array_push($error_array, "<span style='color:#14C000'>You're all set! Go ahead and login!</span><br>");

        //clear session variables
        $_SESSION['reg_fname'] ="";
        $_SESSION['reg_lname'] ="";
        $_SESSION['reg_email'] ="";
        $_SESSION['reg_email2'] ="";

    }
}

?>