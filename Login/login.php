<?php
session_start();

$user = dataFilter($_POST['uname']);
$pass = $_POST['pass'];
$category = dataFilter($_POST['category']);

require '../db.php'; // Adjust path to your database connection script

if ($category == 1) {
    $sql = "SELECT * FROM farmer WHERE fusername='$user'";
} else {
    $sql = "SELECT * FROM buyer WHERE busername='$user'";
}

$result = mysqli_query($conn, $sql);
$num_rows = mysqli_num_rows($result);

if ($num_rows == 0) {
    $_SESSION['message'] = "Invalid User Credentials!";
    header("location: error.php");
    exit;
} else {
    $User = $result->fetch_assoc();

    if ($category == 1 && password_verify($pass, $User['fpassword'])) {
        $_SESSION['id'] = $User['fid'];
        $_SESSION['Hash'] = $User['fhash'];
        $_SESSION['Password'] = $User['fpassword'];
        $_SESSION['Email'] = $User['femail'];
        $_SESSION['Name'] = $User['fname'];
        $_SESSION['Username'] = $User['fusername'];
        $_SESSION['Mobile'] = $User['fmobile'];
        $_SESSION['Addr'] = $User['faddress'];
        $_SESSION['Active'] = $User['factive'];
        $_SESSION['logged_in'] = true;
        $_SESSION['Category'] = 1;
        $_SESSION['Rating'] = 0;

        if ($_SESSION['picStatus'] == 0) {
            $_SESSION['picId'] = 0;
            $_SESSION['picName'] = "profile0.png";
        } else {
            $_SESSION['picId'] = $_SESSION['id'];
            $_SESSION['picName'] = "profile".$_SESSION['picId'].".".$_SESSION['picExt'];
        }

        header("location: profile.php");
        exit;
    } elseif ($category == 0 && password_verify($pass, $User['bpassword'])) {
        $_SESSION['id'] = $User['bid'];
        $_SESSION['Hash'] = $User['bhash'];
        $_SESSION['Password'] = $User['bpassword'];
        $_SESSION['Email'] = $User['bemail'];
        $_SESSION['Name'] = $User['bname'];
        $_SESSION['Username'] = $User['busername'];
        $_SESSION['Mobile'] = $User['bmobile'];
        $_SESSION['Addr'] = $User['baddress'];
        $_SESSION['Active'] = $User['bactive'];
        $_SESSION['logged_in'] = true;
        $_SESSION['Category'] = 0;

        header("location: profile.php");
        exit;
    } else {
        $_SESSION['message'] = "Invalid User Credentials!";
        header("location: error.php");
        exit;
    }
}

function dataFilter($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

