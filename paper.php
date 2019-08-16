<?php
include("config.php");

session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}
$member = $_SESSION['member'];

$title = $_GET['title'];

$query = mysqli_query($conn, "select firstname, lastname
from (member join (reviewer join reviewerAccepted))
where ID = reviewer_ID and reviewer_ID_accepted = reviewer_ID and accepted_papers = '$title';");
$reviewer = $query->fetch_array(MYSQLI_BOTH);
//sql query to fetch information of registerd user and finds user match.

$query = mysqli_query($conn, "select topic from submission where title = '$title';");
$topic = $query->fetch_array(MYSQLI_BOTH);

$query = mysqli_query($conn, "select publish_date
from submission join publish
where title =  '$title' and sub_ID = sub_ID_publish;");
$date = $query->fetch_array(MYSQLI_BOTH);

$query = mysqli_query($conn, "select firstname , lastname
from (member join ( author join ( has join (submission join publish))))
where ID = author_ID and hID = author_ID and hsub_ID = sub_ID and sub_ID = sub_ID_publish and title =  '$title';");


/*$query = mysqli_query($conn, "select count(*) as all_conference_citations
from (submitted_to join (pubMech join (submission join publish))),conference, submissionReferenced
where conf_ISBN =  ISBN AND submit_ID = sub_ID AND submit_ISBN = ISBN AND sub_ID = sub_ID_publish AND sub_ID_ref = sub_ID;");
$citation_count = $query->fetch_array(MYSQLI_BOTH);
$citation_count = $citation_count[0];
$query = mysqli_query($conn, "select count(*) as all_conference_papers
from (submitted_to join (pubMech join (submission join publish))),conference
where conf_ISBN =  ISBN AND submit_ID = sub_ID AND submit_ISBN = ISBN AND sub_ID = sub_ID_publish;");
$paper_count = $query->fetch_array(MYSQLI_BOTH);
$paper_count = $paper_count[0];
$result = mysqli_query($conn, "select distinct conf_name, location, capacity from conference order by conf_name;");*/

?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>ScienceKeep - Paper</title>
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
                            <?php
                        if($member == "AUTHOR")
                        {
                         echo "<li><a href=\"additionalinfo.php\" title=\"\">Additional Information</a></li>";

                        }

                         ?>
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

            <div class="col-twelve">

                <h3><?php echo $title ?></h3>
<p><a href="#"><img width="120" height="120" class="pull" alt="sample-image" src="images/paper.png"></a></p>

<?php
$firstname = $reviewer[0];
$lastname = $reviewer[1];
$query2 = mysqli_query($conn,"select id from member where firstname='$firstname' and lastname='$lastname';");
 $id = $query2->fetch_array(MYSQLI_BOTH);
echo " <tr><b>Reviewed By:</b><a href=\"author.php?auth={$id[0]}\"> {$reviewer[0]} {$reviewer[1]}</a></tr>\n";
echo " <br><tr><b>Publishment Date:</b> {$date[0]}</tr>\n";

?>

            </div>

        </div>


         <div class="row half-bottom">

            <div class="col-six tab-full">

                <h2>Authors & Research Interests</h2>
                 <ul class="disc">
                    <?php
                            while($row = mysqli_fetch_assoc($query)){
                                $firstname = $row["firstname"];
                                $lastname =  $row["lastname"];
                                $query2 = mysqli_query($conn,"select id from member where firstname='$firstname' and lastname='$lastname';");
                                $id = $query2->fetch_array(MYSQLI_BOTH);
                              echo " <li><a href=\"author.php?auth={$id[0]}\">{$row["firstname"]} {$row["lastname"]}</a></li>";

                            $query2 = mysqli_query($conn,"select distinct research_interest
from (authorInterest join ( author join member))
where author_ID = ID and firstname = '$firstname' and lastname = '$lastname' and author_ID_interest = $id[0];");
                            while($row2 = mysqli_fetch_assoc($query2)){
                        echo " <ul><li>{$row2["research_interest"]}</li></ul>";
                            }
                        }
                            ?>      
                </ul>

            </div>


            <div class="col-six tab-full">

                <h2>Topic</h2>
                <?php
                echo "<a href=\"searchresults.php?searchBy=topic&keyword={$topic[0]}\">{$topic[0]}</a>";
               ?>

            </div>

        </div>
</section>

    <!-- Java Script
    ================================================== -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>