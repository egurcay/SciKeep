<?php
include("config.php");

session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}
$member = $_SESSION['member'];
//sql query to fetch information of registerd user and finds user match.
$query = mysqli_query($conn, "select count(*) as conference_count from conference");
$conference_count = $query->fetch_array(MYSQLI_BOTH);
$conference_count = $conference_count[0];
$query = mysqli_query($conn, "select count(*) as all_conference_citations
from (submitted_to join (pubMech join (submission join publish))),conference, submissionReferenced
where conf_ISBN =  ISBN AND submit_ID = sub_ID AND submit_ISBN = ISBN AND sub_ID = sub_ID_publish AND sub_ID_ref = sub_ID;");
$citation_count = $query->fetch_array(MYSQLI_BOTH);
$citation_count = $citation_count[0];
$query = mysqli_query($conn, "select count(*) as all_conference_papers
from (submitted_to join (pubMech join (submission join publish))),conference
where conf_ISBN =  ISBN AND submit_ID = sub_ID AND submit_ISBN = ISBN AND sub_ID = sub_ID_publish;");
$paper_count = $query->fetch_array(MYSQLI_BOTH);
$paper_count = $paper_count[0];
$result = mysqli_query($conn, "select distinct conf_name, location, capacity from conference order by conf_name;");

if(isset($_POST['submit'])){
  $selected_val = $_POST['sort'];
  switch ($selected_val) {
        case 'Option1':

          $result = mysqli_query($conn, "select distinct conf_name, location, capacity
  from conference order by conf_name asc;");
          break;

        case 'Option2':
          $result = mysqli_query($conn, "select distinct conf_name, location, capacity
from conference order by conf_name desc;");
            break;

        case 'Option3':
            $result = mysqli_query($conn, "select distinct conf_name, location, capacity
from conference order by capacity asc;");
            break;

        case 'Option4':
            $result = mysqli_query($conn, "select distinct conf_name, location, capacity
from conference order by location asc;");
            break;

        default:
            $result = mysqli_query($conn, "select distinct conf_name, location, capacity
from conference order by conf_name;");
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
    <title>ScienceKeep - Conferences</title>
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
                            echo "<li><a href=\"editor.php\" title=\"\">Editor's Page</a></li>";
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

        <div class="row narrow section-intro add-bottom text-center">

            <div class="col-twelve tab-full">

                <h1>Conferences</h1>

                <ul class="stats-tabs">
                    <li><a href=""> <?php echo $conference_count ?> <em>Conferences</em></a></li>
                    <li><a href=""> <?php echo $paper_count ?> <em>Total Papers</em></a></li>
                    <li><a href=""><?php echo $citation_count ?> <em>Citations</em></a></li>
                    </ul>

            </div>
            <p><form action="" method="post">
                    <div>
                        <label for="sampleRecipientInput">Sort By</label>
                        <div class="cl-custom-select">
                            <select class="full-width" id="sampleRecipientInput" name="sort">
                                <option value="Option1">A - Z</option>
                                <option value="Option2">Z - A</option>
                                <option value="Option3">Capacity</option>
                                <option value="Option4">Location</option>
                            </select>
                        </div>
                    </div>
                    <input class="btn--primary btn--medium" type="submit" value="sort" name="submit">
                </form>

              </p>

        </div>

        <div class="row add-bottom">

                <div class="table-responsive">

                    <table>
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Capacity</th>
                                <th>Location</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            while($row = mysqli_fetch_assoc($result)){
                              echo "<tr>";
                              echo " <td><a href=\"conference.php?title={$row["conf_name"]}\"> {$row["conf_name"]} </a></td>";
                              echo " <td>  {$row["capacity"]} </td>";
                              echo " <td>  {$row["location"]} </td>";
                              echo " </tr>";
                            }
                            ?>

                            </tbody>
                    </table>

                </div>


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
