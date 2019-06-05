<?php
session_start();
require_once 'include/db.php';
date_default_timezone_set('Asia/Kolkata');

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    function disp_grps($con)
    {
        $stmt = $con->prepare("SELECT `grp_name` FROM `group_names`");
        if ($stmt->execute()) {
            $res_str = '<select class="form-control custom-select" name="group_selection" id="groups">
                            <option value="">--Select--</option>';
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $name = strtoupper($row['grp_name']);
                $name = str_replace("_", " ", $name);
                $res_str .= "<option value=" . $row['grp_name'] . ">" . $name . "</option>";
            }

            $res_str .= '</select>';

            echo $res_str;
        } else {
            echo "<p style='color: red; font-size: 15px;'><b>Error fetching groups! Please refresh page and try again.</b></p>";
        }
    }

    if (!isset($_SESSION['message']) and empty($_SESSION['message']))
        $_SESSION['error'] = 0;
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MinuteIt • New Meeting</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/home.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="src/all.css">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
        <style>
            .meet_form_label {
                font-size: 15px;
            }

            .margin-top {
                margin-top: 5%;
            }

            .container {
                margin-bottom: 10%;
                text-align: left;
            }

            .btn-custom,
            .input-custom {
                width: 100%;
                margin-top: 5%;
            }
        </style>
    </head>

    <body onload="new_meeting(); disable(); pseudo(<?php echo $_SESSION['error']; ?>, 'meeting');">
        <?php
        include "navbar.php";
        include "modals.php";
        ?>
        <div class="container" id="msgcon">
            <?php
            if (isset($_SESSION['message']) and !empty($_SESSION['message'])) {
                echo "<div class='alert alert-" . $_SESSION['type'] . "'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
                $_SESSION['error'] = 0;
            }
            ?>
        </div>

        <div class="container">
            <a class="btn btn-primary" href="home.php" id="back"><span class="fa fa-chevron-left mr-2 mb-1"></span>Back</a>
            <div class="input-group container">
                <form action="save_meeting.php" method="post">
                    <div class="form-row">
                        <div class="col-lg-4">
                            <div class="col-lg-2 col-sm-12 col-md-12">
                                <label class="meet_form_label" for="datepick" class="input-custom"><b>Date</b></label>
                            </div>
                            <div class="col-lg-7 col-sm-12 col-md-12">
                                <input class="form-control" type="date" value="<?php echo date("Y-m-d"); ?>" name="datepick" id="date" required="">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="col-lg-2 col-sm-12 col-md-12">
                                <label class="meet_form_label" for="timepick" class="input-custom" style="padding-right: 2px;"><b>Time</b></label>
                            </div>
                            <div class="col-lg-7 col-sm-12 col-md-12">
                                <input class="form-control" type="time" value="" name="timepick" id="time" required="">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="col-lg-2 col-sm-12 col-md-12">
                                <label class="meet_form_label" for="venue" class="input-custom" style="padding-right: 2px;"><b>Venue</b></label>
                            </div>
                            <div class="col-lg-auto col-sm-12 col-md-12">
                                <input class="form-control" type="text" name="venue" id="venue" required="" autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="form-row margin-top">
                        <div class="col-lg-12">
                            <div class="col-lg-6 col-sm-12 col-md-12">
                                <label class="meet_form_label" for="agenda"><b>Agenda</b></label>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <textarea class="form-control" name="agenda" id="agenda" cols="135" rows="5" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-row margin-top">
                        <div class="col-lg-12">
                            <div class="col-lg-6 col-sm-12 col-md-12">
                                <label class="meet_form_label" for="minute"><b>Minutes</b></label>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <textarea class="form-control" name="minutes" id="minutes" cols="135" rows="15" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-row margin-top">
                        <div class="col-lg-12">
                            <div class="col-lg-6 col-sm-12 col-md-12">
                                <label class="meet_form_label" for="group"><b>Select Group</b></label>
                            </div>
                            <div class="col-lg-4 col-sm-12 col-md-12">
                                <?php disp_grps($con); ?>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-md-12 mt-2">
                                <span id='errmsg' style="color: red;"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row margin-top pl-2">
                        <div class="col-lg-12">
                            <div id="names">

                            </div>
                        </div>
                    </div>

                    <div class="form-row margin-top">
                        <div class="col-lg-4 col-sm-4">
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <button type="button" class="btn btn-danger btn-custom" onclick="window.location.href='home.php'">Cancel</button>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <input type="submit" class="btn btn-success btn-custom" value="Save" id='savebtn'>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script src="src/jquery-3.3.1.min.js"></script>
        <script src="js/formatting.js"></script>
        <script src="js/add_rmv_group.js"></script>
        <script src="js/group_data_fetch.js"></script>
        <script src="js/grp_members_manage.js"></script>
        <script src="js/member_manage.js"></script>
        <script src="js/persistentFormData.js"></script>
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