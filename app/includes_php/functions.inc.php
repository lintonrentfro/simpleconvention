<?php

/*
 * html($text)
 * htmlout($text)
 * seatsavailable($taken, $max)
 * makeyellowif($input, $test)
 * generateRandom32CharString($length = 32)
 * userID($email)
 * isUserInLoginWaitingPeriod($email)
 * logevent($userID, $eventID, $action)
 * getCurrentYearInfo()
 * getYears()
 * deleteBackup()
 * backupTable($table)
 * backupDatabase()
 * Zip($source, $destination)
 * shoutboxDisplay($eventID)
 * shoutboxForm($eventID)
 * shoutboxComment($eventID, $userID, $comment)
 * isIpBanned($ip)
 * forumsAvailable()
 * createEmailList($list)
 * deleteList()
 * user_info()
 * 
 */

function html($text) {
  return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function htmlout($text) {
  echo html($text);
}

function seatsavailable($taken, $max) {
    $available = $max - $taken;
    htmlout ($available);
}

function makeyellowif($input, $test) {
    if ($input == $test) {
        echo 'class="warning"';
    }
}

function generateRandom32CharString($length = 32) {    
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

function userID($email) {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    $sql = 'SELECT id FROM users WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $email);
    $s->execute();
    $row = $s->fetch();
    return $row['id'];
}

function isUserInLoginWaitingPeriod($email) {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    
    // get the user id
    $userID = userID($_POST['email']);
    
    // get the wait required since last bad login
    $sql = 'SELECT failed_logins FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $userID);
    $s->execute();
    $row = $s->fetch();
    $wait = pow(2,$row['failed_logins']);
    if ($row['failed_logins'] == 0) {
        $wait = 0;
    }
    
    // get the time of the last bad login
    $sql = 'SELECT time FROM log WHERE userID = :userID AND action = "bad login"
        ORDER BY time DESC LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $userID);
    $s->execute();
    $row = $s->fetch();
    $last_attempt = $row['time'];
    
    // get the current time
    $current_time = date('Y-m-d H:i:s');
    
    $end_of_wait = date('Y-m-d H:i:s', strtotime($last_attempt . ' + ' . $wait . 'seconds'));
    
    
    if ($end_of_wait > $current_time) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function logevent($userID, $eventID, $action) {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    $time = date('Y-m-d H:i:s');
    $userIP = $_SERVER['REMOTE_ADDR'];
    $sql = 'INSERT INTO log SET 
        userID = :userID,
        userIP = :userIP,
        eventID = :eventID,
        action = :action,
        time = :time';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $userID);
    $s->bindValue(':eventID', $eventID);
    $s->bindValue(':userIP', $userIP);
    $s->bindValue(':action', $action);
    $s->bindValue(':time', $time);
    $s->execute();
}

function getCurrentYearInfo() {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    $sql = 'SELECT *
    FROM static_con_info
    WHERE id =1';
    $s = $pdo->prepare($sql);
    $s->execute();
    $con_info = $s->fetch();
    return $con_info;
}

function getYears() {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    $sql = 'SELECT *  
    FROM years';
    $s = $pdo->prepare($sql);
    $s->execute();
    $years = $s->fetchall();
    return $years;
}

function deleteBackup() {
    $delete_these_files = '/home/simpleco/public_html/demo2/temp/*.*';
    foreach (glob($delete_these_files) as $filename) {
        unlink($filename);
    }
}

function backupTable($table){
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    
    // create the file
    $tablefile = '/home/simpleco/public_html/demo2/temp/' . $table . '.csv';
    $fh = fopen($tablefile, 'w');
    
    // get all rows for table
    $sql = 'SELECT *
    FROM ' . $table;
    $s = $pdo->prepare($sql);
    $s->execute();
    $result = $s->fetchAll(PDO::FETCH_ASSOC);
    
    // write the table rows to the file
    foreach ($result as $row) :
        $string = implode(",", $row) . "\n";
        fwrite($fh, $string);
    endforeach;
    fclose($fh);
}

function backupDatabase() {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    
    // Get a list ofall of the tables
    $sql = 'SHOW TABLES';
    $s = $pdo->prepare($sql);
    $s->execute();
    $table_names = $s->fetchAll(PDO::FETCH_ASSOC);

    // Create a CSV backup file for each table
    foreach ($table_names as $row) :
        backupTable($row['Tables_in_simpleco_demo3']);
    endforeach;
}

// source: http://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php
// example: Zip('/folder/to/compress/', './compressed.zip');
function Zip($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

function shoutboxDisplay($eventID) {
    
    // "echo shoutboxDisplay(7);" should return a complete table of comments in chronological order
    
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    
    // Get a list of all comments for this event
    $sql = '    SELECT * 
                FROM shoutbox
                LEFT JOIN users
                ON userID = users.id 
                WHERE eventID = :eventID
                ORDER BY time ASC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $eventID);
    $s->execute();
    $result = $s->fetchAll(PDO::FETCH_ASSOC);
    
    // return a basic html table of the comments
    $messages = '';
    foreach($result as $row):
        $messages .= '<p>' . $row['first_name'] . ' ' . $row['last_name'] . '<br>';
        $messages .= date("m/d/y g:i A", strtotime($row['time'])) . '<br>';
        $messages .= $row['comment'];
        $messages .= '</p><hr>';
    endforeach;
    return $messages;
}

function shoutboxForm($eventID) {
    
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    
    $sql = 'SELECT parentID FROM users WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_SESSION['email']);
    $s->execute();
    $child_check = $s->fetch();
    
    // check to see if user is logged in
    if (!isset($_SESSION['loggedIn'])) {
        session_start();
        if (!isset($_SESSION['loggedIn'])) {
            echo 'You must log in to add a comment.';
        }
    }
    if ($child_check['parentID'] == 0) {
        // return a basic html form to add a comment
        $form = '<form action="?" method="post">
                    <textarea id="comment" name="comment" maxlength="300" rows="4"></textarea>
                    <input type="hidden" name="id" value="' . $eventID . '"><br>
                    <button class="btn btn-small" type="submit" value="create_comment" name="action" title="submit">submit</button>
                 </form>';
        return $form;
    }
}

function shoutboxComment($eventID, $userID, $comment) {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    
    $time = date('Y-m-d H:i:s');
    $userIP = $_SERVER['REMOTE_ADDR'];
    
    // add the comment to the shoutbox table
    $sql = 'INSERT INTO shoutbox 
            SET userID = :userID,
                eventID = :eventID,
                time = :time,
                userIP = :userIP,
                comment = :comment';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $userID);
    $s->bindValue(':eventID', $eventID);
    $s->bindValue(':userIP', $userIP);
    $s->bindValue(':time', $time);
    $s->bindValue(':comment', $comment);
    $s->execute();
}

function isIPBanned() {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $sql = 'SELECT COUNT(*) 
            FROM log 
            WHERE userIP = :userIP  AND action = :action LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userIP', $ip);
    $s->bindValue(':action', 'ip banned');
    $s->execute();
    $banned = $s->fetch();
    
    
    if ($banned['COUNT(*)'] > 0) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function forumsAvailable() {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    $sql = 'SELECT forums_on FROM static_con_info LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->execute();
    $current_year = $s->fetch();
    if ($current_year['forums_on'] == 0) {
        $title = "Forums are not available at this time.";
        $longdesc = "The forums are currently disabled.";
        include '/home/simpleco/demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }
}

function createEmailList($list){
    
    // takes an array of first_name, last_name, email and writes them to a file
    // in CSV format
    
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    
    // create the file
    $file = '/home/simpleco/public_html/demo2/temp/email_list.csv';
    $fh = fopen($file, 'w');

    // write the array rows to the file
    foreach ($list as $row) :
        $string = implode(",", $row) . "\n";
        fwrite($fh, $string);
    endforeach;
    fclose($fh);
}

function deleteList() {
    $delete_these_files = '/home/simpleco/public_html/demo2/temp/*.*';
    foreach (glob($delete_these_files) as $filename) {
        unlink($filename);
    }
}

function getUserInfo() {
    
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    
    if (isset($_SESSION['loggedIn'])) {
        $sql = '
            SELECT *
            FROM users
            WHERE email = :email
            LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $_SESSION['email']);
        $s->execute();
        $user_info = $s->fetch(PDO::FETCH_ASSOC);
        return $user_info;
    }
}