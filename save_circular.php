<?php
session_start();

require_once 'include/db.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;


error_reporting(E_STRICT | E_ALL);


date_default_timezone_set('Asia/Kolkata');

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $stmt1 = $con->prepare("INSERT INTO `circulars` (`c_id`,`c_date`,`m_date`,`time`,`venue`,`agenda`) VALUES (?,?,?,?,?,?)");
        $stmt1->bind_param("ssssss", $c_id, $c_date, $m_date, $time, $venue, $agenda);

        $c_id = substr(sha1($_POST['cdatepick'].$_POST['agenda'].$_POST['mdatepick'].sha1(mt_rand(0, 2000))), 0, 5);
        $c_date = $_POST['cdatepick'];
        $m_date = $_POST['mdatepick'];
        $time = $_POST['timepick'];
        $venue = $_POST['venue'];
        $agenda = $_POST['agenda'];

        if (!$stmt1->execute())
        {
            echo "error";
            // echo $stmt1->error;
            exit();
        }
        else 
        {
            // echo "success";
            // echo "save_success";
            send_emails($c_id);
            //exit();
        }
    } 
    else 
    {
        echo "error";
        exit();
    }
}
else 
{
    $_SESSION['message'] = "You are logged out. Please login to continue!";
    $_SESSION['type'] = 'danger';
    header('Location: ./index.php');
}

function send_emails($c_id)
{
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->SMTPKeepAlive = true;
    $mail->Port = 587;

    $mail->Username = 'minuteit.ise@gmail.com';
    $mail->Password = $_POST['password'];
    $mail->setFrom( 'minuteit.ise@gmail.com', 'MinuteIt - ISE');
    $mail->addReplyTo( 'minuteit.ise@gmail.com', 'MinuteIt - ISE');

    $mail->Subject = "Circular";

    $mail->Body = 'Dear Faculty, <br><br><br><b>Please find the attached circular below.</b><br><br><br><br>Thank you.';
    $mail->IsHTML(true);

    $names = $_POST['names'];
    $emails = $_POST['emails'];

    $i = 0;
    // $not_sent_to = 0;

    $file_name = circularDoc();

    $mail->addAttachment($file_name, 'Circular_'.$c_id.'.docx');

    foreach ($_POST['attendees'] as $x) 
    {
        $mail->addAddress($emails[$i], $names[$i]);

       /*  if (!$mail->send())
        {
            // echo "error mail not sent";
            // echo "  Mail not sent to " . $emails[$i];
            $not_sent_to++;
            $not_sent_emails_to[$not_sent_to] = $emails[$i];
        } 
        else
        {
            // echo "  Mail sent to ".$emails[$i];
            $_SESSION['message'] = "E-mails have been successfully sent!";
            $_SESSION['type'] = 'success';
            header('Location: ./home.php');
            exit();
        }

        $mail->clearAddresses();
        $mail->clearAttachments(); */
        

        $i++;
    }

    if (!$mail->send()) {
        echo "error_mail_not_sent";
    } 
    else 
    {
        $mail->clearAddresses();
        $mail->clearAttachments();
        unlink($file_name);

        echo "success";
        // $_SESSION['message'] = "E-mails have been successfully sent!";
        // $_SESSION['type'] = 'success';
        // header('Location: ./home.php');
        // exit();
    }
}

function circularDoc()
{
    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    // $circ = get_circular_details($con, $c_id, $attachment);
    $date = dateConvert($_POST['cdatepick']);

    $temp_file_uri = tempnam('tmp/', 'cir');

    $section = $phpWord->addSection();

    $section->addText(
        'NITTE MEENAKSHI INSTITUTE OF TECHNOLOGY
            DEPARTMENT OF INFORMATION SCIENCE AND ENGINEERING',
        array('name' => 'Times New Roman', 'size' => 14, 'bold' => true),
        array('alignment' => 'center', 'spacing' => 130)
    );

    $section->addText(
        'Circular',
        array('name' => 'Times New Roman', 'size' => 14, 'bold' => true),
        array('alignment' => 'center', 'spacing' => 130)
    );

    $section->addLine(
        array(
            'width'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(12),
            'height'      => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(0),
            'positioning' => 'absolute',
        )
    );

    $section->addText(
        'Date: ' . $date,
        array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
        array('alignment' => 'right', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
    );

    $textrun = $section->addTextRun(
        array('alignment' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
    );
    $textrun->addText(
        'This is to inform all the faculty members that there will be a meeting at ',
        array('name' => 'Times New Roman', 'size' => 12),
        array('alignment' => 'left')
    );
    $textrun->addText(
        timeConvert($_POST['timepick']),
        array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
        array('alignment' => 'left')
    );
    $textrun->addText(
        ' on ',
        array('name' => 'Times New Roman', 'size' => 12),
        array('alignment' => 'left')
    );
    $date = dateConvert($_POST['mdatepick']);
    $textrun->addText(
        $date,
        array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
        array('alignment' => 'left')
    );

    $section->addText(
        'Attendance is compulsory.',
        array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
        array('alignment' => 'left')
    );

    //Print Agenda
    $section->addText(
        'Agenda - ',
        array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
        array('alignment' => 'left', 'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(20), 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
    );

    $html = nl2li($_POST['agenda']);

    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

    $section->addTextBreak(2);

    $section->addText(
        'Venue: ' . $_POST['venue'],
        array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
        array('alignment' => 'left')
    );

    $section->addText(
        'Date: ' . $date,
        array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
        array('alignment' => 'left')
    );

    $time = timeConvert($_POST['timepick']);

    $section->addText(
        'Time: ' . $time,
        array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
        array('alignment' => 'left')
    );

    $section->addTextBreak(3);

    $section->addText(
        'HOD’s Signature',
        array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
        array('alignment' => 'right', 'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
    );

    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    // $objWriter->save('php://tmp/circular.docx');
    $objWriter->save($temp_file_uri);

    return $temp_file_uri;
}

function dateConvert($d)
{
    $date = explode("-", $d);
    $_date = $date[2] . "/" . $date[1] . "/" . $date[0];

    return $_date;
}

function timeConvert($time)
{
    $explodedTime = explode(":", $time);
    $suffix = " a.m";

    if ($explodedTime[0] > 12) 
    {
        $time = ($explodedTime[0] - 12) % 10;
        $time .= ":".$explodedTime[1];
        $suffix = " p.m";
    } 
    else
    {
        $time = $explodedTime[0] % 10;
        $time .= ":".$explodedTime[1];
    }

    if ($time == 12)
        $time .= " p.m";
    else
        $time .= $suffix;

    return $time;
}

function nl2li($text)
{
    $tmp_arr1 = explode("\n\n", $text);
    $final_arr = array();
    // now $tmp_arr1 has n number of sections;
    foreach ($tmp_arr1 as $section) {
        $final_arr[] = explode("\n", $section);
    }

    $html = "";
    foreach ($final_arr as $section) {
        $html .= "<ul><li>";
        $html .= implode("</li><li>", $section);
        $html .= "</li></ul>";
    }

    return $html;
}