<?php
   include("config.php");

   session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}
$ISBN = $_GET['ISBN'];
$uname = $_SESSION['uname'];
$member = $_SESSION['member'];

if(isset($_POST['Submit'])){

    $title=$_POST['title'];
    $topic=$_POST['topic'];
    $author_one =$_POST['author_one'];
    $author_two =$_POST['author_two'];
    $author_three=$_POST['author_three'];
    $referenced_one=$_POST['referenced_one'];
    $referenced_two=$_POST['referenced_two'];
    $referenced_three=$_POST['referenced_three'];

    //$profile_name = $query->fetch_array(MYSQLI_BOTH);
    //$rows = mysqli_num_rows($query);

    if(!empty($title) && !empty($topic))
    {
        $query = mysqli_query($conn, "INSERT IGNORE INTO submission (topic,title) VALUES ('$topic','$title');");
        $query1 = mysqli_query($conn, "select sub_ID from submission where title = '$title';");
        $sub_ID = $query1->fetch_array(MYSQLI_BOTH);
        $sub_ID = $sub_ID[0];
    }
    if(!empty($ISBN))
    {
        $query2 = mysqli_query($conn, "INSERT IGNORE INTO submitted_to (submit_ID, submit_ISBN) VALUES ('$sub_ID','$ISBN');");
    
    }
    if(!empty($author_one))
    {
        $query3 = mysqli_query($conn, "INSERT IGNORE INTO has (hsub_ID, hID) VALUES ('$sub_ID','$author_one');");
    
    }
    if(!empty($author_two))
    {
        $query4 = mysqli_query($conn, "INSERT IGNORE INTO has (hsub_ID, hID) VALUES ('$sub_ID','$author_two');");
        
    }
    if(!empty($author_three))
    {
        $query5 = mysqli_query($conn, "INSERT IGNORE INTO has (hsub_ID, hID) VALUES ('$sub_ID','$author_three');");
        
    }
    if(!empty($referenced_one))
    {
        $query6 = mysqli_query($conn, "INSERT IGNORE INTO submissionReferenced (sub_ID_ref, referenced) VALUES ('$sub_ID','$referenced_one');");
       
    }
    if(!empty($referenced_two))
    {
        $query7 = mysqli_query($conn, "INSERT IGNORE INTO submissionReferenced (sub_ID_ref, referenced) VALUES ('$sub_ID','$referenced_two');");
       
    }
    if(!empty($referenced_three))
    {
        $query8 = mysqli_query($conn, "INSERT IGNORE INTO submissionReferenced (sub_ID_ref, referenced) VALUES ('$sub_ID','$referenced_three');");
    }
    

}
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>ScienceKeep - Submit Paper</title>
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
	<section id="styles" class="s-styles">

	<div class="row narrow section-intro add-bottom text-center">

   
	<div class="col-twelve tab-full">
    <h1 class="well">Submit Your Paper</h1>
    <br>
				<form action = "" method = "post" style="text-align:center;">
					<div class="col-sm-8">
                        
						<div class="col-sm-4 form-group">
								<label>Title</label>
								<input type="text" name="title" placeholder="Title" required class="form-control">
						</div>
                        
                        <div class="col-sm-4 form-group" style="text-align:center;">
								<label>Topic</label>
								<input type="text" name="topic" placeholder="Topic" required class="form-control">
						</div>
                        
                        <div class="col-sm-4 form-group">
						<label>Publish ISBN</label>
						<input type="text" name="ISBN" placeholder=<?php echo "\"{$ISBN}\""?> disabled >
                        </div>
						
                    </div>
							
                    <div class="col-sm-8">
						<div class="col-sm-4 form-group">
								<label>Author 1 ID</label>
								<input type="text" name="author_one" placeholder="Author 1 ID" class="form-control" required>
						</div>
						
                        <div class="col-sm-4 form-group">
                        <label>Author 2 ID</label>
						<input type="text" name="author_two" placeholder="Author 2 ID" >
                        </div>
                            
                        <div class="col-sm-4 form-group">
                        <label>Author 3 ID</label>
						<input type="text" name="author_three" placeholder="Author 3 ID" >
                        </div>
                    </div>
                            
                     <div class="col-sm-8">
						<div class="col-sm-4 form-group">
								<label>Referenced Paper 1</label>
								<input type="text" name="referenced_one" placeholder="Referenced Paper 1" class="form-control">
						</div>
						
                        <div class="col-sm-4 form-group">
								<label>Referenced Paper 2</label>
								<input type="text" name="referenced_two" placeholder="Referenced Paper 2" class="form-control">
						</div>
                            
                        <div class="col-sm-4 form-group">
								<label>Referenced Paper 3</label>
								<input type="text" name="referenced_three" placeholder="Referenced Paper 3" class="form-control">
						</div>
                        <input type = "submit" name = "Submit" class="btn--primary btn--medium "/>
                    </div>
						
	</form>
	</div>
	
	</div>
	</section> <!-- end styles -->

    <!-- Java Script
    ================================================== -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>