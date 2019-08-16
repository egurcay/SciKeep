<?php
include("config.php");

session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}

$title = $_GET['title'];
$member = $_SESSION['member'];

$query = mysqli_query($conn, "SELECT count(title) as submitted_count, place_of_publication, pubMech.topic, ISBN , firstname , lastname
from (journal join (pubMech join (submitted_to join (submission)))), member
where journal_name = '$title' and journal_ISBN = ISBN and submit_ISBN = ISBN and sub_ID = submit_ID  and member_status = 'EDITOR';");

$result = $query->fetch_array(MYSQLI_BOTH);
$submitted = $result[0];
$place = $result[1];
$topic = $result[2];
$isbn = $result[3];
$firstname = $result[4];
$lastname = $result[5];

$query = mysqli_query($conn, "SELECT count(title) as published_count
from (journal join (pubMech join (submitted_to join (submission join publish))))
where journal_name = '$title' and journal_ISBN = ISBN and submit_ISBN = ISBN and sub_ID = submit_ID  and sub_ID = sub_ID_publish;");
$published = $query->fetch_array(MYSQLI_BOTH);
$published = $published[0];



$result = mysqli_query($conn, "SELECT distinct title, publish_date, submission.topic
from (journal join (pubMech join (submitted_to join (submission join publish))))
where journal_name = '$title' and journal_ISBN = ISBN and submit_ISBN = ISBN and sub_ID = submit_ID  and sub_ID = sub_ID_publish;");

if(isset($_POST['submit'])){
  $selected_val = $_POST['sort'];
  switch ($selected_val) {
        case 'Option1':

          $result = mysqli_query($conn, "select distinct title, publish_date, submission.topic
from (journal join (pubMech join (submitted_to join (submission join publish))))
where journal_name = '$title' and journal_ISBN = ISBN and submit_ISBN = ISBN and sub_ID = submit_ID  and sub_ID = sub_ID_publish
order by title asc;");
          break;

        case 'Option2':
          $result = mysqli_query($conn, "select distinct title, publish_date, submission.topic
from (journal join (pubMech join (submitted_to join (submission join publish))))
where journal_name = '$title' and journal_ISBN = ISBN and submit_ISBN = ISBN and sub_ID = submit_ID  and sub_ID = sub_ID_publish
order by title desc;
");
            break;

        case 'Option3':
            $result = mysqli_query($conn, "select distinct title, publish_date, submission.topic
from (journal join (pubMech join (submitted_to join (submission join publish))))
where journal_name = '$title' and journal_ISBN = ISBN and submit_ISBN = ISBN and sub_ID = submit_ID  and sub_ID = sub_ID_publish
order by publish_date asc;");
            break;
        default:
            $result = mysqli_query($conn, "SELECT distinct title, publish_date, submission.topic
from (journal join (pubMech join (submitted_to join (submission join publish))))
where journal_name = '$title' and journal_ISBN = ISBN and submit_ISBN = ISBN and sub_ID = submit_ID  and sub_ID = sub_ID_publish;");
            break;
    }
}

$uname = $_SESSION['uname'];

$get_ISBN = mysqli_query($conn, "select journal_ISBN from journal where journal_name = '$title';");
$ISBN = $get_ISBN->fetch_array(MYSQLI_BOTH);
$ISBN = $ISBN[0];

if(isset($_POST['subscribe'])){
$sub_jour = mysqli_query($conn, "INSERT INTO mem_journal (mem_ISBN, mem_ID) VALUES ('$ISBN','$uname');");

if($sub_jour){
        echo '<script language="javascript">';
                        echo 'alert("You have successfully subscribed to '.$title.'! Its ISBN is '.$ISBN.'.\nHint: You can see the journals you have subscribed to from \'My Profile\' section.")';
                        echo '</script>';
}
else{
    $check_duplicate = mysqli_query($conn, "SELECT * from mem_journal WHERE mem_ISBN='$ISBN' and mem_ID='$uname';");
    if (mysqli_num_rows($check_duplicate)>0) {
    echo '<script language="javascript">';
    echo 'alert("You have already subscribed to '.$title.'!\nHint: You can see the journals you have subscribed to from \'My Profile\' section.")';
    echo '</script>';
}
else{
    echo '<script language="javascript">';
    echo 'alert("We are sorry that we cannot provide this :(\nScienceKeep Team")';
    echo '</script>';
}
}
}

?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>ScienceKeep - Journal</title>
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
                            <span class="hide-content">Search Paper</span>
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

                <h1> <?php echo $title ?></h1>

                <ul class="stats-tabs">
                    <li><a href="#"> <?php echo $submitted ?> <em>Submitted Paper</em></a></li>
                    <li><a href="#"> <?php echo $published ?> <em>Published Paper</em></a></li>
                    <li><a href="#"><?php echo $topic ?> <em>Topic</em></a></li>
                    <li><a href="#"><?php echo $isbn ?> <em>ISBN</em></a></li>
                    <li><a href="#"><?php echo $place ?> <em>Place of Publication</em></a></li>
                    </ul>
                    <?php
                    if($member =='AUTHOR'){
                      echo  "<p><form action=\"submitpaper.php?ISBN={$ISBN}\" method=\"post\">";
                      echo "<input class=\"btn--primary btn--medium\" type=\"submit\" value=\"submit your paper\" name=\"submit-paper\"></form></p>";
                
                    }?>
                      <p><form action="#" method="post">
                      <?php
                      echo "<input type=\"submit\" value = \"Subscribe To Journal\" name = \"subscribe\" class=\"btn btn-white \"/>" ;
                      ?>
                      </form></p>

            </div>
            <p><form action="#" method="post">
                    <div>
                        <label for="sampleRecipientInput">Sort By</label>
                        <div class="cl-custom-select">
                            <select class="full-width" id="sampleRecipientInput" name="sort">
                                <option value="Option1">A - Z</option>
                                <option value="Option2">Z - A</option>
                                <option value="Option3">Date</option>
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
                                <th>Topic</th>
                                <th>Publish Date</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            while($row = mysqli_fetch_assoc($result)){
                              echo "<tr>";
                              echo " <td><a href=\"paper.php?title={$row["title"]}\"> {$row["title"]} </a></td>";
                              echo " <td><a href=\"searchresults.php?searchBy=topic&keyword={$row["topic"]}\">  {$row["topic"]}</a> </td>";
                              echo " <td>  {$row["publish_date"]} </td>";
                              echo " </tr>";
                            }
                            ?>

                            </tbody>
                    </table>

                </div>



        </div> <!-- end row -->
</section>


    <!-- Java Script
    ================================================== -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
