<?php
include("config.php");
session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}
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
$result = mysqli_query($conn, "select distinct journal_name, cost, journal_ISBN, place_of_publication
from journal");

if(isset($_POST['submit'])){
  $selected_val = $_POST['sort'];
  switch ($selected_val) {
        case 'Option1':

          $result = mysqli_query($conn, "select distinct journal_name, cost, journal_ISBN, place_of_publication
          from journal
          order by journal_name asc;");
          break;

        case 'Option2':
          $result = mysqli_query($conn, "select distinct journal_name, cost, journal_ISBN, place_of_publication
          from journal
          order by journal_name desc;");
            break;

        case 'Option3':
            $result = mysqli_query($conn, "select distinct journal_name, cost, journal_ISBN, place_of_publication
            from journal
            order by cost asc;");
            break;

        case 'Option4':
            $result = mysqli_query($conn, "select distinct journal_name, cost, journal_ISBN, place_of_publication
            from journal
            order by place_of_publication asc;");
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
    <title>ScienceKeep - Contact</title>
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
                        <li class=current><a href="contact.php" title="">Contact</a></li>

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

        <div class="row">

          <div class="col-six tab-full">

              <h3 class="add-bottom">Our Motto</h3>

              <div class="video-container">
              <iframe width="560" height="315" src="https://www.youtube.com/embed/XiLBOnqhGdY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
              </div>

          </div>

            <div class="col-six tab-full">

                <h3 class="add-bottom">Reach ScienceKeep™ Team!</h3>

                <form>
                    <div>
                        <label for="sampleInput">Your email</label>
                        <input class="full-width" type="email" placeholder="erim.erdal@ug.bilkent.edu.tr" id="sampleInput">
                    </div>
                    <div>
                        <label for="sampleRecipientInput">Reason for contacting</label>
                        <div class="cl-custom-select">
                            <select class="full-width" id="sampleRecipientInput">
                                <option value="Option 1">Questions</option>
                                <option value="Option 2">Report</option>
                                <option value="Option 3">Others</option>
                            </select>
                        </div>
                    </div>

                    <label for="exampleMessage">Message</label>
                    <textarea class="full-width" placeholder="Your message" id="exampleMessage"></textarea>

                    <label class="add-bottom">
                        <input type="checkbox">
                        <span class="label-text">Send a copy to yourself</span>
                    </label>

                    <input class="btn--primary full-width" type="submit" value="Submit">

                </form>

            </div>

        </div> <!-- end row -->

    </section> <!-- end styles -->

    <!-- s-footer
    ================================================== -->
    <footer class="s-footer">

        <div class="s-footer__main">
            <div class="row">

                <div class="col-two md-four mob-full s-footer__social">

                    <h4>Erim Erdal</h4>

                    <ul class="s-footer__linklist">
                    <p><a href="#"><img width="120" height="120" class="pull" alt="sample-image" src="images/erimerdal.jpg"></a></p>
                        <li><a href="https://www.facebook.com/erime2">Facebook</a></li>
                        <li><a href="https://www.instagram.com/erim.erdal/">Instagram</a></li>
                        <li><a href="https://twitter.com/Erim_Erd">Twitter</a></li>
                        <li><a href="https://www.linkedin.com/in/erimerdal/">LinkedIn</a></li>
                    </ul>
              </div>
              <div class ="col-two md-four mob-full s-footer__social">
                    <h4>Emre Başar</h4>

                    <ul class="s-footer__linklist">
                    <p><a href="#"><img width="120" height="120" class="pull" alt="sample-image" src="images/emrebasar.jpg"></a></p>
                        <li><a href="https://www.facebook.com/E.m.R.e.Basar">Facebook</a></li>
                        <li><a href="https://www.instagram.com/eemrebasar/">Instagram</a></li>
                        <li><a href="https://twitter.com/eemrebasar">Twitter</a></li>
                        <li><a href="https://www.linkedin.com/in/eemrebasar/">LinkedIn</a></li>
                    </ul>
              </div>
              <div class ="col-two md-four mob-full s-footer__social">
                    <h4>Emre Gürçay</h4>

                    <ul class="s-footer__linklist">
                    <p><a href="#"><img width="120" height="120" class="pull" alt="sample-image" src="images/egurcay.jpg"></a></p>
                        <li><a href="https://www.facebook.com/emre.gurcay.egurcay">Facebook</a></li>
                        <li><a href="https://www.instagram.com/egurcay/">Instagram</a></li>
                        <li><a href="https://www.linkedin.com/in/egurcay/">LinkedIn</a></li>
                    </ul>
              </div>
              <div class ="col-two md-four mob-full s-footer__social">
                    <h4>Erdem Adaçal</h4>

                    <ul class="s-footer__linklist">
                    <p><a href="#"><img width="120" height="120" class="pull" alt="sample-image" src="images/erdemadacal.jpg"></a></p>
                        <li><a href="https://www.facebook.com/ea.ksk">Facebook</a></li>
                        <li><a href="https://www.instagram.com/erdemadacal/">Instagram</a></li>
                        <li><a href="https://www.linkedin.com/in/erdemadacal/">LinkedIn</a></li>
                    </ul>

                </div> <!-- end s-footer__social -->

            </div>
        </div> <!-- end s-footer__main -->

        <div class="s-footer__bottom">
            <div class="row">
                <div class="col-full">
                    <div class="s-footer__copyright">
                    </div>

                    <div class="go-top">
                        <a class="smoothscroll" title="Back to Top" href="#top"></a>
                    </div>
                </div>
            </div>
        </div> <!-- end s-footer__bottom -->

    </footer> <!-- end s-footer -->


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
