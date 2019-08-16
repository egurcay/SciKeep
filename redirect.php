<?php
//Establishing Connection with server by passing server_name, user_id and pass as a patameter
include("config.php");
session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}

header("Location: http://dijkstra.ug.bcc.bilkent.edu.tr/~erim.erdal/Project/reviewer.php");



?>