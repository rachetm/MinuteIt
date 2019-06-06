<?php
session_start();
require_once 'include/db.php';
date_default_timezone_set('Asia/Kolkata');

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    function attendees($con, $m_id)
    {
        $stmt = $con->prepare("SELECT `fac_name` FROM `attendees` WHERE `m_id` = ?");
        $stmt->bind_param("i", $m_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo $row['fac_name'] . '<br>';
        }
    }

    function meet_deet($con, $row = null)
    {
        if ($row != null) {
            $date = explode("-", $row['date']);
            $_date = $date[2] . "-" . $date[1] . "-" . $date[0];

            echo '<div class="row" id="meet_' . $row['m_id'] . '">
                    <div class="col-lg-12">
                        <div class="tile">
                            <div class="table-responsive tablecon">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr><td><b>Date</b></td><td>' . $_date . '</td></tr>
                                        <tr><td><b>Time</b></td><td>' . timeConvert($row['time']) . '</td></tr>
                                        <tr><td><b>Venue</b></td><td>' . $row['venue'] . '</td></tr>
                                        <tr><td><b>Agenda</b></td><td class="info">' . nl2br($row['agenda']) . '</td></tr>
                                        <tr><td><b>Minutes</b></td><td class="info">' . nl2br($row['minutes']) . '</td></tr>
                                        <tr><td><b>Attendees</b></td><td class="info">';
            attendees($con, $row['m_id']);
            echo '</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row float-xl-left">';
            // <div class="col-auto mb-2">
            //     <button class="btn btn-danger" type="submit" onclick="confirmation_modal_show(' . $row['m_id'] . ')" disabled><span class="fa fa-trash-alt fa_cus mb-1"></span>Delete</button>
            // </div>
            echo '<div class="col-auto">
                                    <button class="btn btn-success" type="submit" onclick="action_modal_show(' . $row['m_id'] . ');"><span class="fa fa-check fa_cus mb-1"></span>Action Taken</button>
                                </div>
                            </div>
                            <div class="row float-xl-right">
                                <div class="col-auto mb-2">
                                    <form class="form-inline" action="email_meet.php" method="GET">
                                        <input type="hidden" name="m_id" value=' . $row['m_id'] . '>
                                        <button class="btn btn-warning" type="submit"><span class="fa fa-envelope fa_cus mb-1"></span>Email</button>
                                    </form>
                                </div>
                                <div class="col-auto">
                                    <form class="form-inline" action="docGenerate.php" method="POST">
                                        <input type="hidden" name="id" value="' . $row['m_id'] . '">
                                        <input type="hidden" name="type" value="meeting">
                                        <button class="btn btn-info doc_download" type="submit"><span class="fa fa-file-word fa_cus mb-1"></span>Download</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="border-top my-3 mx-5" id="meet_' . $row['m_id'] . '_hr' . '"></div>';
        } else {
            $date = explode("-", $_POST['datepick']);
            $date = $date[2] . "-" . $date[1] . "-" . $date[0];

            echo '<div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive tablecon">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr><td style="text-align:center">No records found for <b>' . $date . '</b></td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>';
        }
    }

    function display($con)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $stmt = $con->prepare("SELECT * FROM `meetings` WHERE `date` = ? ORDER BY `m_id` DESC");
            $date = $_POST['datepick'];
            $stmt->bind_param("s", $date);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                meet_deet($con, null);
            } else {
                while ($row = $result->fetch_assoc()) {
                    meet_deet($con, $row);
                }
            }
        } else {
            $stmt = $con->prepare("SELECT * FROM `meetings` ORDER BY `m_id` DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                meet_deet($con, $row);
            }
        }
    }
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MinuteIt • Meetings</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/home.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="src/all.css">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    </head>
    <style>
        .remaining-content span {
            display: none;
        }

        @media (min-width: 992px) {
            .container {
                margin-bottom: 30px;
            }
        }
    </style>

    <body>
        <?php
        include "navbar.php";
        include "modals.php";
        ?>

        <div class="container" id="msgcon">
            <span id="msg"></span>
            <?php
            if (isset($_SESSION['message']) and !empty($_SESSION['message'])) {
                echo "<div class='alert alert-" . $_SESSION['type'] . "'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
                $_SESSION['error'] = 0;
            }
            ?>
        </div>



        <div class="container" id="filter">
            <div class="container row"><a class="btn btn-primary" href="home.php" id="back"><span class="fa fa-chevron-left mr-2 mb-1"></span>Back</a></div>
            <form action="show_meetings.php" method="post">
                <div class="form-row">
                    <div class="col-auto mb-2">
                        <input class="form-control" id="date" type="date" name="datepick" required="">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success"><span class="fa fa-filter fa_cus mb-1"></span>Filter</button>
                    </div>
                    <div class="col-auto">
                        <button type="button" onclick="location.href='show_meetings.php'" class="btn btn-danger"><span class="fa fa-times fa_cus mb-1"></span>Remove Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="container">
            <?php
            display($con);
            ?>
        </div>

        <script src="src/jquery-3.3.1.min.js"></script>
        <script src="js/formatting.js"></script>
        <script src="js/add_rmv_group.js"></script>
        <script src="js/group_data_fetch.js"></script>
        <script src="js/grp_members_manage.js"></script>
        <script src="js/member_manage.js"></script>
        <script src="js/add_action.js"></script>
        <script src="js/modal_autofocus.js"></script>
        <script src="js/persistentFormData.js"></script>
        <script src="js/del_meeting.js"></script>
        <script src="js/docGenerate.js"></script>
        <script src="src/popper.min.js"></script>
        <script src="src/bootstrap.min.js"></script>
        <script>
            var showChar = 100;
            var ellipsestext = "...";
            var moretext = "See More";
            var lesstext = "See Less";
            $('.info').each(function() {
                var content = $(this).html();
                if (content.length > showChar) {
                    var show_content = content.substr(0, showChar);
                    var hide_content = content.substr(showChar, content.length - showChar);
                    var html = show_content + '<span class="moreelipses">' + ellipsestext + '</span><span class="remaining-content"><span>' + hide_content + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
                    $(this).html(html);
                }
            });

            $(".morelink").click(function() {
                if ($(this).hasClass("less")) {
                    $(this).removeClass("less");
                    $(this).html(moretext);
                } else {
                    $(this).addClass("less");
                    $(this).html(lesstext);
                }
                $(this).parent().prev().toggle();
                $(this).prev().toggle();
                return false;
            });
        </script>
    </body>

    </html>
<?php

} else {
    $_SESSION['message'] = "You are logged out. Please login to continue!";
    $_SESSION['type'] = 'danger';
    header('Location: ./index.php');
}

function timeConvert($time)
{
    $explodedTime = explode(":", $time);
    // $suffix = " a.m";

    if ($explodedTime[0] > 12) 
    {
        if( ($explodedTime[0] >= 22 ))
        {
            $time = ($explodedTime[0] - 12);
            $time .= ":".$explodedTime[1];
            $suffix = " p.m";
        }
        else 
        {
            $time = ($explodedTime[0] - 12) % 10;
            $time .= ":" . $explodedTime[1];
            $suffix = " p.m";
        }
    } 
    else if ($explodedTime[0] <= 12)
    {
        if( ($explodedTime[0]) >= 10)
        {
            $time = $explodedTime[0];
            $time .= ":".$explodedTime[1];
            if($explodedTime[0] == 12)
                $suffix = " p.m";
            else
                $suffix = " a.m";
        }
        else {
            $time = $explodedTime[0] % 10;
            $time .= ":" . $explodedTime[1];
            $suffix = " a.m";
        }
    }

    $time .= $suffix;

    return $time;
}

// function timeConvert($time)
// {
//     $explodedTime = explode(":", $time);
//     $suffix = " a.m";

//     if ($explodedTime[0] > 12) {
//         $time = ($explodedTime[0] - 12) % 10;
//         $time .= ":" . $explodedTime[1];
//         $suffix = " p.m";
//     } else {
//         $time = $explodedTime[0] % 10;
//         $time .= ":" . $explodedTime[1];
//     }

//     if ($time == 12)
//         $time .= " p.m";
//     else
//         $time .= $suffix;

//     return $time;
// }
?>