<?php
   include("config.php");

   session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}
$uname = $_SESSION['uname'];
$member = $_SESSION['member'];

if(isset($_POST['Submit'])){

    $password=$_POST['password'];
    $repassword=$_POST['repassword'];
    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $email=$_POST['email'];
    $country=$_POST['country'];
    $city=$_POST['city'];
    $zip_code=$_POST['zip_code'];
    $website=$_POST['website'];
    $street_name=$_POST['street_name'];
    $street_number=$_POST['street_number'];

    //$profile_name = $query->fetch_array(MYSQLI_BOTH);
    //$rows = mysqli_num_rows($query);

    if(!empty($password) && !empty($repassword) && $password == $repassword)
    {
        $query = mysqli_query($conn, "UPDATE login
        set password = '$password'
        where login_ID = '$uname';");
        
    }
    if(!empty($firstname))
    {
        $query2 = mysqli_query($conn, "UPDATE member
        set firstname = '$firstname'
        where ID = '$uname';");
    
    }
    if(!empty($lastname))
    {
        $query3 = mysqli_query($conn, "UPDATE member
        set lastname = '$lastname'
        where ID = '$uname';");
        
    }
    if(!empty($email))
    {
        $query4 = mysqli_query($conn, "UPDATE member
        set email = '$email'
        where ID = '$uname';");
        
    }
    if(!empty($country))
    {
        $query5 = mysqli_query($conn, "UPDATE member
        set country = '$country'
        where ID = '$uname';");
       
    }
    if(!empty($city))
    {
        $query6 = mysqli_query($conn, "UPDATE member
        set city = '$city'
        where ID = '$uname';");
       
    }
    if(!empty($zipcode))
    {
        $query7 = mysqli_query($conn, "UPDATE member
        set zipcode = '$zipcode'
        where ID = '$uname';");
        
    }
    if(!empty($website))
    {
        $query8 = mysqli_query($conn, "UPDATE member
        set website = '$website'
        where ID = '$uname';");
       
    }
    if(!empty($street_name))
    {
        $query9 = mysqli_query($conn, "UPDATE member
        set street_name = '$street_name'
        where ID = '$uname';");
        
    }
    if(!empty($street_number))
    {
        $query10 = mysqli_query($conn, "UPDATE member
        set street_number = '$street_number'
        where ID = '$uname';");
        
    }

}
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>ScienceKeep - Edit Profile</title>
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
                            <li class=current><a href="editprofile.php">Edit Profile</a></li>
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
    <h1 class="well">Edit Your Profile</h1>
    <br>
				<form action = "" method = "post" style="text-align:center;">
					<div class="col-sm-8">
                        
						<div class="col-sm-4 form-group">
								<label>Password</label>
								<input type="password" name="password" placeholder="Enter New Password Here" class="form-control">
						</div>
                        
                        <div class="col-sm-4 form-group" style="text-align:center;">
								<label>Re-enter Password</label>
								<input type="password" name="repassword" placeholder="Re-Enter Password Here" class="form-control">
						</div>
                        
                        <div class="col-sm-4 form-group">
						<label>Email Address</label>
						<input type="text" name="email" placeholder="Change e-mail" >
                        </div>
						
                    </div>
							
                    <div class="col-sm-8">
						<div class="col-sm-4 form-group">
								<label>First Name</label>
								<input type="text" name="firstname" placeholder="Change first name" class="form-control">
						</div>
						
                        <div class="col-sm-4 form-group">
								<label>Last Name</label>
								<input type="text" name="lastname" placeholder="Change last name" class="form-control">
						</div>
                            
                        <div class="col-sm-4 form-group">
                        <label>Website</label>
						<input type="text" name="website" placeholder="Change website" >
                        </div>
                    </div>
                            
                     <div class="col-sm-8">
						<div class="col-sm-4 form-group">
								<label>City</label>
								<input type="text" name="city" placeholder="Change city" class="form-control">
						</div>
						
                        <div class="col-sm-4 form-group">
								<label>Country</label>
								<input type="text" name="country" placeholder="Change country" class="form-control">
						</div>
                            
                        <div class="col-sm-4 form-group">
								<label>Zip Code</label>
								<input type="text" name="zip_code" placeholder="Change zip-code" class="form-control">
						</div>
							
                    </div>
						
                    <div class="col-sm-8">
						<div class="col-sm-4 form-group">
								<label>Street Name</label>
								<input type="text" name="street_name" placeholder="Change street name" class="form-control">
						</div>
						
                        <div class="col-sm-4 form-group">
								<label>Street Number</label>
								<input type="text" name="street_number" placeholder="Change street number" class="form-control">
						</div>
                        
                        <div class="col-sm-4 form-group">
    
                            <input type = "submit" name = "Submit" class="btn--primary btn--medium "/>
						</div>
                        
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