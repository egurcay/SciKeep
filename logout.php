<?php
session_start();
//unsetler gelcek cCc BİR GECE ANSIZIN 82 MUSUL
unset($_SESSION['uname']);
unset($_SESSION['psw']);
unset($_SESSION['login']);
unset($_SESSION['member']);
session_destroy();
header("Location: login.php");
?>