<?php
include("config.php");
session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}
$uname = $_SESSION['uname'];
$member = $_SESSION['member'];

//sql query to fetch information of registerd user and finds user match.
$query = mysqli_query($conn, "select count(*) as journal_count from journal;");
$journal_count = $query->fetch_array(MYSQLI_BOTH);
$journal_count = $journal_count[0];
$query = mysqli_query($conn, "select count(*) as all_journal_paper_citations_count
from (publish join (submission join (submitted_to join (pubMech join journal)))),submissionReferenced
where sub_ID_publish = sub_ID AND sub_ID = submit_ID AND submit_ISBN = ISBN AND journal_ISBN = ISBN AND sub_ID_ref = sub_ID;");
$citation_count = $query->fetch_array(MYSQLI_BOTH);
$citation_count = $citation_count[0];
$query = mysqli_query($conn, "select count(*) as all_journal_paper_count
from (publish join (submission join (submitted_to join (pubMech join journal))))
where sub_ID_publish = sub_ID AND sub_ID = submit_ID AND submit_ISBN = ISBN AND journal_ISBN = ISBN;");
$paper_count = $query->fetch_array(MYSQLI_BOTH);
$paper_count = $paper_count[0];
$view1 = mysqli_query($conn, "create view author_citation_count (author_ID, author_citation_count)
as select distinct author_ID, count(*) as author_citation_count
from (author join (has join (submission join publish))), submissionReferenced
where  sub_ID = sub_ID_publish and title = referenced and hID = author_ID and hsub_ID = sub_ID
group by author_ID;");
$view2 = mysqli_query($conn,"create view published_paper_count (published_author_ID, firstname, lastname, published_paper_count)
as select author_ID, firstname, lastname ,count(title) as published_paper_count
from (member join (author join ( has join (publish join (submission )))))
where author_ID =  ID and author_ID = hID and hsub_ID = sub_ID and sub_ID = sub_ID_publish
group by firstname, lastname;");
$result = mysqli_query($conn, "select firstname, lastname, published_paper_count, author_citation_count
from published_paper_count join author_citation_count
where author_ID = published_author_ID;");





$clean1 = mysqli_query($conn, "drop view author_citation_count;");
$clean2 = mysqli_query($conn, "drop view published_paper_count;");

if(isset($_POST['submit'])){
  $selected_val = $_POST['sort'];
  switch ($selected_val) {
        case 'Option1':

        $view1 = mysqli_query($conn, "create view author_citation_count (author_ID, author_citation_count)
        as select distinct author_ID, count(*) as author_citation_count
        from (author join (has join (submission join publish))), submissionReferenced
        where  sub_ID = sub_ID_publish and title = referenced and hID = author_ID and hsub_ID = sub_ID
        group by author_ID;");
        $view2 = mysqli_query($conn,"create view published_paper_count (published_author_ID, firstname, lastname, published_paper_count)
        as select author_ID, firstname, lastname ,count(title) as published_paper_count
        from (member join (author join ( has join (publish join (submission )))))
        where author_ID =  ID and author_ID = hID and hsub_ID = sub_ID and sub_ID = sub_ID_publish
        group by firstname, lastname;");
        $result = mysqli_query($conn, "select firstname, lastname, published_paper_count, author_citation_count
        from published_paper_count join author_citation_count
        where author_ID = published_author_ID order by lastname asc;");
        $clean1 = mysqli_query($conn, "drop view author_citation_count;");
        $clean2 = mysqli_query($conn, "drop view published_paper_count;");
          break;

        case 'Option2':
        $view1 = mysqli_query($conn, "create view author_citation_count (author_ID, author_citation_count)
        as select distinct author_ID, count(*) as author_citation_count
        from (author join (has join (submission join publish))), submissionReferenced
        where  sub_ID = sub_ID_publish and title = referenced and hID = author_ID and hsub_ID = sub_ID
        group by author_ID;");
        $view2 = mysqli_query($conn,"create view published_paper_count (published_author_ID, firstname, lastname, published_paper_count)
        as select author_ID, firstname, lastname ,count(title) as published_paper_count
        from (member join (author join ( has join (publish join (submission )))))
        where author_ID =  ID and author_ID = hID and hsub_ID = sub_ID and sub_ID = sub_ID_publish
        group by firstname, lastname;");
        $result = mysqli_query($conn, "select firstname, lastname, published_paper_count, author_citation_count
        from published_paper_count join author_citation_count
        where author_ID = published_author_ID order by lastname desc;");
        $clean1 = mysqli_query($conn, "drop view author_citation_count;");
        $clean2 = mysqli_query($conn, "drop view published_paper_count;");
            break;

        case 'Option3':
        $view1 = mysqli_query($conn, "create view author_citation_count (author_ID, author_citation_count)
        as select distinct author_ID, count(*) as author_citation_count
        from (author join (has join (submission join publish))), submissionReferenced
        where  sub_ID = sub_ID_publish and title = referenced and hID = author_ID and hsub_ID = sub_ID
        group by author_ID;");
        $view2 = mysqli_query($conn,"create view published_paper_count (published_author_ID, firstname, lastname, published_paper_count)
        as select author_ID, firstname, lastname ,count(title) as published_paper_count
        from (member join (author join ( has join (publish join (submission )))))
        where author_ID =  ID and author_ID = hID and hsub_ID = sub_ID and sub_ID = sub_ID_publish
        group by firstname, lastname;");
        $result = mysqli_query($conn, "select firstname, lastname, published_paper_count, author_citation_count
        from published_paper_count join author_citation_count
        where author_ID = published_author_ID order by author_citation_count desc;");
        $clean1 = mysqli_query($conn, "drop view author_citation_count;");
        $clean2 = mysqli_query($conn, "drop view published_paper_count;");
            break;

        case 'Option4':
        $view1 = mysqli_query($conn, "create view author_citation_count (author_ID, author_citation_count)
        as select distinct author_ID, count(*) as author_citation_count
        from (author join (has join (submission join publish))), submissionReferenced
        where  sub_ID = sub_ID_publish and title = referenced and hID = author_ID and hsub_ID = sub_ID
        group by author_ID;");
        $view2 = mysqli_query($conn,"create view published_paper_count (published_author_ID, firstname, lastname, published_paper_count)
        as select author_ID, firstname, lastname ,count(title) as published_paper_count
        from (member join (author join ( has join (publish join (submission )))))
        where author_ID =  ID and author_ID = hID and hsub_ID = sub_ID and sub_ID = sub_ID_publish
        group by firstname, lastname;");
        $result = mysqli_query($conn, "select firstname, lastname, published_paper_count, author_citation_count
        from published_paper_count join author_citation_count
        where author_ID = published_author_ID order by published_paper_count desc;");
            break;

        default:
            $result = mysqli_query($conn, "select distinct journal_name, cost, journal_ISBN, place_of_publication
            from journal
            order by journal_name asc;");
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
    <title>ScienceKeep - Authors</title>
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

                <h1>Authors</h1>



            </div>
            <p><form action="" method="post">
                    <div>
                        <label for="sampleRecipientInput">Sort By</label>
                        <div class="cl-custom-select">
                            <select class="full-width" id="sampleRecipientInput" name="sort">
                                <option value="Option1">A - Z</option>
                                <option value="Option2">Z - A</option>
                                <option value="Option3">Citation Count</option>
                                <option value="Option4">Paper Count</option>
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
                                <th>Paper Count</th>
                                <th>Citation Count</th>

                                <th>ScienceKeep Points®</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            while($row = mysqli_fetch_assoc($result)){
                              $sciencepoints = $row["author_citation_count"] * 19;
                              $firstname = $row["firstname"];
                              $lastname = $row["lastname"];
                              $memberid = mysqli_query($conn, "SELECT ID from member where firstname = '$firstname' and lastname = '$lastname';");
                              $memberid = $memberid -> fetch_array(MYSQLI_BOTH);

                              echo "<tr>";
                              echo " <td> <a href=\"author.php?auth={$memberid[0]}\"> {$row["firstname"]} {$row["lastname"]} </a></td>";
                              echo " <td>  {$row["published_paper_count"]} </td>";
                              echo " <td>  {$row["author_citation_count"]} </td>";

                              echo " <td>{$sciencepoints}</td>";
                              echo " </tr>";
                            }
                            ?>

                            </tbody>
                    </table>

                </div>



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
