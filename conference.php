<?php
include("config.php");

session_start();
if(!$_SESSION['login']){
    header("location:login.php");
    die;
}

$title = $_GET['title'];
$member = $_SESSION['member'];


$query = mysqli_query($conn, "SELECT distinct date from (conference join pubMech) where conf_name = '$title' and conf_ISBN = ISBN;");
$date = $query->fetch_array(MYSQLI_BOTH);
$date = $date[0];

$query = mysqli_query($conn, "SELECT distinct location, capacity from conference where conf_name = '$title';");
$row = $query->fetch_array(MYSQLI_BOTH);
$location = $row[0];
$capacity = $row[1];

$query = mysqli_query($conn, "SELECT count(conf_session_id) as session_count
from (conference join (conf_present join sessions))
where conf_ISBN = conf_ISBN_present and conf_session_id = sessions_ID and conf_name = '$title';");
$row = $query->fetch_array(MYSQLI_BOTH);
$conf_session_id = $row[0];


$result = mysqli_query($conn, "SELECT distinct firstname, lastname , title
from (conference join (conf_present join (sessions join (presents join (member join (has join (submission join (publish join (pubMech join submitted_to)))))))))
where conf_ISBN = conf_ISBN_present and conf_name = '$title' and presenter_ID = ID and ID = hID and hsub_ID = sub_ID and sub_ID = sub_ID_publish and submit_ID = sub_ID and submit_ISBN = conf_ISBN
group by title;");

$get_ISBN = mysqli_query($conn, "select conf_ISBN from conference where conf_name = '$title';");
$ISBN = $get_ISBN->fetch_array(MYSQLI_BOTH);
$ISBN = $ISBN[0];
$uname = $_SESSION['uname'];

$result2 = mysqli_query($conn, "SELECT name, budget, type
from (conference join (funds join sponsor))
where conf_ISBN = funds_conf_ISBN and funds_name = name and conf_ISBN = '$ISBN';");

if(isset($_POST['attend'])){
$attend_conference = mysqli_query($conn, "INSERT INTO attends (attend_ID, attend_conf_ISBN) VALUES ('$uname','$ISBN');");

if($attend_conference){
        echo '<script language="javascript">';
                        echo 'alert("We have affirmed your attendance request to '.$title.'! Its ISBN is '.$ISBN.'.\nHint: You can see the conferences you have requested to attend from \'My Profile\' section.")';
                        echo '</script>';
}
else{
    $check_duplicate = mysqli_query($conn, "SELECT * from attends WHERE attend_conf_ISBN='$ISBN' and attend_ID='$uname';");
    if (mysqli_num_rows($check_duplicate) > 0) {
    echo '<script language="javascript">';
    echo 'alert("You are already an attendee of '.$title.'. We\'ve already reduced the available capacity for you. :) \nHint: You can see the conferences you have requested to attend from \'My Profile\' section.")';
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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ScienceKeep - Conference</title>

    <!-- css -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="css/conf.css">
</head>
<body data-spy="scroll" data-target="#site-nav">
    <nav id="site-nav" class="navbar navbar-fixed-top navbar-custom">
        <div class="container">
            <div class="navbar-header">

                <!-- logo -->
                <div class="site-branding">
                    <a class="logo" href="homepage.php">

                        <!-- logo image  -->
                        <img src="images/logo2.png" alt="Logo">
                    </a>
                </div>

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-items" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div><!-- /.navbar-header -->

            <div class="collapse navbar-collapse" id="navbar-items">
                <ul class="nav navbar-nav navbar-right">

                    <!-- navigation menu -->
                    <li><a href="homepage.php">Home</a></li>
                    <li><a data-scroll href="#submit">Submit Your Paper</a></li>
                    <li><a data-scroll href="#schedule">Sessions</a></li>
                    <li><a data-scroll href="#sponsor">Sponsors</a></li>

                </ul>
            </div>
        </div><!-- /.container -->
    </nav>

    <header id="site-header" class="site-header valign-center">

        <div class="intro">


            <h2><?php echo $date. " / " .$location ?></h2>

            <h1><?php echo $title ?></h1>

            <h1><?php echo "ISBN = " . $ISBN ?></h1>

            <p><br><br> JOIN THIS CONFERENCE</p>
            <p><form action="#" method="post">
            <?php
            echo "<input type=\"submit\" value = \"Attend\" name = \"attend\" class=\"btn btn-white \"/>" ;
            ?>
            </form></p>


        </div>

    </header>

    <section id="facts" class="section bg-image-1 facts text-center">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">

                    <i class="ion-ios-calendar"></i>
                    <h3> <?php echo $date ?></h3>

                </div>
                <div class="col-sm-3">

                    <i class="ion-ios-location"></i>
                    <h3><?php echo $location ?></h3>

                </div>
                <div class="col-sm-3">

                    <i class="ion-speakerphone"></i>
                    <h3><?php echo $conf_session_id ?><br>Sessions</h3>

                </div>
                <div class="col-sm-3">

                    <i class="ion-ios-person"></i>
                    <h3><?php echo $capacity ?><br>Capacity</h3>
                </div>
            </div><!-- row -->
        </div><!-- container -->
    </section>

    <section id="submit" class="section bg-image-2 contribution">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-uppercase mt0 font-400">Submit Your Paper</h3>
<?php
                    if($member =='AUTHOR'){
                      echo "<a class=\"btn btn-white\" href=\"submitpaper.php?ISBN={$ISBN}\">Submit</a>";
                
                    }
                    else{
                        echo "<p>Sorry, you cannot submit a paper unless your member type  is author.</p>";
                    }?>
                </div>
            </div>
        </div>
    </section>

    <section id="schedule" class="section schedule">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="section-title">Sessions</h3>
                </div>
            </div>
            <div class="row">

                    <?php
                            while($col = mysqli_fetch_assoc($result)){
                                                    $firstname = $col[0];
                                                    $lastname = $col[1];
                                                    $title_present = $col[2];
                            echo "<div class=\"col-md-4 col-sm-6\">";
                            echo "<div class=\"schedule-box\">";
                            echo "<h3>";
                            echo "<td> {$col["firstname"]} {$col["lastname"]} </td>";
                            echo "</h3>";
                            echo "<p>{$col["title"]}</p>";
                            echo "</div>";
                            echo "</div>";

                            }
                    ?>

                </div>

            </div>
    </section>
    <section id="sponsor" class="section schedule">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="section-title">Sponsors</h3>
                </div>
            </div>
            <div class="row">
                
                    <?php
                            while($col = mysqli_fetch_assoc($result2)){                  
                            echo "<div class=\"col-md-4 col-sm-6\">";
                            echo "<div class=\"schedule-box\">";
                            echo "<h3>"; 
                            echo "<td> {$col["name"]} </td>";
                            echo "</h3>";
                            echo "<p>{$col["budget"]}</p>";
                            echo "</h3>";
                            echo "<p>{$col["type"]}</p>";
                            echo "</div>";
                            echo "</div>";

                            }
                    ?>
    
                </div>
                
            </div>
    </section>

    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
        </div>
    </footer>

    <!-- script -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bower_components/smooth-scroll/dist/js/smooth-scroll.min.js"></script>
    <script src="js/conf.js"></script>
</body>
</html>
