<?php

session_start();

/*
 * --------------------------------------------------------------------
 * Event Admin Controller
 * 
 * 
 * Includes
 * Loggin In/Out
 * Event Admin
 * Default
 * 
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

//////////////////////////////////////////////////////////////////////////
// Arrays Used Across Entire Controller
//////////////////////////////////////////////////////////////////////////
$con_info = getCurrentYearInfo();
$user_info = getUserInfo();

/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                logging in / logging out                             |
 *                                                                     |
 * --------------------------------------------------------------------
 */

/*
 * responds to request to login and sends login form
 */
if (isset($_GET['loginform'])) {
    include '/home/simpleco/demo2/app/pages_eventadmin/login.inc.html.php';
    exit();
}

/*
 * responds to login form data
 */
if (isset($_GET['loginformdata'])){
    
    // For each previous failed login attempt, the wait time until another
    // attempt can be made doubles.  The wait time begins with 2 seconds.
    if (isUserInLoginWaitingPeriod($_POST['email']) == TRUE) {
        $title = "Too Many Unsuccessful Login Attempts";
        $longdesc = "For each failed login attempt, the wait time until another
            attempt can be made doubles.  Please wait a moment and try again or
            use the password recovery tool.";
        include '/home/simpleco/demo2/app/pages_eventadmin/confirmation.inc.html.php';
        exit();
    }
    
    if (userIsLoggedIn()) {
        logevent($user_info['id'], NULL, 'good login');
        
        // reset the failed_logins field
        recordGoodLogin($user_info['id']);
        
        // return to index.php
        header("Location: .");
    }
    else {
        logevent($user_info['id'], NULL, 'bad login');
        
        // add to the failed_logins field
        recordBadLogin($user_info['id']);
        
        $title = 'Unauthorized User';
        $longdesc = "That email and password combination was not found.";
        include '/home/simpleco/demo2/app/pages_eventadmin/confirmation.inc.html.php';
        exit();
    }
}

/*
 * responds to logout attempt
 */
if (isset($_GET['logout'])) {
    logevent($user_info['id'], NULL, 'logout');
    
    unset($_SESSION['loggedIn']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    header("Location: index.php");
    exit();
}

/*
 * check to see if session is set
 */

if (!isset($_SESSION['loggedIn'])) {
    $title = 'Unauthorized User';
    $longdesc = "You need to log in as a user with appropriate credentials to view this part of the site.";
    include '/home/simpleco/demo2/app/pages_eventadmin/confirmation.inc.html.php';
    exit ();
}

/*
 * checks to see if user is logged in
 */
if (!userHasRole(3)) {
    $title = 'Unauthorized User';
    $longdesc = "You do not have permission to access this part of the site.";
    include '/home/simpleco/demo2/app/pages_eventadmin/confirmation.inc.html.php';
        exit();
}

/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                event admin                                          |
 *                                                                     |
 * --------------------------------------------------------------------
 */

/*
 * responds to request to view all events
 */
if (isset($_GET['view_events'])) {
    
    // create an array of search fields
    $event_columns = array();
    $sql = "SELECT column_name FROM information_schema.columns 
        WHERE table_name='events' AND table_schema!='information_schema'";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_columns = $s->fetchall();

    // get info for all events
    $events = array();
    $sql = "SELECT * FROM events ORDER BY start ASC";
    $s = $pdo->prepare($sql);
    $s->execute();
    $events = $s->fetchall();
    
    include '/home/simpleco/demo2/app/pages_eventadmin/view_events.inc.html.php';
    exit();
}

/*
 * responds to user search request and displays results
 */
if (isset($_POST['action']) and $_POST['action'] == 'search_events') {

    // returns the full event list if no criteria are selected
    if ($_POST['searchby'] == '') {
        header("Location: ." . '?view_events');
        exit();
    }
    
    // search events with submitted criteria
    $search_result = array();
    $select = 'SELECT * FROM ';
    $from = 'events ';
    $where = 'WHERE ' . $_POST['searchby'] . ' ';
    $like = 'LIKE' . '"%' . $_POST['search_text'] . '%"';
    $sql = $select . $from . $where . $like;
    $s = $pdo->prepare($sql);
    $s->execute();
    $search_result = $s->fetchall();

    // saving submitted search criteria
    $searched_by = '';
    $searched_text = '';
    $searched_by = $_POST['searchby'];
    $searched_text = $_POST['search_text'];
    
    // create an array of search fields
    $event_columns = array();
    $sql = "SELECT column_name FROM information_schema.columns 
        WHERE table_name='events' AND table_schema!='information_schema'";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_columns = $s->fetchall();
    
    include '/home/simpleco/demo2/app/pages_eventadmin/view_event_search.inc.html.php';
    exit();
}

/*
 * responds to request to edit event
 */
if (isset($_POST['action']) and $_POST['action'] == 'edit_event') {
    
    $result = array();
    $result1 = array();
    $result2 = array();
    $result3 = array();
    $result4 = array();
    
    $sql = 'SELECT * FROM events WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $result = $s->fetch();
    
    $result1 = $pdo->query('SELECT id FROM years ORDER BY id ASC');
    foreach ($result1 as $row) {
        $years[] = array(
            'id' => $row['id']);
    }
    $result2 = $pdo->query('SELECT statusdescription FROM event_status ORDER BY
        statusdescription ASC');
    foreach ($result2 as $row) {
        $statuses[] = array(
            'statusdescription' => $row['statusdescription']);
    }
    $result3 = $pdo->query('SELECT id, first_name, last_name FROM users ORDER BY 
        last_name ASC');
    foreach ($result3 as $row) {
        $contacts[] = array(
            'id' => $row['id'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']);
    }
    $result4 = $pdo->query('SELECT event_type_desc FROM event_types
        ORDER BY event_type_desc ASC');
    foreach ($result4 as $row) {
        $types[] = array(
            'event_type_desc' => $row['event_type_desc']);
    }
    include '/home/simpleco/demo2/app/pages_eventadmin/edit_event.inc.html.php';
    exit();
}

/*
 * responds to request to edit an event's properties
 */
if (isset($_POST['action']) and $_POST['action'] == 'edit_events_properties') {
    $eventprops = array();
    $sql = 'SELECT eventpropertyID, property 
        FROM lookup_events_eventproperties 
        INNTER JOIN event_properties 
        ON eventpropertyID = id 
        WHERE eventID = :eventID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    $eventprops = $s->fetchall();

    $eventinfo = array();
    $sql = 'SELECT * FROM events WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $eventinfo = $s->fetch();
    
    $props = array();
    $result = $pdo->query('SELECT * FROM event_properties
        ORDER BY id ASC');
    foreach ($result as $row) {
    $props[] = array(
    'id' => $row['id'],
    'property' => $row['property']);
    }
    
    include '/home/simpleco/demo2/app/pages_eventadmin/edit_events_properties.inc.html.php';
    exit();
}

/*
 * responds to request to add an event property to an event
 */
if (isset($_POST['action']) and $_POST['action'] == 'add_events_property') {
    try {
        $sql = 'INSERT INTO lookup_events_eventproperties SET
        eventID             =   :eventID,
        eventpropertyID     =   :eventpropertyID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':eventID', $_POST['eventID']);
        $s->bindValue(':eventpropertyID', $_POST['eventpropertyID']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error adding event property to this event!';
        include '/home/simpleco/demo2/app/pages_eventadmin/error.inc.html.php';
        exit();
    }
    
    // get the user id and log the event
    $id = userID($_SESSION['email']);
    logevent($id, $_POST['eventID'], 'added property ' . $_POST['eventpropertyID']);
    
    $title = "Event Property Added to Event";
    $longdesc = 'You have successfully added an event property to that event.';
    include '/home/simpleco/demo2/app/pages_eventadmin/confirmation.inc.html.php';
    exit();
}

/*
 * responds to request to delete an event's property
 */
if (isset($_POST['action']) and $_POST['action'] == 'delete_events_property') {
    $sql = 'DELETE FROM lookup_events_eventproperties WHERE eventID = :eventID AND
        eventpropertyID = :eventpropertyID LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['eventID']);
    $s->bindValue(':eventpropertyID', $_POST['eventpropertyID']);
    $s->execute();
    
    logevent($user_info['id'], $_POST['eventID'], 'deleted property ' . $_POST['eventpropertyID']);
    
    $title = "Event Property Deleted for that Event";
    $longdesc = 'You have successfully deleted an event property for that event.';
    include '/home/simpleco/demo2/app/pages_eventadmin/confirmation.inc.html.php';
    exit();
}

/*
 * responds to request to update event
 */
if (isset($_POST['action']) and $_POST['action'] == 'update_event') {
    try {
        $sql = 'UPDATE events SET
            name                    =   :name,
            building                =   :building,
            room                    =   :room,
            start                   =   :start,
            end                     =   :end,
            type                    =   :type,
            year_id                 =   :year_id,
            contact                 =   :contact,
            maxusers                =   :maxusers,
            status                  =   :status,
            registration_required   =   :registration_required,
            shoutbox                =   :shoutbox,
            description             =   :description WHERE
            id                      =   :id 
            LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':building', $_POST['building']);
        $s->bindValue(':room', $_POST['room']);
        $s->bindValue(':start', $_POST['start']);
        $s->bindValue(':end', $_POST['end']);
        $s->bindValue(':type', $_POST['type']);
        $s->bindValue(':year_id', $_POST['year_id']);
        $s->bindValue(':contact', $_POST['contact']);
        $s->bindValue(':maxusers', $_POST['maxusers']);
        $s->bindValue(':status', $_POST['status']);
        $s->bindValue(':registration_required', $_POST['registration_required']);
        $s->bindValue(':description', $_POST['description']);
        $s->bindValue(':shoutbox', $_POST['shoutbox']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error updating event!';
        include '/home/simpleco/demo2/app/pages_eventadmin/error.inc.html.php';
        exit();
    }
    
    logevent($user_info['id'], $_POST['id'], 'updated');
    
    $title = "Event Updated";
    $longdesc = 'You have successfully updated an event.';
    include '/home/simpleco/demo2/app/pages_eventadmin/confirmation.inc.html.php';
    exit();
}

/*
 * responds to request to add an event
 */
if (isset($_GET['add_event'])) {
    
    $years = array();
    $statuses = array();
    $contacts = array();
    $types = array();
    
    $result = $pdo->query('SELECT id FROM years ORDER BY id ASC');
    foreach ($result as $row) {
        $years[] = array(
            'id' => $row['id']);
    }
    $result = $pdo->query('SELECT statusdescription FROM event_status ORDER BY
        statusdescription ASC');
    foreach ($result as $row) {
        $statuses[] = array(
            'statusdescription' => $row['statusdescription']);
    }
    $result = $pdo->query('SELECT id, first_name, last_name FROM users ORDER BY 
        last_name ASC');
    foreach ($result as $row) {
        $contacts[] = array(
            'id' => $row['id'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']);
    }
    $result = $pdo->query('SELECT event_type_desc FROM event_types
        ORDER BY event_type_desc ASC');
    foreach ($result as $row) {
        $types[] = array(
            'event_type_desc' => $row['event_type_desc']);
    }
    include '/home/simpleco/demo2/app/pages_eventadmin/add_event.inc.html.php';
    exit();
}

/*
 * responds to addevent form and adds event to database
 */
if (isset($_POST['action']) and $_POST['action'] == 'create_event') {
    try {
        $sql = 'INSERT INTO events SET
            name        =   :name,
            building    =   :building,
            room        =   :room,
            start       =   :start,
            end         =   :end,
            type        =   :type,
            year_id     =   :year_id,
            contact     =   :contact,
            maxusers    =   :maxusers,
            status      =   :status,
            registration_required   =   :registration_required,
            description =   :description';
        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':building', $_POST['building']);
        $s->bindValue(':room', $_POST['room']);
        $s->bindValue(':start', $_POST['start']);
        $s->bindValue(':end', $_POST['end']);
        $s->bindValue(':type', $_POST['type']);
        $s->bindValue(':year_id', $_POST['year_id']);
        $s->bindValue(':contact', $_POST['contact']);
        $s->bindValue(':maxusers', $_POST['maxusers']);
        $s->bindValue(':status', $_POST['status']);
        $s->bindValue(':registration_required', $_POST['registration_required']);
        $s->bindValue(':description', $_POST['description']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error adding event!';
        include '/home/simpleco/demo2/app/pages_eventadmin/error.inc.html.php';
        exit();
    }
    
    // gets the event id for the newly-created event
    $sql = 'SELECT id FROM events 
        WHERE name = :name 
            AND start = :start
            AND end = :end 
            AND contact = :contact
            AND description = :description';
    $s = $pdo->prepare($sql);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':start', $_POST['start']);
    $s->bindValue(':end', $_POST['end']);
    $s->bindValue(':contact', $_POST['contact']);
    $s->bindValue(':description', $_POST['description']);
    $s->execute();
    $event_id = $s->fetch();
    
    logevent($user_info['id'], $event_id['id'], 'created event');
    
    $title = "Event Added";
    $longdesc = 'You have successfully added an event.';
    include '/home/simpleco/demo2/app/pages_eventadmin/confirmation.inc.html.php';
    exit();
}

/*
 * responds to request to view event attendees
 */
if (isset($_POST['action']) and $_POST['action'] == 'view_event_attendees') {
    
    // gets users registered for this event
    $registered_users = array();
    $sql = 'SELECT id, first_name, last_name, email 
        FROM lookup_users_events 
        INNER JOIN users ON userID = id 
        WHERE eventID = :eventID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    $result = $s->fetchall();
    foreach ($result as $row) {
        $registered_users[] = array(
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'id' => $row['id']);
    }
    
    include '/home/simpleco/demo2/app/pages_eventadmin/view_event_attendees.inc.html.php';
    exit();
}

/*
 * responds to request to delete an event and returns confirmation page
 */
if (isset($_POST['action']) and $_POST['action'] == 'delete_event') {
    
    // get the event info
    $sql = 'SELECT * FROM events WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $event = $s->fetch();
    
    // gets users registered for this event
    $registered_users = array();
    if ($event['registration_required'] == 1) {
        $sql = 'SELECT id, first_name, last_name, email 
            FROM lookup_users_events 
            INNER JOIN users ON userID = id 
            WHERE eventID = :eventID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':eventID', $_POST['id']);
        $s->execute();
        $result = $s->fetchall();
        foreach ($result as $row) {
            $registered_users[] = array(
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'id' => $row['id']);
        }
    }
    
    // gets list of users with duty roster items for this event
    $duty_roster_for_event = array();
    $sql = 'SELECT users.first_name, duty_roster.description, users.last_name, 
            users.email
        FROM duty_roster 
        INNER JOIN users 
        ON duty_roster.userID = users.id 
        WHERE duty_roster.eventID = :eventID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    $result = $s->fetchall();
    foreach ($result as $row) {
        $duty_roster_for_event[] = array(
            'users.first_name' => $row['first_name'],
            'users.last_name' => $row['last_name'],
            'users.email' => $row['email'],
            'duty_roster.description' => $row['description']);
    }
    
    include '/home/simpleco/demo2/app/pages_eventadmin/delete_event_confirmation.inc.html.php';
    exit();
}

/*
 * responds to event deletion confirmation page
 */
if (isset($_POST['action']) and $_POST['action'] == 'yes_delete_event') {
    
    // de-registers users for the event
    $sql = 'DELETE FROM lookup_users_events 
        WHERE eventID       =   :eventID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    
    // removes duty roster items for this event
    $sql = 'DELETE FROM duty_roster 
        WHERE eventID       =   :eventID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    
    // delete the event
    $sql = 'DELETE FROM events
        WHERE id       =   :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    
    logevent($user_info['id'], $_POST['id'], 'deleted event');
    
    // display confirmation
    $title = "Event Deleted";
    $longdesc = 'You have successfully deleted an event, de-registered users 
        from that event, and deleted all duty roster items for that event.';
    include '/home/simpleco/demo2/app/pages_eventadmin/confirmation.inc.html.php';
    exit();
}

/*
 * ----------------------------------------------------------------------------- DEFAULT
 */
header("Location: ." . '?view_events');
exit();

