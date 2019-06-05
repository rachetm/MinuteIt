<?php
session_start();
require_once 'vendor/autoload.php';

date_default_timezone_set('Asia/Kolkata');


require_once 'include/db.php';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        function email_std()
        {

            $mail_to = $_POST['mail_to'];
            $mail_from = $_POST['mail_from'];
            $password = $_POST['password'];
            $mail_sub = $_POST['subject'];
            $From_name = "MinuteIt - ISE";
            $mail_content = $_POST['mail_body'];

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->IsSMTP(); // telling the class to use SMTP

            try {
                /*For Gmail*/

                $mail->Host       = "smtp.gmail.com";   // SMTP server
                $mail->SMTPAuth   = true;              // enable SMTP authentication
                $mail->SMTPSecure = "tls";            // sets the prefix to the servier
                $mail->Port       = 587;             // set the SMTP port for the GMAIL
                //Also change login credentials


                // /*For Yandex Mail*/
                // $mail->Host = 'smtp.yandex.com';  // Specify main and backup SMTP servers
                // $mail->SMTPAuth = true;                               // Enable SMTP authentication
                // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                // $mail->Port = 587;                                    // TCP port to connect to

                $mail->Username = $mail_from;                // SMTP username
                $mail->Password = $password;                           // SMTP password
                $mail->From = $mail_from;
                $mail->FromName = $From_name;

                // $mail->Username = 'deathrow.death@yandex.com';                 // SMTP username
                // $mail->Password = 'incorrect';                           // SMTP password
                // $mail->From = 'deathrow.death@yandex.com';
                // $mail->FromName = $From_name;


                $mail->addReplyTo($mail_from, 'MinuteIt - ISE');
                $mail->addAddress($mail_to, "");
                $mail->Subject = $mail_sub;
                $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
                $mail->Body = $mail_content;
                $mail->IsHTML(true);

                if ($mail->send()) {
                    return 1;
                } else {
                    return 0;
                }
            } catch (phpmailerException $e) {
                // echo "Error : ".$e->errorMessage();
                $_SESSION['message'] = "Something went wrong and the email couldn't been sent. Please try again.";
                $_SESSION['type'] = 'danger';
                header('Location: ./show_meetings.php');
                exit();
            } catch (Exception $e) {
                // echo $e->getMessage();
                $_SESSION['message'] = "Something went wrong and the email couldn't been sent. Please try again.";
                $_SESSION['type'] = 'danger';
                header('Location: ./show_meetings.php');
                exit();
            }
        }

        if (email_std()) {
            $_SESSION['message'] = "Email has been successfully sent!";
            $_SESSION['type'] = 'success';
            header('Location: ./show_meetings.php');
            exit();
        } else {
            $_SESSION['message'] = "Something went wrong and the email couldn't been sent. Please try again.";
            $_SESSION['type'] = 'danger';
            header('Location: ./show_meetings.php');
            exit();
        }
    }


    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MinuteIt • Email Minutes</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/home.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="src/all.css">
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
        <style>
            .email {}

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

            @media (min-width: 992px) {
                .btn-custom {
                    padding-left: 90px;
                    padding-right: 90px;
                }
            }
        </style>
    </head>

    <body onload="get_data(<?php echo $_GET['m_id']; ?>)">
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
            <form action="email_meet.php" method="POST">
                <div class="form-group">
                    <div class="row form-row form-group">
                        <div class="col-lg-4 col-sm-auto col-md-auto">
                            <label for="mail_to">To</label>
                            <input type="text" class="form-control field" class="email" name="mail_to" required autofocus>
                        </div>
                        <div class="col-lg-4 col-sm-auto col-md-auto">
                            <label for="mail_from">Your email</label>
                            <input type="text" class="form-control field" class="email" name="mail_from" value="minuteit.ise@gmail.com" required>
                        </div>
                        <div class="col-lg-4 col-sm-auto col-md-auto">
                            <label class="meet_form_label" for="password" class="input-custom" style="padding-right: 2px;">Password for your email</label>
                            <input type="password" class="form-control field" name="password" required>
                        </div>
                    </div>


                    <div class="row form-row form-group">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control field" name="subject" required>
                    </div>

                    <div class="row form-row form-group">
                        <label for="mail_body">Body</label>
                        <textarea class="form-control" name="mail_body" id="mail_body" cols="135" rows="25" required></textarea>
                    </div>

                    <div class="row form-row form-group float-lg-right">
                        <div class="col col-sm-auto mb-3">
                            <button type="button" class="btn btn-danger btn-custom" onclick="window.location.href='show_meetings.php'">Cancel</button>
                        </div>
                        <div class="col col-sm-auto">
                            <input type="submit" class="btn btn-success btn-custom" value="Send" id='savebtn'>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script src="src/jquery-3.3.1.min.js"></script>
        <script src="js/formatting.js"></script>
        <script src="js/add_rmv_group.js"></script>
        <script src="js/group_data_fetch.js"></script>
        <script src="js/grp_members_manage.js"></script>
        <script src="js/member_manage.js"></script>
        <script src="js/modal_autofocus.js"></script>
        <script src="src/popper.min.js"></script>
        <script src="src/bootstrap.min.js"></script>
        <script>
            function get_data(m_id) {
                var $m_id = m_id;
                $.ajax({
                    type: "POST",
                    url: "email_meet_dataGet.php",
                    data: {
                        "m_id": $m_id
                    },
                    success: function(data, status) {

                        if (data === "error") {
                            document.getElementById("msgcon").innerHTML = "<div class='alert alert-danger'>Something went wrong! Please try again.</div>";
                        } else {
                            let m_details = JSON.parse(JSON.parse(data)[0]);
                            let attendees = JSON.parse(data)[1];
                            // console.log(JSON.parse(data)[1][0]);
                            // console.log(attendees[0]);

                            let body = "<b>Date</b>\t : \t" + m_details.date + "\n\n<br><br>";
                            body += "<b>Time</b>\t : \t" + m_details.time + "\n\n<br><br>";
                            body += "<b>Venue</b>\t : \t" + m_details.venue + "\n\n<br><br>";
                            body += "<b>Agenda</b>\t : <br>\n\n" + nl2br(m_details.agenda) + "\n\n<br><br>";
                            body += "<b>Minutes</b>\t : <br>\n\n" + nl2br(m_details.minutes) + "\n\n<br><br>";

                            body += "\n<b>Attendees</b>\t : \n<br>";
                            var i = 0;
                            while (i < attendees.length) {
                                body += attendees[i].fac_name + "\n<br>";
                                i++;
                            }

                            $('#mail_body').html(body);
                        }
                    },
                    error: function(x, s, err) {
                        document.getElementById("msgcon").innerHTML = "<div class='alert alert-danger'>Something went wrong! Please try again.</div>";
                    }
                });
            }
        </script>
    </body>

    </html>

<?php
} else {
    $_SESSION['message'] = "You are logged out. Please login to continue!";
    $_SESSION['type'] = 'danger';
    header('Location: ./index.php');
}
?>