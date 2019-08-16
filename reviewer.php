<?php

//Establishing Connection with server by passing server_name, user_id and pass as a patameter
include("config.php");
session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}
else{
  if($_SESSION['member'] != 'REVIEWER'){
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

$query = mysqli_query($conn, "SELECT count(*) as all_author_papers_citation
from (submissionReferenced natural join (submission natural join (has natural join author))), publish
where  sub_ID_publish = sub_ID AND author_ID = hID AND hsub_ID = sub_ID AND referenced = title and author_ID = '$uname'");
$citation_count = $query->fetch_array(MYSQLI_BOTH);



$query = mysqli_query($conn, "SELECT count(*) as submitted_papers
from (submission join (has join author))
where author_ID = '$uname' and sub_ID = hsub_ID and hID = author_ID");
$submitted_paper_count = $query->fetch_array(MYSQLI_BOTH);

$query = mysqli_query($conn, "SELECT count(*) as published_papers
from (publish join (submission join (has join author)))
where author_ID = '$uname' and sub_ID = hsub_ID and hID = author_ID and sub_ID_publish = sub_ID");
$published_paper_count = $query->fetch_array(MYSQLI_BOTH);


$papers = mysqli_query($conn, "SELECT distinct title, publish_date, name, count(referenced) as reference_count
        from (institution join (organises join (pubMech join (submitted_to join (publish join submissionReferenced))))), (submission join (has join author))
        where author_ID = '$uname' and author_ID = hID and hsub_ID = sub_ID and sub_ID = submit_ID and sub_ID_publish = sub_ID and submit_ISBN = ISBN and org_ISBN = ISBN and org_name =  name and referenced = title
        group by title, publish_date, name;");

$reivewers = mysqli_query($conn, "SELECT firstname, lastname, reviewer_ID
from member join reviewer
where reviewer_ID = ID;");

$submitted_papers = mysqli_query($conn, "SELECT  title, topic, sub_ID
from submission
where not exists ( select sub_ID_publish from publish where publish.sub_ID_publish = submission.sub_ID );");

$reviews = mysqli_query($conn, "select title, topic, sub_ID
from reviewerAssigned join submission
where sub_ID = assigned_paper_ID and reviewer_ID_assigned = '$uname';");

if(isset($_POST['submit-sort'])){
  $selected_val = $_POST['sort-option'];
  switch ($selected_val) {
        case 'Option1':

          $papers = mysqli_query($conn, "SELECT distinct title, publish_date, name, count(referenced) as reference_count
from (institution join (organises join (pubMech join (submitted_to join (publish join submissionReferenced))))), (submission join (has join author))
where author_ID = '$uname' and author_ID = hID and hsub_ID = sub_ID and sub_ID = submit_ID and sub_ID_publish = sub_ID and submit_ISBN = ISBN and org_ISBN = ISBN and org_name =  name and referenced = title
group by title, publish_date, name
order by title asc;");
          break;

        case 'Option2':
          $papers = mysqli_query($conn, "SELECT distinct title, publish_date, name, count(referenced) as reference_count
from (institution join (organises join (pubMech join (submitted_to join (publish join submissionReferenced))))), (submission join (has join author))
where author_ID = '$uname' and author_ID = hID and hsub_ID = sub_ID and sub_ID = submit_ID and sub_ID_publish = sub_ID and submit_ISBN = ISBN and org_ISBN = ISBN and org_name =  name and referenced = title
group by title, publish_date, name
order by title desc;");
            break;

        case 'Option3':
            $papers = mysqli_query($conn, "SELECT distinct title, publish_date, name, count(referenced) as reference_count
from (institution join (organises join (pubMech join (submitted_to join (publish join submissionReferenced))))), (submission join (has join author))
where author_ID = '$uname' and author_ID = hID and hsub_ID = sub_ID and sub_ID = submit_ID and sub_ID_publish = sub_ID and submit_ISBN = ISBN and org_ISBN = ISBN and org_name =  name and referenced = title
group by title, publish_date, name
order by publish_date asc;");
            break;

        case 'Option4':
            $papers = mysqli_query($conn, "SELECT distinct title, publish_date, name, count(referenced) as reference_count
    from (institution join (organises join (pubMech join (submitted_to join (publish join submissionReferenced))))), (submission join (has join author))
    where author_ID = '$uname' and author_ID = hID and hsub_ID = sub_ID and sub_ID = submit_ID and sub_ID_publish = sub_ID and submit_ISBN = ISBN and org_ISBN = ISBN and org_name =  name and referenced = title
    group by title, publish_date, name
    order by reference_count asc;");
            break;

        default:
            $papers = mysqli_query($conn, "SELECT distinct title, publish_date, name, count(referenced) as reference_count
        from (institution join (organises join (pubMech join (submitted_to join (publish join submissionReferenced))))), (submission join (has join author))
        where author_ID = '$uname' and author_ID = hID and hsub_ID = sub_ID and sub_ID = submit_ID and sub_ID_publish = sub_ID and submit_ISBN = ISBN and org_ISBN = ISBN and org_name =  name and referenced = title
        group by title, publish_date, name;");
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
    <title>Style Guide - Philosophy</title>
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
                         echo "<li class=current><a href=\"reviewer.php\" title=\"\">Review Papers</a></li>";

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

                <div>
                    <h2> Reviewer's Page </h2>

                    <h5>Assigned Papers</h5>
                </div>

        </div>

        <div class="row add-bottom">

            <div class="table-responsive">

                <table>
                        <thead>
                        <tr>

                            <th>Paper Name</th>
                            <th>Topic</th>
                            <th>Paper ID</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        if(isset($_POST['accepted'])){
                          $sub_ID = $_POST['sub_ID'];
                          $title = mysqli_query($conn, "select title from submission where sub_ID = '$sub_ID';");
                          $title = $title->fetch_array(MYSQLI_BOTH);
                          $title = $title[0];

                          $accept_paper = mysqli_query($conn, "INSERT IGNORE INTO reviewerAccepted (reviewer_ID_accepted, accepted_papers) VALUES ('$uname','$title');");
                          $publish_paper = mysqli_query($conn, "INSERT IGNORE INTO publish (sub_ID_publish, name_point, publish_date) VALUES ('$sub_ID','1', STR_TO_DATE('19.07.2018', '%d.%m.%Y'));");
                          $delete_assigned = mysqli_query($conn, "delete from reviewerAssigned where reviewer_ID_assigned = '$uname' and assigned_paper_ID = '$sub_ID';");
                          while($row = mysqli_fetch_assoc($reviews)){

                          echo "<tr>";
                          echo " <td> <a href=\"paper.php?title={$row["title"]}\"> {$row["title"]}  </td>"; // TODO: Add links.
                          echo " <td> {$row["topic"]}  </td>";
                          echo " <td> {$row["sub_ID"]} </td>";
                          echo " </tr>";
                          array_push($title, $row["title"]);
                          array_push($sub_ID, $row["sub_ID"]);
                          echo "<meta http-equiv='refresh' content='0'>";
                          }
                        }
                        else if(isset($_POST['declined'])){
                          $sub_ID = $_POST['sub_ID'];
                          $title = mysqli_query($conn, "select title from submission where sub_ID = '$sub_ID';");
                          $title = $title->fetch_array(MYSQLI_BOTH);
                          $title = $title[0];

                          $reject_paper = mysqli_query($conn, "INSERT IGNORE INTO reviewerRejected (reviewer_ID_rejected ,rejected_papers) VALUES ('$uname','$title');");
                          $delete_assigned = mysqli_query($conn, "delete from reviewerAssigned where reviewer_ID_assigned = '$uname' and assigned_paper_ID = '$sub_ID';");
                          while($row = mysqli_fetch_assoc($reviews)){

                          echo "<tr>";
                          echo " <td> <a href=\"paper.php?title={$row["title"]}\"> {$row["title"]}  </td>"; // TODO: Add links.
                          echo " <td> {$row["topic"]}  </td>";
                          echo " <td> {$row["sub_ID"]} </td>";
                          echo " </tr>";
                          array_push($title, $row["title"]);
                          array_push($sub_ID, $row["sub_ID"]);
                          echo "<meta http-equiv='refresh' content='0'>";
                          }
                        }
                        else {
                          while($row = mysqli_fetch_assoc($reviews)){

                          echo "<tr>";
                          echo " <td> <a href=\"paper.php?title={$row["title"]}\"> {$row["title"]}  </td>"; // TODO: Add links.
                          echo " <td> {$row["topic"]}  </td>";
                          echo " <td> {$row["sub_ID"]} </td>";
                          echo " </tr>";
                          array_push($title, $row["title"]);
                          array_push($sub_ID, $row["sub_ID"]);
                          }
                        }

                        ?>
                        </tbody>
                        <thead>
                        <tr>

                            <th>Enter Paper ID</th>
                            <th>Accept Paper</th>
                            <th>Reject Paper</th>

                        </tr>
                        </thead>

                        <tbody>
                        <p><form action="#" method="post">
                        <?php
                        echo " <td> <div class=\"field-wrap\"> <input type=\"text\" name = \"sub_ID\" placeholder=\"Enter Paper ID\"  autocomplete=\"off\"/> </div> </td>" ;
                        echo " <td> <input type=\"submit\" value = \"Accept\" name = \"accepted\" class=\"btn--primary btn--medium \"/> </td>" ;
                        echo " <td> <input type=\"submit\" value = \"Reject\" name = \"declined\" class=\"btn--primary btn--medium \"/> </td>" ;
                        ?>
                      </form></p>
                        </tbody>




                </table>
        </p>
</html>
