<?php
// LOG IN
include("config.php");

if(isset($_POST['login'])){
//Define $user and $pass
$uname=$_POST['uname'];
$psw=$_POST['psw'];


//sql query to fetch information of registerd user and finds user match.
$query = mysqli_query($conn, "SELECT * FROM login WHERE password='$psw' AND login_ID='$uname'");

$rows = mysqli_num_rows($query);
if($rows == 1){

session_start();
$_SESSION['uname'] = $uname;
$_SESSION['psw'] = $psw;

$query = mysqli_query($conn, "SELECT member_status FROM member WHERE ID='$uname'");
$row = $query->fetch_array(MYSQLI_BOTH);

$_SESSION['member'] = $row[0];
$_SESSION['login']=true;
header("Location: homepage.php"); // Redirecting to other page
}
else{
   echo '<script language="javascript">';
    echo 'alert("Invalid user ID or password");';
    echo '</script>';
    header("Location: homepage.php");
}
}
// SIGN UP
if(isset($_POST['signup'])){
  //Define all necessary attributes
  $uname=$_POST['uname'];
  $psw=$_POST['psw'];
  $fname=$_POST['fname'];
  $lname=$_POST['lname'];
  $email=$_POST['email'];
  $bdate=$_POST['bdate'];
  $country=$_POST['country'];
  $city=$_POST['city'];
  $zip=$_POST['zip'];
  $website=$_POST['website'];
  $street=$_POST['street'];
  $member=$_POST['member'];
  $streetno=100;


  //sql query to fetch information of registerd user and finds user match.
  $query = mysqli_query($conn, "INSERT IGNORE INTO member (ID, birth_date, member_status, email, website, firstname, lastname, zip_code, city, country, street_name, street_number) VALUES ('$uname', STR_TO_DATE('15.08.1997', '%d.%m.%Y'), '$member', '$email', '$website', '$fname', '$lname', '$zip', '$city', '$country', '$street', '$streetno');");
  $query = mysqli_query($conn, "INSERT IGNORE INTO login (login_ID, password) VALUES ('$uname','$psw');");

  if($member == 1){ //Subscriber
    $query = mysqli_query($conn, "INSERT IGNORE INTO subscriber (subscriber_ID,fee) VALUES ('$uname', '0');");
  }
  else if($member == 2){ //Author
    $query = mysqli_query($conn, "INSERT IGNORE INTO author (author_ID,education_level) VALUES ('$uname', 'NA');");
  }
  else if($member == 3){ //Editor
    $query = mysqli_query($conn, "INSERT IGNORE INTO editor (editor_ID) VALUES ('$uname');");
  }
  else if($member == 4){ //Reviewer
    $query = mysqli_query($conn, "INSERT IGNORE INTO reviewer (reviewer_ID) VALUES ('$uname');");
  }
  else{ //$member == 5 // Conference_Chair
    $query = mysqli_query($conn, "INSERT IGNORE INTO confChair (conf_chair_ID) VALUES ('$uname');");
  }

  //$query = mysqli_query($conn, "")
  //header("Location: homepage.php");

}
?>

<!DOCTYPE html>
<html lang="en" >
<style>
input[type=date]:required:invalid::-webkit-datetime-edit {
    color: #ffffff;
}
input[type=date]:focus::-webkit-datetime-edit {
    color: #ffffff; !important;
}
</style>
<head>
  <meta charset="UTF-8">
  <title>ScienceKeep Login</title>
  <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">


      <link rel="stylesheet" href="css/style.css">


</head>

<body>
                <div class="header__logo">
                    <a class="logo" href="homepage.php">
                        <img src="images/logo2.png" alt="Homepage">
                    </a>
                </div> <!-- end header__logo -->

  <div class="form">

      <ul class="tab-group">
        <li class="tab active"><a href="#login">Log In</a></li>
        <li class="tab"><a href="#signup">Sign Up</a></li>
      </ul>

      <div class="tab-content">

        <div id="login">
          <h1>Welcome Back!</h1>

          <form action="" method="post" style="text-align:center;">

            <div class="field-wrap">
            <label>
              User ID<span class="req">*</span>
            </label>
            <input type="text" name = "uname" required oninvalid="this.setCustomValidity('Please write a valid user ID')"
 oninput="setCustomValidity('')" autocomplete="off"/>
          </div>

          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" name = "psw" required oninvalid="this.setCustomValidity('This field cannot be blank')"
 oninput="setCustomValidity('')" autocomplete="off"/>
          </div>

          <button class="button button-block" type = "submit" name = "login" />Log In</button>

          </form>

        </div>
        <div id="signup">
          <h1>Sign Up for Free</h1>

          <form action="" method="post" style="text-align:center;">

          <div class="top-row">
            <div class="field-wrap">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" name = "fname" required autocomplete="off" />
            </div>

            <div class="field-wrap">
              <label>
                Last Name<span class="req">*</span>
              </label>
              <input type="text" name = "lname" required autocomplete="off"/>
            </div>
          </div>

          <div class="field-wrap">
            <label>
              User ID<span class="req">*</span>
            </label>
            <input type="text" name = "uname" required autocomplete="off"/>
          </div>

          <div class="field-wrap">
            <label>
              Enter E-mail<span class="req">*</span>
            </label>
            <input type="email" name = "email" required autocomplete="off"/>
          </div>

          <div class="field-wrap">
            <label>
              Set A Password<span class="req">*</span>
            </label>
            <input type="password" name = "psw" required autocomplete="off"/>
          </div>

          <div class="field-wrap">
            <input type="date" name = "bdate" required autocomplete="off" min = "1900-01-01" max = "2000-01-01"/>
          </div>

          <div class="top-row">

            <div class="field-wrap">
              <label>
                Select Country<span class="req">*</span>
              </label>
              <input type="text" name = "country" required autocomplete="off" />
            </div>

            <div class="field-wrap">
              <label>
                Select City<span class="req">*</span>
              </label>
              <input type="text" name = "city" required autocomplete="off"/>
            </div>
          </div>

          <div class="top-row">
            <div class="field-wrap">
              <label>
                Enter Street<span class="req">*</span>
              </label>
              <input type="text" name = "street" required autocomplete="off" />
            </div>

            <div class="field-wrap">
              <label>
                Enter Zipcode<span class="req">*</span>
              </label>
              <input type="text" name = "zip" required autocomplete="off"/>
            </div>
          </div>

          <div class="field-wrap">
            <label>
              Your website<span class="req">*</span>
            </label>
            <input type="text" name = "website" required autocomplete="off"/>
          </div>

          <div class="field-wrap">
            <label style="color:#ffffff;">Member Status<span class="req">*</span></label>
 <select name=member>
    <option value=1>Subscriber</option>
    <option value=2>Author</option>
    <option value=3>Editor</option>
    <option value=4>Reviewer</option>
    <option value=5>Conference Chair</option>
  </select>
</div>

          <button type="submit" name = "signup" class="button button-block"/>Get Started</button>

          </form>

        </div>

      </div><!-- tab-content -->

</div> <!-- /form -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>



    <script  src="js/index.js"></script>




</body>

</html>
