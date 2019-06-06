<?php
    session_start();

    require_once 'include/db.php';
    require_once 'vendor/autoload.php';

    date_default_timezone_set('Asia/Kolkata');

    if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
    {
        $page = $_SERVER['HTTP_REFERER'];
        // $page = explode('/',$_SERVER[ 'HTTP_REFERER'])[3];

        if ( $_SERVER['REQUEST_METHOD'] === 'POST' )
        {

            if(isset($_POST['type']))
            {
                if($_POST['type'] === "circular")
                    circularDoc($con);
                elseif ($_POST['type'] === "meeting")
                    minuteDoc($con);
                elseif  ($_POST['type'] === "action")
                    actionDoc($con);
                else
                {
                    $_SESSION['message'] = "Something went wrong! Please try again.";
                    $_SESSION['type'] = 'danger';
                    header('Location: '.$page);
                    exit();
                }
            }
            else 
            {
                $_SESSION['message'] = "Something went wrong! Please try again.";
                $_SESSION['type'] = 'danger';
                header('Location: '.$page);
                exit();
            }
        }
        else 
        {
            $_SESSION['message'] = "Something went wrong! Please try again.";
            $_SESSION['type'] = 'danger';
            header('Location: '.$page);
            exit();
        }
    }
    else 
    {
        $_SESSION['message'] = "You are logged out. Please login to continue!";
        $_SESSION['type'] = 'danger';
        header('Location: ./index.php');
        exit();
    }

    // ########-------- MINUTE DOC --------########
    function minuteDoc($con)
    {
        $meet = get_meet_details($con);

        $date = dateConvert($meet['date']);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $file = str_replace('/', '-', $date).' - Proceedings.docx';

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        
        $section = $phpWord->addSection();

        $section->addText(
            'NITTE MEENAKSHI INSTITUTE OF TECHNOLOGY
            DEPARTMENT OF INFORMATION SCIENCE AND ENGINEERING',
            array('name' => 'Times New Roman', 'size' => 14, 'bold' => true), array('alignment' => 'center', 'spacing' => 130)
        );

        $section->addLine(
            array(
                'width'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(12),
                'height'      => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(0),
                'positioning' => 'absolute',
            )
        );

        $section->addText( 
            'Proceedings of the meeting dated '.$date,
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true, 'underline' => 'single'),
            array('alignment' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );

        $textrun = $section->addTextRun(
            array('alignment' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );        
        $textrun->addText( 
            'The meeting was held in ',
            array('name' => 'Times New Roman', 'size' => 12),
            array('alignment' => 'left')
        );
        $textrun->addText(
            $meet['venue'],
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'left')
        );
        $textrun->addText( 
            ' on ',
            array('name' => 'Times New Roman', 'size' => 12),
            array('alignment' => 'left')
        );
        $textrun->addText( 
            $date,
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'left')
        );

        $section->addText( 
            'The following members were present -',
            array('name' => 'Times New Roman', 'size' => 12),
            array('alignment' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );

        $section->addText( 
            'MEMBERS - ',
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true, 'underline' => 'single'),
            array('alignment' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );


        //Print Attendees
        $attendees = get_attendees($con);
        
        while ($row = $attendees->fetch_assoc()) 
        {
            $section->addListItem( $row['fac_name'], 0, null, \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER);
        }

        $section->addText(
            'MINUTES - ',
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true, 'underline' => 'single'),
            array('alignment' => 'left', 'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(20), 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );

        $section->addText(
            'Following points were discussed - ',
            array('name' => 'Times New Roman', 'size' => 12),
            array('alignment' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );
        
        $html = nl2li( $meet['minutes']);
        
        // $section->addListItem( $meet['minutes'], 0, null);
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
        
        $section->addTextBreak(3);

        $section->addText(
            'HOD’s Signature',
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'right', 'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );


        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');

    }
// #########---------- ACTION DOC ----------#########
    function actionDoc($con)
    {
        $meet = get_meet_details($con);
        $action = get_action_details($con);
        
        if($action == false)
            return;

        $date = dateConvert($meet['date']);
        $dt = dateConvert($action['date']);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $file = str_replace('/','-',$dt).' - Action Taken.docx';

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');


        $section = $phpWord->addSection();

        $section->addText(
            'NITTE MEENAKSHI INSTITUTE OF TECHNOLOGY
            DEPARTMENT OF INFORMATION SCIENCE AND ENGINEERING',
            array('name' => 'Times New Roman', 'size' => 14, 'bold' => true), array('alignment' => 'center', 'spacing' => 130)
        );

        $section->addLine(
            array(
                'width'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(12),
                'height'      => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(0),
                'positioning' => 'absolute',
            )
        );

        $section->addText( 
            'Action taken for the meeting dated '.$date,
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true, 'underline' => 'single'),
            array('alignment' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );

        $textrun = $section->addTextRun(
            array('alignment' => 'left', 'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );        
        $textrun->addText( 
            'The Action was taken on ',
            array('name' => 'Times New Roman', 'size' => 12),
            array('alignment' => 'left')
        );
        $textrun->addText(
            $dt,
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'left')
        );

        $html = nl2li($action['action_taken']);

        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

        $section->addTextBreak(3);

        $section->addText(
            'HOD’s Signature',
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'right', 'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
    }


// #########---------- CIRCULAR DOC ----------##########
    function circularDoc($con)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        $circ = get_circular_details($con);
        $date = dateConvert($circ['c_date']);

        $file = str_replace('/', '-', $date).' - Circular.docx';

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        $section = $phpWord->addSection();

        $section->addText(
            'NITTE MEENAKSHI INSTITUTE OF TECHNOLOGY
            DEPARTMENT OF INFORMATION SCIENCE AND ENGINEERING',
            array('name' => 'Times New Roman', 'size' => 14, 'bold' => true), array('alignment' => 'center', 'spacing' => 130)
        );

        $section->addText(
            'Circular',
            array('name' => 'Times New Roman', 'size' => 14, 'bold' => true), array('alignment' => 'center', 'spacing' => 130)
        );

        $section->addLine(
            array(
                'width'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(12),
                'height'      => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(0),
                'positioning' => 'absolute',
            )
        );

        $section->addText( 
            'Date: '.$date,
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
            timeConvert($circ['time']),
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'left')
        );
        $textrun->addText( 
            ' on ',
            array('name' => 'Times New Roman', 'size' => 12),
            array('alignment' => 'left')
        );
        $date = dateConvert($circ['m_date']);
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
        
        $html = nl2li( $circ['agenda']);
        
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

        $section->addTextBreak(2);

        $section->addText( 
            'Venue: '.$circ['venue'],
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'left')
        );

        $section->addText( 
            'Date: '.$date,
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'left')
        );

        $time = timeConvert($circ['time']);

        $section->addText( 
            'Time: '.$time,
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'left')
        );
        
        $section->addTextBreak(3);

        $section->addText(
            'HOD’s Signature',
            array('name' => 'Times New Roman', 'size' => 12, 'bold' => true),
            array('alignment' => 'right', 'spaceBefore' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(15))
        );
          
        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        echo $objWriter->save('php://output');
    }

    function get_circular_details($con)
    {
            $stmt = $con->prepare("SELECT * FROM `circulars` WHERE `c_id` = ?");
            $stmt->bind_param("i",$_POST['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return $row;
    }

    function get_meet_details($con)
    {
            $stmt = $con->prepare("SELECT * FROM `meetings` WHERE `m_id` = ?");
            if(isset($_POST['type']) && $_POST['type'] === "action")
                $stmt->bind_param("i",$_POST['id']);
            else
                $stmt->bind_param("i",$_POST['id']);

            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return $row;
    }

    function get_attendees($con)
    {
        $stmt = $con->prepare("SELECT `fac_name` FROM `attendees` WHERE `m_id` = ?");
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function get_action_details($con)
    {
        $stmt = $con->prepare("SELECT * FROM `actions` WHERE `m_id` = ?");
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 0)
            return false;
        else
            return $result->fetch_assoc();
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

    function nl2li($text)
    {
        $tmp_arr1= explode("\n\n",$text);
        $final_arr=array();
        // now $tmp_arr1 has n number of sections;
        foreach($tmp_arr1 as $section)
        {
            $final_arr[]= explode("\n",$section);
        }

        $html="";
        foreach($final_arr as $section)
        {
            $html.="<ul><li>";
            $html.=implode("</li><li>",$section);
            $html.="</li></ul>";
        }

        return $html;
    }
?>