<?php

//Establishing Connection with server by passing server_name, user_id and pass as a patameter
include("config.php");
session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}
else{
    if($_SESSION['member']!= 'EDITOR'){
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


if(isset($_POST['submit'])){
  $selected_val = $_POST['sort'];
  switch ($selected_val) {
        case 'Option1':
echo "anan";
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
    <title>ScienceKeep - Editor</title>
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
        <p><a href="#">Website</a><br><a href="#"><?php echo $email[0]?></a></p>
                        <ul class="stats-tabs">
                            <li><a href="#">1912 <em>ScienceKeep™ Points</em></a></li>
                            <li><a href="#"><?php echo $submitted_paper_count[0]?>  <em>Submitted Papers</em></a></li>
                            <li><a href="#"><?php echo $published_paper_count[0]?>  <em>Published Papers</em></a></li>
                            <li><a href="#"><?php echo $citation_count[0]?> <em>Citations</em></a></li>
                            </ul>

                    </div>
                    <p><form action="" method="post">
                            <div>
                                <h6>Published Papers</h6>
                                <label for="sampleRecipientInput">Sort By</label>
                                <div class="cl-custom-select">
                                    <select class="full-width" id="sampleRecipientInput" name = "sort">
                                        <option value="Option1">A - Z</option>
                                        <option value="Option2">Z - A</option>
                                        <option value="Option3">Publish Date</option>
                                        <option value="Option4">Citation Count</option>
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
                                        <th>Title</th>
                                        <th>Institution Name</th>
                                        <th>Publish Date</th>
                                        <th>Citation Count</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    while($row = mysqli_fetch_assoc($papers)){
                                    echo "<tr>";
                                    echo " <td><a href=\"#\"> {$row["title"]} </a></td>";
                                    echo " <td>  {$row["name"]} </td>";
                                    echo " <td>  {$row["publish_date"]} </td>";
                                    echo " <td>  {$row["reference_count"]} </td>";
                                    echo " </tr>";
                                    }
                                    ?>
                                    </tbody>
                            </table>

                        </div>

                    </div>

                </div> <!-- end row -->
                <div class="row narrow section-intro add-bottom text-center">

                <div>
                    <h6>Reviewers</h6>
                </div>
        </p>

        </div>

        <div class="row add-bottom">

            <div class="table-responsive">

                <table>
                        <thead>
                        <tr>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Reviewer ID</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while($row = mysqli_fetch_assoc($reivewers)){
                        echo "<tr>";
                        echo " <td><a href=\"#\"> {$row["firstname"]} </a></td>";
                        echo " <td>  {$row["lastname"]} </td>";
                        echo " <td>  {$row["reviewer_ID"]} </td>";
                        //echo ' <td> <div class="field-wrap"> <input type="text" name = "assign_paper" placeholder="Enter submission ID"  autocomplete="off"/> </div> </td>' ;
                        //echo ' <td> <input type = "submit" name = "Submit" class="btn--primary btn--medium "/> </td>' ;
                        echo " </tr>";

                        }
                        ?>
                        </tbody>
                        <thead>
                        <tr>

                            <th>Enter Paper ID</th>
                            <th>Enter Reviewer ID</th>
                            <th>Assign Paper</th>

                        </tr>
                        </thead>

                        <tbody>
                        <p><form action="" method="post">
                        <?php
                        echo " <td> <div class=\"field-wrap\"> <input type=\"text\" name = \"paper_ID\" placeholder=\"Enter Paper ID\"  autocomplete=\"off\"/> </div> </td>" ;
                        echo " <td> <div class=\"field-wrap\"> <input type=\"text\" name = \"reviewer_ID\" placeholder=\"Enter Reviewer ID\"  autocomplete=\"off\"/> </div> </td>" ;
                        echo " <td> <input type=\"submit\" value = \"Assign\" name = \"assign\" class=\"btn--primary btn--medium \"/> </td>" ;

                        if(isset($_POST['assign'])){
                          $paper_ID = $_POST['paper_ID'];
                          $reviewer_ID = $_POST['reviewer_ID'];
                          $title = mysqli_query($conn, "INSERT IGNORE INTO reviewerAssigned (reviewer_ID_assigned, editor_ID_assigned, assigned_paper_ID) VALUES ('$reviewer_ID','$uname','$paper_ID');");
                        }
                        ?>
                      </form></p>
                        </tbody>
                </table>

            </div>

        </div>
        <p><form action="" method="post">
        <div class="row narrow section-intro add-bottom text-center">

                <div>
                    <h6>Awaiting Papers</h6>
                </div>
        </p>

        </div>

        <div class="row add-bottom">

            <div class="table-responsive">

                <table>
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Topic</th>
                            <th>Paper ID</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $submitted_papers = mysqli_query($conn, "create view submitted_not_published (title, topic, sub_ID)
                        as SELECT distinct title, topic, sub_ID
                        from submission
                        where not exists ( select sub_ID_publish from publish where publish.sub_ID_publish = submission.sub_ID );");

                        $submitted_papers = mysqli_query($conn, "SELECT distinct title, topic, sub_ID
                        from submitted_not_published
                        where not exists ( select assigned_paper_ID from reviewerAssigned where reviewerAssigned.assigned_paper_ID = submitted_not_published.sub_ID );");

                        $drop = mysqli_query($conn, "drop view submitted_not_published;");

                        while($row = mysqli_fetch_assoc($submitted_papers)){
                        echo "<tr>";
                        echo " <td><a href=\"#\"> {$row["title"]} </a></td>";
                        echo " <td>  {$row["topic"]} </td>";
                        echo " <td>  {$row["sub_ID"]} </td>";
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
