<?php

//Establishing Connection with server by passing server_name, user_id and pass as a patameter
include("config.php");
session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}
else{
    if($_SESSION['member']!= 'SUBSCRIBER'){
    header("location:homepage.php");
    die;
}
}
$uname = $_SESSION['uname'];
$member = $_SESSION['member'];

$query = mysqli_query($conn, "SELECT firstname, lastname from  member where ID = '$uname'");
$profile_name = $query->fetch_array(MYSQLI_BOTH);

$query = mysqli_query($conn, "SELECT email from  member where ID = '$uname'");
$email = $query->fetch_array(MYSQLI_BOTH);

$query = mysqli_query($conn, "SELECT count(journal_name) as subscribed_count 
from (journal join (mem_journal join member))
where journal_ISBN = mem_ISBN and mem_ID = ID and ID = '$uname';");
$subscribed_count = $query->fetch_array(MYSQLI_BOTH);


$query = mysqli_query($conn, "SELECT sum(cost) as total_cost, avg(cost) as avg_fee
from (journal join (mem_journal join member))
where journal_ISBN = mem_ISBN and mem_ID = ID and ID = '$uname';");
$total_cost = $query->fetch_array(MYSQLI_BOTH);

$journals = mysqli_query($conn, "SELECT journal_name, place_of_publication, cost, journal_ISBN
from (journal join (mem_journal join member))
where journal_ISBN = mem_ISBN and mem_ID = ID and ID = '$uname';");

$conferences = mysqli_query($conn, "select conf_name, location, capacity, conf_ISBN
from (conference join (attends join member))
where conf_ISBN = attend_conf_ISBN and attend_ID = ID and ID = '$uname';");

$query = mysqli_query($conn, "select count(conf_name) as attend_count
from (conference join (attends join member))
where conf_ISBN = attend_conf_ISBN and attend_ID = ID and ID = '$uname';");
$attend_count = $query->fetch_array(MYSQLI_BOTH);


if(isset($_POST['submit'])){
  $selected_val = $_POST['sort'];
  switch ($selected_val) {
        case 'Option1':
echo "anan";
          $journals = mysqli_query($conn, "select journal_name, place_of_publication, cost, journal_ISBN
from (journal join (mem_journal join member))
where journal_ISBN = mem_ISBN and mem_ID = ID and ID = '$uname'
order by journal_name asc;");
          break;

        case 'Option2':
          $journals = mysqli_query($conn, "select journal_name, place_of_publication, cost, journal_ISBN
from (journal join (mem_journal join member))
where journal_ISBN = mem_ISBN and mem_ID = ID and ID = '$uname'
order by journal_name desc;");
            break;

        case 'Option3':
            $journals = mysqli_query($conn, "select journal_name, place_of_publication, cost, journal_ISBN
from (journal join (mem_journal join member))
where journal_ISBN = mem_ISBN and mem_ID = ID and ID = '$uname'
order by place_of_publication asc;");
            break;

        case 'journals':
            $papers = mysqli_query($conn, "select journal_name, place_of_publication, cost, journal_ISBN
from (journal join (mem_journal join member))
where journal_ISBN = mem_ISBN and mem_ID = ID and ID = '$uname'
order by cost asc;");
            break;

        default:
            $journals = mysqli_query($conn, "SELECT journal_name 
from (journal join (mem_journal join member))
where journal_ISBN = mem_ISBN and mem_ID = ID and ID = '$uname';");
            break;
    }
}

if(isset($_POST['submit_two'])){
  $selected_val = $_POST['sort'];
  switch ($selected_val) {
        case 'Option1':
echo "anan";
          $conferences = mysqli_query($conn, "select conf_name, location, capacity, conf_ISBN
from (conference join (attends join member))
where conf_ISBN = attend_conf_ISBN and attend_ID = ID and ID = '$uname'
order by conf_name asc;");
          break;

        case 'Option2':
          $conferences = mysqli_query($conn, "select conf_name, location, capacity, conf_ISBN
from (conference join (attends join member))
where conf_ISBN = attend_conf_ISBN and attend_ID = ID and ID = '$uname'
order by conf_name desc;");
            break;

        case 'Option3':
            $conferences = mysqli_query($conn, "select conf_name, location, capacity, conf_ISBN
from (conference join (attends join member))
where conf_ISBN = attend_conf_ISBN and attend_ID = ID and ID = '$uname'
order by location asc;");
            break;

        case 'Option4':
            $conferences = mysqli_query($conn, "select conf_name, location, capacity, conf_ISBN
from (conference join (attends join member))
where conf_ISBN = attend_conf_ISBN and attend_ID = ID and ID = '$uname'
order by capacity asc;");
            break;

        default:
            $conferences = mysqli_query($conn, "select conf_name, location, capacity, conf_ISBN
from (conference join (attends join member))
where conf_ISBN = attend_conf_ISBN and attend_ID = ID and ID = '$uname';");
            break;
    }
}


?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>ScienceKeep - Subscription Details</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/main.css">

    <style type="text/css" media="screen">
        .s-styles {
            background: #f2f2f2;
            padding-top: 12rem;
            padding-bottom: 12rem;
        }

        .s-styles .section-intro h1 {
            margin-top: 0;
        }
     </style>

    <!-- script
    ================================================== -->
    <script src="js/modernizr.js"></script>
    <script src="js/pace.min.js"></script>

    <!-- favicons
    ================================================== -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

</head>

<body id="top">

    <!-- pageheader
    ================================================== -->
    <div class="s-pageheader">

        <header class="header">
            <div class="header__content row">

                <div class="header__logo">
                    <a class="logo" href="homepage.php">
                        <img src="images/logo2.png" alt="Homepage">
                    </a>
                </div> <!-- end header__logo -->


                <a class="header__search-trigger" href="#0"></a>

                <div class="header__search">

                    <form role="search" method="get" class="header__search-form" action="searchresults.php">
                        <label>
                            <span class="hide-content">Search Paper By</span>
                             <label>
                                <form action="">
                            <input type="radio" name="searchBy" value="title" checked="on"> Title
                            <input type="radio" name="searchBy" value="author"> Author
                            <input type="radio" name="searchBy" value="topic"> Topic
                             </form>
                        </label>
                            <input type="search" class="search-field" placeholder="Type Keywords" value="" name="keyword" title="Search for:" >
                        </label>
                    </form>

                    <a href="#0" title="Close Search" class="header__overlay-close">Close</a>

                </div>  <!-- end header__search -->


               <a class="header__toggle-menu" href="#0" title="Menu"><span>Menu</span></a>

                <nav class="header__nav-wrap">

                    <h2 class="header__nav-heading h6">Site Navigation</h2>

                    <ul class="header__nav">
                        <li><a href="homepage.php" title="">Home</a></li>
                        <li class="has-children">
                            <a href="" title="">Publications</a>
                            <ul class="sub-menu">
                            <li ><a href="conferences.php">List All Conferences</a></li>
                            <li><a href="journals.php">List All Journals</a></li>
                            </ul>
                        </li>
                        <li class="has-children">
                            <a href="" title="">Topics</a>
                            <ul class="sub-menu">
                              <li><a href="searchresults.php?searchBy=topic&keyword=Embedded Sysytems">Embedded Sysytems</a></li>
                              <li><a href="searchresults.php?searchBy=topic&keyword=Computer Architecture">Computer Architecture</a></li>
                              <li><a href="searchresults.php?searchBy=topic&keyword=Computational Biology">Computational Biology</a></li>
                              <li><a href="searchresults.php?searchBy=topic&keyword=Management Information Systems">Management Information Systems</a></li>
                              <li><a href="searchresults.php?searchBy=topic&keyword=Computers and Society">Computers and Society</a></li>
                              <li><a href="searchresults.php?searchBy=topic&keyword=">Browse All Topics</a></li>
                            </ul>
                        </li>

                        <li><a href="authors.php" title="">Authors</a></li>

                        <li class="has-children">
                            <a href="#0" title="">Profile</a>
                            <ul class="sub-menu">
                            <li><a href="profile.php" title="">My Profile</a></li>
                            <li><a href="editprofile.php">Edit Profile</a></li>
                            <?php
                        if($member == "AUTHOR")
                        {
                         echo "<li><a href=\"additionalinfo.php\" title=\"\">Additional Information</a></li>";

                        }
                        if($member == "SUBSCRIBER")
                        {
                         echo "<li><a href=\"subscriber.php\" title=\"\">Subscription Details</a></li>";

                        }

                         ?>
                            </ul>
                        </li>
                        <?php
                        if($member == "REVIEWER")
                        {
                         echo "<li><a href=\"reviewer.php\" title=\"\">Review Papers</a></li>";

                        }
                        else if($member == "EDITOR"){
                            echo "<li class=current><a href=\"editor.php\" title=\"\">Editor's Page</a></li>";
                        }

                         ?>
                        <li><a href="contact.php" title="">Contact</a></li>

                        <li><a href="logout.php" title="">Logout</a></li>

                    </ul> <!-- end header__nav -->
                    <a href="#0" title="Close Menu" class="header__overlay-close close-mobile-menu">Close</a>

                </nav> <!-- end header__nav-wrap -->

            </div> <!-- header-content -->
        </header> <!-- header -->

    </div> <!-- end s-pageheader -->



            <!-- styles
            ================================================== -->
            <section id="styles" class="s-styles">

                <div class="row narrow section-intro add-bottom text-center" >

                    <div class="col-twelve tab-full">

                        <h2><?php echo $profile_name[0]." ".$profile_name[1]?></h2>
        <p><a href="#"><img width="120" height="120" class="pull" alt="sample-image" src="images/sample-image.jpg"></a></p>
        <p><a href="#">E-mail</a><br><a href="#"><?php echo $email[0]?></a></p>
                        <ul class="stats-tabs">
                            <li><a href="#"><?php echo $subscribed_count[0]?>  <em>Subscribed Journals</em></a></li>
                            <li><a href="#"><?php echo $attend_count[0]?>  <em>Attended Conferences</em></a></li>
                            <li><a href="#"><?php echo $total_cost[0]?>  <em>Total Fee</em></a></li>
                            <li><a href="#"><?php echo $total_cost[1]?>  <em>Average Fee</em></a></li>
                            
                            </ul>

                    </div>
                    <p><form action="" method="post">
                            <div>
                                <h6>Subscribed Journals</h6>
                                <label for="sampleRecipientInput">Sort By</label>
                                <div class="cl-custom-select">
                                    <select class="full-width" id="sampleRecipientInput" name = "sort">
                                        <option value="Option1">A - Z</option>
                                        <option value="Option2">Z - A</option>
                                        <option value="Option3">Location</option>
                                        <option value="Option4">Cost</option>
                                    </select>
                                </div>

                            </div>
                        <input class="btn--primary btn--medium" type="submit" value="sort" name="submit">
                        </form>
                    </p>

                </div>

            
        </p>

        </div>

    

        </div>

        <div class="row add-bottom">

            <div class="table-responsive">

                <table>
                        <thead>
                        <tr>
                            <th>Journal Name</th>
                            <th>Place</th>
                            <th>Cost</th>
                            <th>ISBN</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while($row = mysqli_fetch_assoc($journals)){
                             echo "<tr>";
                        echo " <td><a href=\"#\"> {$row["journal_name"]} </a></td>";
                        echo " <td>  {$row["place_of_publication"]} </td>";
                        echo " <td>  {$row["cost"]} </td>";
                        echo " <td>  {$row["journal_ISBN"]} </td>";
                        echo " </tr>";
                        

                        }


                        ?>
                        </tbody>
                </table>

            </div>

        </div>
        </p>
        </div> <!-- end row -->



        <section id="styles" class="s-styles">

                <div class="row narrow section-intro add-bottom text-center" >
                    <p><form action="" method="post">
                            <div>
                                <h6>Attended Conferences</h6>
                                <label for="sampleRecipientInput">Sort By</label>
                                <div class="cl-custom-select">
                                    <select class="full-width" id="sampleRecipientInput" name = "sort">
                                        <option value="Option1">A - Z</option>
                                        <option value="Option2">Z - A</option>
                                        <option value="Option3">Location</option>
                                        <option value="Option4">Capacity</option>
                                    </select>
                                </div>

                            </div>
                        <input class="btn--primary btn--medium" type="submit" value="sort" name="submit_two">
                        </form>
                    </p>

                </div>

            
        </p>

        </div>

    

        </div>

        <div class="row add-bottom">

            <div class="table-responsive">

                <table>
                        <thead>
                        <tr>
                            <th>Conference Name</th>
                            <th>Place</th>
                            <th>Capacity</th>
                            <th>ISBN</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while($row = mysqli_fetch_assoc($conferences)){
                       echo "<tr>";
                        echo " <td><a href=\"#\"> {$row["conf_name"]} </a></td>";
                        echo " <td>  {$row["location"]} </td>";
                        echo " <td>  {$row["capacity"]} </td>";
                        echo " <td>  {$row["conf_ISBN"]} </td>";
                        echo " </tr>";

                        }


                        ?>
                        </tbody>
                </table>

            </div>

        </div>
        </p>
        </div> <!-- end row -->


    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader">
            <div class="line-scale">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>


    <!-- Java Script
    ================================================== -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
