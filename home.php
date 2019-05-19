<?php

session_start();
require_once 'include/db.php';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    ?>

    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MinuteIt • Home</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/home.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="src/all.css">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    </head>

    <body>
        <?php
        include "navbar.php";
        include "modals.php";
        ?>

        <div class="container" id="msgcon">
            <?php
            if (isset($_SESSION['message']) and !empty($_SESSION['message'])) {
                echo "<div class='alert alert-" . $_SESSION['type'] . "'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }
            ?>
        </div>

        <!-- <div class="opcontainer">
                    <a href="new_circular.php">
                        <div class="row" id="a">
                            <div class="col-sm-12 col-lg-12 optionscol1">
                                <span class="fa fa-plus" id='cal'></span>
                                <p>New circular</p>
                            </div>
                        </div>
                    </a>

                    <a href="view_circulars.php">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12 optionscol1">
                                <span class="fa fa-file-alt" id='cal'></span>
                                <p>View previous circulars</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="opcontainer mb-5">
                    <a href="new_meeting.php" onclick="new_meeting()">
                        <div class="row" id="a">
                            <div class="col-sm-12 col-lg-12 optionscol">
                                <span class="fa fa-plus" id='cal'></span>
                                <p>New meeting</p>
                            </div>
                        </div>
                    </a>

                    <a href="show_meetings.php">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12 optionscol">
                                <span class="fa fa-calendar-alt" id='cal'></span>
                                <p>View previous meetings</p>
                            </div>
                        </div>
                    </a>
                </div> -->

        <div class="container" id="opcon">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <a href="new_circular.php">
                        <div class="card mb-3 optionscol">
                            <div class="mt-3"><b><span style="font-size: 18px; font-family: 'Lato', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">New Circular</span></b></div>
                            <div class="card-body">
                                <h1 class="card-title"><i class="fa fa-plus"></i></h1>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6 col-md-6">
                    <a href="view_circulars.php">
                        <div class="card mb-3 optionscol">
                            <div class="mt-3"><b><span style="font-size: 18px; font-family: 'Lato', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">View Circulars</span></b></div>
                            <div class="card-body">
                                <h1 class="card-title"><i class="fa fa-file-alt"></i></h1>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <a href="new_meeting.php" onclick="new_meeting()">
                        <div class="card mb-3 optionscol1">
                            <div class="mt-3"><b><span style="font-size: 18px; font-family: 'Lato', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">New Meeting</span></b></div>
                            <div class="card-body">
                                <h1 class="card-title"><i class="fa fa-plus"></i></h1>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6 col-md-6">
                    <a href="show_meetings.php">
                        <div class="card mb-3 optionscol1">
                            <div class="mt-3"><b><span style="font-size: 18px; font-family: 'Lato', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif">View Meetings</span></b></div>
                            <div class="card-body">
                                <h1 class="card-title"><i class="fa fa-calendar-alt"></i></h1>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer container-fluid">
            <p>Made with ♥ by <span><b>Rachet Mudnur</b></span></p>
        </div>

        <script src="src/jquery-3.3.1.min.js"></script>
        <script src="js/add_rmv_group.js"></script>
        <script src="js/group_data_fetch.js"></script>
        <script src="js/grp_members_manage.js"></script>
        <script src="js/member_manage.js"></script>
        <script src="js/modal_autofocus.js"></script>
        <script src="src/popper.min.js"></script>
        <script src="src/bootstrap.min.js"></script>

    </body>


    </html>
<?php

} else {
    $_SESSION['message'] = "You are logged out. Please login to continue!";
    $_SESSION['type'] = 'danger';
    header('Location: ./index.php');
}
?>