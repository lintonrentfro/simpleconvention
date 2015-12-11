<?php

session_start();

/*
 * --------------------------------------------------------------------
 * Schedule (for displaying on big screens a the con) Controller
 * 
 * 
 * Includes
 * Schedule
 * --------------------------------------------------------------------
 */

/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                includes                                             |
 *                                                                     |
 * --------------------------------------------------------------------
 */

$siteroot = '/home/simpleco/';
require $siteroot . 'demo2/app/includes_php/magicquotes.inc.php';
require $siteroot . 'demo2/app/includes_php/encrypt.inc.php';
require $siteroot . 'demo2/app/includes_php/access.inc.php';
require $siteroot . 'demo2/app/includes_php/db.inc.php';
require $siteroot . 'demo2/app/includes_php/functions.inc.php';
require $siteroot . 'demo2/app/stripe-php/lib/Stripe.php';
$includemailer = $siteroot . 'demo2/app/PHPMailer_5.2.2/*.php';
foreach (glob($includemailer) as $filename)
{
    include $filename;
}

/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                schedule                                             |
 *                                                                     |
 * --------------------------------------------------------------------
 */

// get info for all events on schedule
$sql = 'SELECT * FROM events WHERE 
    status="on schedule - all clear" 
    ORDER BY start';
$s = $pdo->prepare($sql);
$s->execute();
$result = $s->fetchall();
foreach ($result as $row) {
    $events[] = array(
        'id' => $row['id'],
        'name' => $row['name'],
        'building' => $row['building'],
        'room' => $row['room'],
        'start' => $row['start'],
        'end' => $row['end'],
        'type' => $row['type'],
        'year_id' => $row['year_id'],
        'contact' => $row['contact'],
        'currentusers' => $row['currentusers'],
        'maxusers' => $row['maxusers'],
        'status' => $row['status'],
        'registration_required' => $row['registration_required'],
        'description' => $row['description'],);
    }


include '/home/simpleco/demo2/app/pages_schedule/default.inc.html.php';
exit();
