<?php

session_start();

//////////////////////////////////////////////////////////////////////////
// Admin Controller
// 
// Includes
// Array Used Across Entire Controller
// Loggin In/Out
// Users
// Events
// Event Types
// Duty Roster
// Event Properties
// Event Overflow
// Static Convention Info
// Year Progression
// Advance to next year (NOT TESTED)
// Badges
// Logs
// Backup
// Advertising
// Store
// static HTML pages
// Forum
// Guests
// Email Features
// Dashboard (homepage)
//////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////
// includes
//////////////////////////////////////////////////////////////////////////
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

//////////////////////////////////////////////////////////////////////////
//                logging in/out
//////////////////////////////////////////////////////////////////////////

// sends login form
if (isset($_GET['loginform'])) {
    include $siteroot . 'demo2/app/pages_admin/login.inc.html.php';
    exit();
}

// responds to login form data
if (isset($_GET['loginformdata'])){
    
    // check to see if userIP is banned
    if (isIPBanned() == TRUE) {
        $title = "Your IP Address Has Been Banned";
        $longdesc = "Your ip was banned from logging into this website,
            registering a new account, or recovering a password.  If you feel
            this is a mistake, please contact the convention staff for 
            assistance.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // For each previous failed login attempt, the wait time until another
    // attempt can be made doubles.  The wait time begins with 2 seconds.
    if (isUserInLoginWaitingPeriod($_POST['email']) == TRUE) {
        $title = "Too Many Unsuccessful Login Attempts";
        $longdesc = "For each failed login attempt, the wait time until another
            attempt can be made doubles.  Please wait a moment and try again or
            use the password recovery tool.";
        include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
        exit();
    }
    
    if (userIsLoggedIn()) {
        
        // get the user id and log the event
        logevent($user_info['id'], NULL, 'login');
        
        // reset the failed_logins field
        recordGoodLogin($id);
        
        header("Location: .");
    }
    else {
        
        // get the user id and log the event
        logevent($user_info['id'], NULL, 'bad login');
        
        // add to the failed_logins field
        recordBadLogin($id);
        
        $title = 'Unauthorized User';
        $longdesc = "Email and password combination not found.";
        include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
        exit();
    }
}

// make sure user is logged in
if (!isset($_SESSION['loggedIn'])) {
    if (!isset($_SESSION['loggedIn'])) {
        $title = 'Unauthorized User';
        $longdesc = "You need to log in to view this part of the site.";
        include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
        exit ();
    }
}

// make sure user has role 2
if (!userHasRole(2)) {
    $title = 'Unauthorized User';
    $longdesc = "You do not have permission to access this part of the site.";
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// logs user out
if (isset($_GET['logout'])) {
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'logout');
    
    unset($_SESSION['loggedIn']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    $longdesc = "You are now logged out.";
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// users
//////////////////////////////////////////////////////////////////////////

// show all users
if (isset($_GET['view_users'])) {
    
    // get a list of all users
    try {
        $result = $pdo->query('SELECT * FROM users ORDER BY last_name ASC');
    }
    catch (PDOException $e) {
        $error = 'Error fetching users from the database!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    foreach ($result as $row) {
        $users[] = array(
            'id' => $row['id'], 
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'company' => $row['company'],
            'email' => $row['email'],
            'address1' => $row['address1'],
            'address2' => $row['address2'],
            'city' => $row['city'],
            'state' => $row['state'],
            'zip' => $row['zip'],
            'home' => $row['home'],
            'work' => $row['work'],
            'cell' => $row['cell']);
        }
        
    // create an array of search fields
    $sql = "SELECT column_name FROM information_schema.columns WHERE table_name='users'";
    $s = $pdo->prepare($sql);
    $s->execute();
    $user_columns = $s->fetchall();
    
    // get a list of all user roles
    $sql = 'SELECT * FROM user_roles';
    $s = $pdo->prepare($sql);
    $s->execute();
    $user_roles = $s->fetchall();
    
    // get guest list
    $sql = 'SELECT userID FROM guests';
    $result = $pdo->query($sql);
    while ($row = $result->fetch()) {
        $guests[] = $row['userID'];
    }
        
    include $siteroot . 'demo2/app/pages_admin/view_users.inc.html.php';
    exit();
}

// show all users with selected role
if (isset($_POST['action']) and $_POST['action'] == 'search_user_role') {
    
    // returns the full user list if no criteria are selected
    if ($_POST['searchby'] == '') {
        header("Location: ." . '?view_users');
        exit();
    }
    
    // get a list of all user roles
    $sql = 'SELECT * FROM user_roles';
    $s = $pdo->prepare($sql);
    $s->execute();
    $user_roles = $s->fetchall();
    
    // get guest list
    $sql = 'SELECT userID FROM guests';
    $result = $pdo->query($sql);
    while ($row = $result->fetch()) {
        $guests[] = $row['userID'];
    }
    
    // create an array of search fields
    $sql = "SELECT column_name FROM information_schema.columns WHERE table_name='users'";
    $s = $pdo->prepare($sql);
    $s->execute();
    $user_columns = $s->fetchall();
    
    // get all users with selected role
    $sql = 'SELECT first_name, last_name, email, cell, users.id 
        FROM lookup_users_userroles 
        INNER JOIN users 
        ON userID = id 
        WHERE roleID = :roleID
        ORDER BY last_name ASC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':roleID', $_POST['searchby']);
    $s->execute();
    $search_result = $s->fetchall();
    
    include $siteroot . 'demo2/app/pages_admin/view_user_search.inc.html.php';
    exit();
}

// show user search results
if (isset($_POST['action']) and $_POST['action'] == 'search_users') {

    // returns the full user list if no criteria are selected
    if ($_POST['searchby'] == '') {
        header("Location: ." . '?view_users');
        exit();
    }
    
    // search users with submitted criteria
    $select = 'SELECT * FROM ';
    $from = 'users ';
    $where = 'WHERE ' . $_POST['searchby'] . ' ';
    $like = 'LIKE' . '"%' . $_POST['search_text'] . '%"';
    $sql = $select . $from . $where . $like . ' ORDER BY last_name ASC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $search_result = $s->fetchall();
    
    // get guest list
    $sql = 'SELECT userID FROM guests';
    $result = $pdo->query($sql);
    while ($row = $result->fetch()) {
        $guests[] = $row['userID'];
    }

    // saving submitted search criteria
    $searched_by = $_POST['searchby'];
    $searched_text = $_POST['search_text'];
    
    // create an array of search fields
    $sql = "SELECT column_name FROM information_schema.columns WHERE table_name='users'";
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $user_columns = $s->fetchall();
    
    // get a list of all user roles
    $sql = 'SELECT * FROM user_roles';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $user_roles = $s->fetchall();
    
    include $siteroot . 'demo2/app/pages_admin/view_user_search.inc.html.php';
    exit();
}

// show edit user form
if (isset($_GET['action']) and $_GET['action'] == 'edit_user') {
    
    // gets if the user has paid for a badge or not
    $sql = 'SELECT COUNT(*) FROM lookup_users_years
        WHERE userID = :userID AND yearID = :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['id']);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    $paidforbadge = $s->fetch();
    
    include $siteroot . 'demo2/app/pages_admin/edit_user.inc.html.php';
    exit();
}

// edit user roles
if (isset($_GET['action']) and $_GET['action'] == 'edit_user_roles') {
    
    // gets all roles currently assigned to that user
    try {
        $sql = 'SELECT roleID, role  
            FROM lookup_users_userroles 
            INNER JOIN user_roles 
            ON roleID = id 
            WHERE userID = :userID
            ORDER BY roleID ASC';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $_GET['id']);
        $s->execute();
        $result1 = $s->fetchall();
    }
    catch (PDOException $e) {
        $error = 'Error retrieving user roles!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    
    // gets list of all possible user roles
    $result = $pdo->query('SELECT * FROM user_roles ORDER BY id ASC');
    foreach ($result as $row) {
        $roles[] = array(
            'id' => $row['id'],
            'role' => $row['role']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/edit_user_roles.inc.html.php';
    exit();
}

// add role to user
if (isset($_POST['action']) and $_POST['action'] == 'create_new_users_role') {
    
    // check to see if user already has that role
    $sql = 'SELECT COUNT(*) FROM lookup_users_userroles WHERE 
    userID = :userID AND roleID = :roleID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_POST['userID']);
    $s->bindValue(':roleID', $_POST['roleID']);
    $s->execute();
    $row = $s->fetch();
    if ($row[0] > 0) {
        $title = "Error";
        $longdesc = 'That user already has that role.';
        include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
        exit();
    }

    // create new user|user role
    try {
        $sql = 'INSERT INTO lookup_users_userroles SET
        userID  =   :userID,
        roleID   =   :roleID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $_POST['userID']);
        $s->bindValue(':roleID', $_POST['roleID']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error adding role for user!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'added user role ' . $_POST['roleID']. ' to user ' . $_POST['userID']);
    
    $title = "Role Added for User";
    $longdesc = 'You have successfully added a role for that user.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// delete role from user
if (isset($_POST['action']) and $_POST['action'] == 'delete_users_role') {
    $sql = 'DELETE FROM lookup_users_userroles WHERE userID = :userID AND
        roleID = :roleID LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_POST['userID']);
    $s->bindValue(':roleID', $_POST['roleID']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'deleted user role ' . $_POST['roleID']. ' from user ' . $_POST['userID']);
    
    $title = "Role Deleted from User";
    $longdesc = 'You have successfully deleted a role for that user.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// confirm request to delete user
if (isset($_GET['action']) and $_GET['action'] == 'delete_user') {
    
    // loads confirmation page
    include $siteroot . 'demo2/app/pages_admin/confirm_user_delete.inc.html.php';
    exit();
}

// delete user
if (isset($_POST['action']) and $_POST['action'] == 'delete_user_confirmed') {
    
    // deletes user record
    try {
        $sql = 'DELETE FROM users WHERE id = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error deleting user from the users table!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    
    // deletes lookup_users_userroles entries for that user
    try {
        $sql = 'DELETE FROM lookup_users_userroles WHERE userID = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->execute();
    }
    catch (PDOException $e) {
    $error = 'Error deleting the roles assinged to that user.';
    include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
    exit();
    }
    
    // de-registers user from all events
    $sql = 'DELETE FROM lookup_users_events 
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_POST['id']);
    $s->execute();
    
    // removes all duty roster items for this user
    $sql = 'DELETE FROM duty_roster 
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_POST['id']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'deleted user ' . $_POST['id']);
    
    $title = "User Record Deleted";
    $longdesc = 'You have successfully deleted: ' . 
            $userinfo['first_name'] . ' ' . $userinfo['last_name'] . '.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// show update user form
if (isset($_POST['action']) and $_POST['action'] == 'update_user') {
    try {
    $sql = 'UPDATE users SET
        first_name = :first_name,
        last_name = :last_name,
        company = :company,
        email = :email,
        address1 = :address1,
        address2 = :address2,
        city = :city,
        state = :state,
        zip = :zip,
        home = :home,
        work = :work,
        cell = :cell WHERE
        id = :id LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':first_name', $_POST['first_name']);
    $s->bindValue(':last_name', $_POST['last_name']);
    $s->bindValue(':company', $_POST['company']);
    $s->bindValue(':email', $_POST['email']);
    $s->bindValue(':address1', $_POST['address1']);
    $s->bindValue(':address2', $_POST['address2']);
    $s->bindValue(':city', $_POST['city']);
    $s->bindValue(':state', $_POST['state']);
    $s->bindValue(':zip', $_POST['zip']);
    $s->bindValue(':home', $_POST['home']);
    $s->bindValue(':work', $_POST['work']);
    $s->bindValue(':cell', $_POST['cell']);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error updating user from the users table!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'updated user ' . $_POST['id']);
    
    $title = "User Record Updated";
    $longdesc = 'You have successfully updated: ' . 
            $_POST['first_name'] . ' ' . $_POST['last_name'] . '.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// show add user form
if (isset($_GET['add_user'])) {
    include $siteroot . 'demo2/app/pages_admin/add_user.inc.html.php';
    exit();
}

// add user
if (isset($_POST['action']) and $_POST['action'] == 'create_new_user') {
    
    // checks to see if email entered isn't already in use by a registered account
    $sql = 'SELECT COUNT(*) FROM users WHERE 
    email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->execute();
    $row = $s->fetch();
    if ($row[0] > 0) {
        $title = "Email In Use";
        $longdesc = 'The email address you entered already belongs to another user.';
        include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
        exit();
    }
    
    try {
        $sql = 'INSERT INTO users SET
        first_name  =   :first_name,
        last_name   =   :last_name,
        company     =   :company,
        email       =   :email,
        address1    =   :address1,
        address2    =   :address2,
        city        =   :city,
        state       =   :state,
        zip         =   :zip,
        home        =   :home,
        work        =   :work,
        cell        =   :cell';
        $s = $pdo->prepare($sql);
        $s->bindValue(':first_name', $_POST['firstname']);
        $s->bindValue(':last_name', $_POST['lastname']);
        $s->bindValue(':company', $_POST['company']);
        $s->bindValue(':email', $_POST['email']);
        $s->bindValue(':address1', $_POST['address1']);
        $s->bindValue(':address2', $_POST['address2']);
        $s->bindValue(':city', $_POST['city']);
        $s->bindValue(':state', $_POST['state']);
        $s->bindValue(':zip', $_POST['zip']);
        $s->bindValue(':home', $_POST['home']);
        $s->bindValue(':work', $_POST['work']);
        $s->bindValue(':cell', $_POST['cell']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error adding user to the users table!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }

    try {
        $salt = generateSalt($_POST['email']);
        $password = generateHash($salt, $_POST['password']);
        $sql = 'UPDATE users SET
            password = :password
            WHERE email = :email';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $_POST['email']);
        $s->bindValue(':password', $password);
        $s->execute();
    }
    catch (PDOException $e){
        $error = 'Error updating new user record with password!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    try {
        
        // get the user id of the newly-created user account
        $new_user_id = userID($_POST['email']);
        
        // assign the new user a role of 1
        $sql = 'INSERT INTO lookup_users_userroles (userID, roleID) VALUES
            (:id, 1)';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $new_user_id);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error assinging default role of 1 for new user!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'created user ' . $new_user_id);
    
    $title = "User Added";
    $longdesc = 'You have successfully added: ' . 
            $_POST['firstname'] . ' ' . $_POST['lastname'] . '.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// show user's events
if (isset($_GET['action']) and $_GET['action'] == 'view_registered_events_for_user') {

    // gets events for which the user is registered
    $sql = 'SELECT id, name, start, end, building, room, currentusers, maxusers, type  
        FROM lookup_users_events 
        INNER JOIN events ON eventID = id 
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['id']);
    $s->execute();
    $result = $s->fetchall();
    foreach ($result as $row) {
        $events_registered[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'building' => $row['building'],
            'room' => $row['room'],
            'start' => $row['start'],
            'end' => $row['end'],
            'type' => $row['type'],
            'currentusers' => $row['currentusers'],
            'maxusers' => $row['maxusers']);
    }
    
    // create an array of search fields
    $sql = "SELECT column_name FROM information_schema.columns WHERE table_name='users'";
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_GET['id']);
    $s->execute();
    $user_columns = $s->fetchall();
    
    // get a list of all user roles
    $sql = 'SELECT * FROM user_roles';
    $s = $pdo->prepare($sql);
    $s->execute();
    $user_roles = $s->fetchall();
    
    // gets number of events for which user is registered
    $sql = 'SELECT COUNT(*) FROM lookup_users_events
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['id']);
    $s->execute();
    $eventsregisterednumber = $s->fetch();
    
    include $siteroot . 'demo2/app/pages_admin/view_users_events.inc.html.php';
    exit();
}

// give user free badge
if (isset($_POST['action']) and $_POST['action'] == 'give_badge') {

    // record that the user paid in lookup_users_years
    $sql = 'INSERT INTO lookup_users_years SET 
    userID        =   :userID,
    yearID       =   :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_POST['id']);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    
    // log the action
    logevent($user_info['id'], NULL, 'gave user id ' . $_POST['id'] . ' free badge');
    
    // log that the user's badge is a free one so that a list of currently valid
    // free badges can be pulled
    logevent($_POST['id'], NULL, 'badge is free');
    
    // display confirmation
    $title = "User Given Badge";
    $longdesc = 'You have successfully given that user a badge.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// make user a guest
if (isset($_GET['action']) and $_GET['action'] == 'make_guest') {
    
    // make user a guest
    $sql = 'INSERT INTO guests SET
        userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['id']);
    $s->execute();
    
    header("Location: ." . '?view_users');
    exit();
}

// de-guest user
if (isset($_GET['action']) and $_GET['action'] == 'unmake_guest') {
    
    // de-guest user
    $sql = 'DELETE FROM guests WHERE
        userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['id']);
    $s->execute();
    
    // remove them from events they were a guest on
    $sql = 'DELETE FROM lookup_guests_events WHERE
        guestID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['id']);
    $s->execute();
    
    header("Location: ." . '?view_users');
    exit();
}

//////////////////////////////////////////////////////////////////////////
// events
//////////////////////////////////////////////////////////////////////////

// show all events
if (isset($_GET['view_events'])) {
    
    // create an array of search fields
    $sql = "SELECT column_name FROM information_schema.columns 
        WHERE table_name='events' AND table_schema!='information_schema'";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_columns = $s->fetchall();
    
    // get list of event types
    $sql = "SELECT *
            FROM event_types";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_types = $s->fetchall();
    
    // get list of event properties
    $sql = "SELECT *
            FROM event_properties";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_properties = $s->fetchall();
    
    // get info for all events
    $sql = "SELECT events.id, name, building, room, start,
            end, type, year_id, contact, maxusers, status, description,  
            users.first_name, users.last_name, events.registration_required 
            FROM events
            INNER JOIN users 
            ON contact = users.id 
            ORDER BY start ASC";
    $s = $pdo->prepare($sql);
    $s->execute();
    $events = $s->fetchall();

    include $siteroot . 'demo2/app/pages_admin/view_events.inc.html.php';
    exit();
}

// show event search results
if (isset($_POST['action']) and $_POST['action'] == 'search_events') {

    // returns the full event list if no criteria are selected
    if ($_POST['searchby'] == '') {
        header("Location: ." . '?view_events');
        exit();
    }
    
    // get list of event types
    $sql = "SELECT *
            FROM event_types";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_types = $s->fetchall();
    
    // get list of event properties
    $sql = "SELECT *
            FROM event_properties";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_properties = $s->fetchall();
    
    // search events with submitted criteria
    $select = 'SELECT * FROM ';
    $from = 'events ';
    $where = 'WHERE ' . $_POST['searchby'] . ' ';
    $like = 'LIKE' . '"%' . $_POST['search_text'] . '%"';
    $sql = $select . $from . $where . $like;
    $s = $pdo->prepare($sql);
    $s->execute();
    $search_result = $s->fetchall();

    // saving submitted search criteria
    $searched_by = $_POST['searchby'];
    $searched_text = $_POST['search_text'];
    
    // create an array of search fields
    $sql = "SELECT column_name FROM information_schema.columns 
        WHERE table_name='events' AND table_schema!='information_schema'";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_columns = $s->fetchall();
    
    include $siteroot . 'demo2/app/pages_admin/view_event_search.inc.html.php';
    exit();
}

// show type of event
if (isset($_POST['action']) and $_POST['action'] == 'search_events_by_type') {
    
    // returns the full event list if no criteria are selected
    if ($_POST['searchbytype'] == '') {
        header("Location: ." . '?view_events');
        exit();
    }
    
    // get list of event types
    $sql = "SELECT *
            FROM event_types";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_types = $s->fetchall();
    
    // get list of event properties
    $sql = "SELECT *
            FROM event_properties";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_properties = $s->fetchall();
    
    // create an array of search fields
    $sql = "SELECT column_name FROM information_schema.columns 
        WHERE table_name='events' AND table_schema!='information_schema'";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_columns = $s->fetchall();
    
    // search events with submitted criteria
    $sql = 'SELECT * FROM events WHERE type = :type';
    $s = $pdo->prepare($sql);
    $s->bindValue(':type', $_POST['searchbytype']);
    $s->execute();
    $search_result = $s->fetchall();

    include $siteroot . 'demo2/app/pages_admin/view_event_search.inc.html.php';
    exit();
    
}

// show event's with property
if (isset($_POST['action']) and $_POST['action'] == 'search_events_by_property') {

    // returns the full event list if no criteria are selected
    if ($_POST['eventpropertyID'] == '') {
        header("Location: ." . '?view_events');
        exit();
    }
    
    // create an array of search fields
    $sql = "SELECT column_name FROM information_schema.columns 
        WHERE table_name='events' AND table_schema!='information_schema'";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_columns = $s->fetchall();
    
    // get list of event types
    $sql = "SELECT *
            FROM event_types";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_types = $s->fetchall();
    
    // get list of event properties
    $sql = "SELECT *
            FROM event_properties";
    $s = $pdo->prepare($sql);
    $s->execute();
    $event_properties = $s->fetchall();
    
    // search events with submitted criteria
    $sql = 'SELECT * 
        FROM lookup_events_eventproperties
        INNER JOIN events
        ON eventID = id
        WHERE eventpropertyID = :eventpropertyID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventpropertyID', $_POST['eventpropertyID']);
    $s->execute();
    $search_result = $s->fetchall();
    
    include $siteroot . 'demo2/app/pages_admin/view_event_search.inc.html.php';
    exit();
}

// show edit event form
if (isset($_POST['action']) and $_POST['action'] == 'edit_event') {
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
    include $siteroot . 'demo2/app/pages_admin/edit_event.inc.html.php';
    exit();
}

// show edit event property form
if (isset($_POST['action']) and $_POST['action'] == 'edit_events_properties') {
    $sql = 'SELECT eventpropertyID, property 
        FROM lookup_events_eventproperties 
        INNTER JOIN event_properties 
        ON eventpropertyID = id 
        WHERE eventID = :eventID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    $eventprops = $s->fetchall();

    $sql = 'SELECT * FROM events WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $eventinfo = $s->fetch();
    
    $result = $pdo->query('SELECT * FROM event_properties
        ORDER BY id ASC');
    foreach ($result as $row) {
    $props[] = array(
    'id' => $row['id'],
    'property' => $row['property']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/edit_events_properties.inc.html.php';
    exit();
}

// add event property to event
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
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    
    // get the user id and log the event
    logevent($user_info['id'], $_POST['eventID'], 'added property ' . $_POST['eventpropertyID']);
    
    $title = "Event Property Added to Event";
    $longdesc = 'You have successfully added an event property to that event.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// delete event property to event
if (isset($_POST['action']) and $_POST['action'] == 'delete_events_property') {
    $sql = 'DELETE FROM lookup_events_eventproperties WHERE eventID = :eventID AND
        eventpropertyID = :eventpropertyID LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['eventID']);
    $s->bindValue(':eventpropertyID', $_POST['eventpropertyID']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], $_POST['eventID'], 'deleted property ' . $_POST['eventpropertyID']);
    
    $title = "Event Property Deleted for that Event";
    $longdesc = 'You have successfully deleted an event property for that event.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// update event
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
            contact_email_displayed =   :contact_email_displayed, 
            maxusers                =   :maxusers,
            status                  =   :status,
            registration_required   =   :registration_required, 
            description             =   :description,
            shoutbox                =   :shoutbox,
            can_conflict            =   :can_conflict 
            WHERE id                =   :id 
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
        $s->bindValue(':contact_email_displayed', $_POST['contact_email_displayed']);
        $s->bindValue(':maxusers', $_POST['maxusers']);
        $s->bindValue(':status', $_POST['status']);
        $s->bindValue(':description', $_POST['description']);
        $s->bindValue(':registration_required', $_POST['registration_required']);
        $s->bindValue(':can_conflict', $_POST['can_conflict']);
        $s->bindValue(':shoutbox', $_POST['shoutbox']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error updating event!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    
    // get the user id and log the event
    logevent($user_info['id'], $_POST['id'], 'updated');
    
    $title = "Event Updated";
    $longdesc = 'You have successfully updated an event.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// show add event form
if (isset($_GET['add_event'])) {
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
    include $siteroot . 'demo2/app/pages_admin/add_event.inc.html.php';
    exit();
}

// add event
if (isset($_POST['action']) and $_POST['action'] == 'create_event') {
    
    //convert the user submitted start & end to datetime format for mysql
    $start = date_create($_POST['start']);
    $mysql_start = date_format($start, 'Y-m-d H:i:s');
    $end = date_create($_POST['end']);
    $mysql_end = date_format($end, 'Y-m-d H:i:s');
    
    try {
        $sql = 'INSERT INTO events SET
        name                    =   :name,
        building                =   :building,
        room                    =   :room,
        start                   =   :start,
        end                     =   :end,
        type                    =   :type,
        contact                 =   :contact,
        maxusers                =   :maxusers,
        status                  =   :status,
        registration_required   =   :registration_required, 
        year_id                 =   :year_id,
        description             =   :description';
        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':building', $_POST['building']);
        $s->bindValue(':room', $_POST['room']);
        $s->bindValue(':start', $mysql_start);
        $s->bindValue(':end', $mysql_end);
        $s->bindValue(':type', $_POST['type']);
        $s->bindValue(':year_id', $_POST['year_id']);
        $s->bindValue(':contact', $_POST['contact']);
        $s->bindValue(':maxusers', $_POST['maxusers']);
        $s->bindValue(':status', $_POST['status']);
        $s->bindValue(':registration_required', $_POST['registration_required']);
        $s->bindValue(':description', $_POST['description']);
        $s->bindValue(':year_id', $_POST['year_id']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error adding event!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
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
    
    // get the user id and log the event
    logevent($user_info['id'], $event_id['id'], 'created event');
    
    $title = "Event Added";
    $longdesc = 'You have successfully added an event.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// show event attendees
if (isset($_POST['action']) and $_POST['action'] == 'view_event_attendees') {
    
    // gets users registered for this event
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
    
    include $siteroot . 'demo2/app/pages_admin/view_event_attendees.inc.html.php';
    exit();
}

// confirm delete event
if (isset($_POST['action']) and $_POST['action'] == 'delete_event') {
    
    // get the event info
    $sql = 'SELECT * FROM events WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $event = $s->fetch();
    
    // gets users registered for this event
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
    
    // gets list of users with duty roster items for this event
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
    
    include $siteroot . 'demo2/app/pages_admin/delete_event_confirmation.inc.html.php';
    exit();
}

// delete event
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
    
    // get the user id and log the event
    logevent($user_info['id'], $_POST['id'], 'deleted event');
    
    // display confirmation
    $title = "Event Deleted";
    $longdesc = 'You have successfully deleted an event, de-registered users
        from that event, and deleted all duty roster items for that event.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// event types
//////////////////////////////////////////////////////////////////////////

// view event types
if (isset($_GET['view_event_types'])) {
    
    // gets a list of all current event types
    $result = $pdo->query('SELECT * FROM event_types 
            ORDER BY event_type_desc ASC');
    foreach ($result as $row) {
        $event_types[] = array(
            'event_type_id' => $row['event_type_id'], 
            'event_type_desc' => $row['event_type_desc']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/view_event_types.inc.html.php';
    exit();
}

// add event type
if (isset($_POST['action']) and $_POST['action'] == 'create_event_type') {
    $sql = 'INSERT INTO event_types SET
    event_type_desc = :event_type_desc';
    $s = $pdo->prepare($sql);
    $s->bindValue(':event_type_desc', $_POST['type']);
    $s->execute();
    
    // gets a list of all current event types
    $result = $pdo->query('SELECT * FROM event_types 
            ORDER BY event_type_desc ASC');
    foreach ($result as $row) {
        $event_types[] = array(
            'event_type_id' => $row['event_type_id'], 
            'event_type_desc' => $row['event_type_desc']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/view_event_types.inc.html.php';
    exit();
}

// show edit event type form
if (isset($_POST['action']) and $_POST['action'] == 'edit_event_type') {
    $sql = 'SELECT * FROM event_types WHERE event_type_id = :event_type_id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':event_type_id', $_POST['id']);
    $s->execute();
    $result = $s->fetch();
    
    include $siteroot . 'demo2/app/pages_admin/edit_event_type.inc.html.php';
    exit();
}

// update event type
if (isset($_POST['action']) and $_POST['action'] == 'update_event_type') {

    // get the original event property description from the posted id
    $sql = 'SELECT event_type_desc FROM event_types 
        WHERE event_type_id = :event_type_id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':event_type_id', $_POST['event_type_id']);
    $s->execute();
    $old_event_type = $s->fetch();

    // update the event property to the new user-submitted one
    $sql = 'UPDATE event_types 
        SET event_type_desc = :event_type_desc
        WHERE event_type_id = :event_type_id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':event_type_id', $_POST['event_type_id']);
    $s->bindValue(':event_type_desc', $_POST['event_type_desc']);
    $s->execute();
    
    // updates all events that had the old event type with the new one
    $sql = 'UPDATE events 
        SET type = :type
        WHERE type = :oldtype';
    $s = $pdo->prepare($sql);
    $s->bindValue(':type', $_POST['event_type_desc']);
    $s->bindValue(':oldtype', $old_event_type['event_type_desc']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'edited event type ' . $_POST['event_type_id']);
    
    // gets a list of all current event types
    $result = $pdo->query('SELECT * FROM event_types 
            ORDER BY event_type_desc ASC');
    foreach ($result as $row) {
        $event_types[] = array(
            'event_type_id' => $row['event_type_id'], 
            'event_type_desc' => $row['event_type_desc']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/view_event_types.inc.html.php';
    exit();
}

// delete event type
if (isset($_POST['action']) and $_POST['action'] == 'delete_event_type') {
    
    // gets a count of events with the event type to be deleted
    $sql = 'SELECT COUNT(*) 
        FROM events 
        WHERE type = :type';
    $s = $pdo->prepare($sql);
    $s->bindValue(':type', $_POST['event_type_desc']);
    $s->execute();
    $count = $s->fetch();
    
    // if any events have that event type, the user is instructed to change them
    // before deleting that event type
    if ($count['COUNT(*)'] > 0) {
        $title = "Error";
        $longdesc = 'Existing events have that event type. Please edit those
            events to have a different event type first.';
        include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
        exit();
    }
    
    // if no events have that event type, it is deleted
    if ($count['COUNT(*)'] < 1) {
        $sql = 'DELETE FROM event_types WHERE event_type_id = :event_type_id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':event_type_id', $_POST['id']);
        $s->execute();
    }
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'deleted event type ' . $_POST['id']);
    
    // gets a list of all current event types
    $result = $pdo->query('SELECT * FROM event_types 
            ORDER BY event_type_desc ASC');
    foreach ($result as $row) {
        $event_types[] = array(
            'event_type_id' => $row['event_type_id'], 
            'event_type_desc' => $row['event_type_desc']);
    }
 
    include $siteroot . 'demo2/app/pages_admin/view_event_types.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// duty roster
//////////////////////////////////////////////////////////////////////////

// show duty roster
if (isset($_GET['duty_roster'])) {
    
    // get list of all duty roster items
    try {
        $result = $pdo->query('SELECT duty_roster.id, duty_roster.userID, 
            duty_roster.eventID, duty_roster.start, duty_roster.end, 
            duty_roster.description, users.first_name, users.last_name, events.name  
            FROM duty_roster
            LEFT JOIN users 
                ON duty_roster.userID = users.id 
            LEFT JOIN events
                ON duty_roster.eventID = events.id
            ORDER BY duty_roster.start ASC');
    }
    catch (PDOException $e) {
        $error = 'Error fetching duty roster from the database!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    foreach ($result as $row) {
        $duty_roster[] = array(
            'duty_roster.id' => $row['id'], 
            'duty_roster.userID' => $row['userID'], 
            'duty_roster.eventID' => $row['eventID'],
            'duty_roster.start' => $row['start'],
            'duty_roster.end' => $row['end'],
            'duty_roster.description' => $row['description'],
            'users.first_name' => $row['first_name'],
            'events.name' => $row['name'],
            'users.last_name' => $row['last_name']);
    }
    
    // get list of all users with user_role 4 for duty roster search
    $result = $pdo->query('SELECT first_name, last_name, id 
        FROM lookup_users_userroles
        INNER JOIN users
        ON userID = id
        WHERE roleID = 4');
    foreach ($result as $row) {
        $user_list[] = array(
            'id' => $row['id'], 
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']);
    }
    
    // gets a list of all current event properties
    $result = $pdo->query('SELECT * 
        FROM event_properties 
        ORDER BY property');
    foreach ($result as $row) {
        $eventproperties[] = array(
            'id' => $row['id'], 
            'property' => $row['property']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/view_duty_roster.inc.html.php';
    exit();
}

// show add duty roster item to event form
if (isset($_POST['action']) and $_POST['action'] == 'add_duty_to_this_event') {
    
    // get list of all users with user_role 4 for duty roster search
    $result = $pdo->query('SELECT first_name, last_name, id 
        FROM lookup_users_userroles
        INNER JOIN users
        ON userID = id
        WHERE roleID = 4');
    foreach ($result as $row) {
        $user_list[] = array(
            'id' => $row['id'], 
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']);
    }
    
    // gets a list of all current event properties
    $result = $pdo->query('SELECT * 
        FROM event_properties 
        ORDER BY property');
    foreach ($result as $row) {
        $eventproperties[] = array(
            'id' => $row['id'], 
            'property' => $row['property']);
    }

    // gets event information
    $sql = 'SELECT * FROM events WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $eventinfo = $s->fetch();

    include $siteroot . 'demo2/app/pages_admin/add_duty_form.inc.html.php';
    exit();
}

// show add duty roster item form
if (isset($_GET['create_duty_item'])) {
    
    // get list of all users with user_role 4 for duty roster search
    $result = $pdo->query('SELECT first_name, last_name, id 
        FROM lookup_users_userroles
        INNER JOIN users
        ON userID = id
        WHERE roleID = 4');
    foreach ($result as $row) {
        $user_list[] = array(
            'id' => $row['id'], 
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/add_duty_form_no_event.inc.html.php';
    exit();
}

// create duty roster item
if (isset($_POST['action']) and $_POST['action'] == 'submit_duty') {
    
    // convert the user submitted start & end to datetime format for mysql
    $start = date_create($_POST['start']);
    $mysql_start = date_format($start, 'Y-m-d H:i:s');
    $end = date_create($_POST['end']);
    $mysql_end = date_format($end, 'Y-m-d H:i:s');
    
    // create the new duty roster item
    $sql = 'INSERT INTO duty_roster SET
    userID        =   :userID,
    eventID       =   :eventID,
    start         =   :start,
    end           =   :end,
    description   =   :description';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_POST['userID']);
    $s->bindValue(':eventID', $_POST['eventID']);
    $s->bindValue(':start', $mysql_start);
    $s->bindValue(':end', $mysql_end);
    $s->bindValue(':description', $_POST['description']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'created duty for user ' . $_POST['userID']);
    
    // display confirmation
    $title = "Duty Added to Duty Roster";
    $longdesc = 'You have successfully added a duty to the duty roster.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// show duty roster item edit form
if (isset($_POST['action']) and $_POST['action'] == 'edit_duty_roster_item') {
    
    // gets duty roster item info
    $sql = 'SELECT * FROM duty_roster WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $duty_roster_item = $s->fetch();
    
    // get list of all users with user_role 4 for duty roster search
    $result = $pdo->query('SELECT first_name, last_name, id 
        FROM lookup_users_userroles
        INNER JOIN users
        ON userID = id
        WHERE roleID = 4');
    foreach ($result as $row) {
        $user_list[] = array(
            'id' => $row['id'], 
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']);
    }
    
    // gets event information
    $sql = 'SELECT * FROM events WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $duty_roster_item['eventID']);
    $s->execute();
    $eventinfo = $s->fetch();
    
    include $siteroot . 'demo2/app/pages_admin/edit_duty_roster_item.inc.html.php';
    exit();
}

// delete duty roster item
if (isset($_POST['action']) and $_POST['action'] == 'delete_duty') {
    
    $sql = 'DELETE FROM duty_roster 
        WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    
    // display confirmation
    $title = "Duty Deleted";
    $longdesc = 'You have successfully deleted that duty roster item.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// show edit duty roster item form
if (isset($_POST['action']) and $_POST['action'] == 'update_duty') {
    try {
        $sql = 'UPDATE duty_roster SET
        userID         =   :userID,
        eventID        =   :eventID,
        start          =   :start,
        end            =   :end,
        description    =   :description WHERE
        id             =   :id LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $_POST['id']);
        $s->bindValue(':userID', $_POST['userID']);
        $s->bindValue(':eventID', $_POST['eventID']);
        $s->bindValue(':start', $_POST['start']);
        $s->bindValue(':end', $_POST['end']);
        $s->bindValue(':description', $_POST['description']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error updating duty roster item!';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    
    // get the user id and log the event
    logevent($user_info['id'], $_POST['eventID'], 'edited duty for user ' . $_POST['userID']);
    
    $title = "Duty Roster Item Updated";
    $longdesc = 'You have successfully updated a duty roster item.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// view duty roster for user
if (isset($_POST['action']) and $_POST['action'] == 'search_roster_by_user') {
    
    // returns all duty roster items if search field is left blank
    if ($_POST['duties_for_user'] == '') {
        header("Location: ." . '?duty_roster');
        exit();
    }
    
    // get list of all duty roster items for a single user
    $sql = 'SELECT duty_roster.id, duty_roster.userID, 
        duty_roster.eventID, duty_roster.start, duty_roster.end, 
        duty_roster.description, users.first_name, users.last_name, events.name  
        FROM duty_roster
        LEFT JOIN users 
            ON duty_roster.userID = users.id 
        LEFT JOIN events
            ON duty_roster.eventID = events.id
        WHERE duty_roster.userID = :ID
        ORDER BY duty_roster.start ASC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':ID', $_POST['duties_for_user']);
    $s->execute();
    $result = $s->fetchall();
    
    $duty_roster = array();

    foreach ($result as $row) {
        $duty_roster[] = array(
            'duty_roster.id' => $row['id'], 
            'duty_roster.userID' => $row['userID'], 
            'duty_roster.eventID' => $row['eventID'],
            'duty_roster.start' => $row['start'],
            'duty_roster.end' => $row['end'],
            'duty_roster.description' => $row['description'],
            'users.first_name' => $row['first_name'],
            'events.name' => $row['name'],
            'users.last_name' => $row['last_name']);
    }
    
    // get list of all users with user_role 4 for duty roster search
    $result = $pdo->query('SELECT first_name, last_name, id 
        FROM lookup_users_userroles
        INNER JOIN users
        ON userID = id
        WHERE roleID = 4');
    foreach ($result as $row) {
        $user_list[] = array(
            'id' => $row['id'], 
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']);
    }
    
    // gets a list of all current event properties
    $result = $pdo->query('SELECT * 
        FROM event_properties 
        ORDER BY property');
    foreach ($result as $row) {
        $eventproperties[] = array(
            'id' => $row['id'], 
            'property' => $row['property']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/view_duty_roster.inc.html.php';
    exit();
}

// view duty roster for event property
if (isset($_POST['action']) and $_POST['action'] == 'search_roster_by_event_property') {
    
    // returns all duty roster items if search field is left blank
    if ($_POST['duties_for_property'] == '') {
        header("Location: ." . '?duty_roster');
        exit();
    }
    
    // get list of all events having the selected event property
    $sql = 'SELECT eventID
        FROM lookup_events_eventproperties 
        WHERE eventpropertyID = :eventpropertyID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventpropertyID', $_POST['duties_for_property']);
    $s->execute();
    $result = $s->fetchall();
    
    // convert the resulting array to a list of event ids
    $list = array();
    foreach ($result as $row):
        $list[] = $row['eventID'];
    endforeach;
    
    // modify the list of event ids into the WHERE clause of a sql statement
    $where = 'WHERE duty_roster.eventID = "';
    $where = $where . implode('" OR duty_roster.eventID = "', $list);
    $where = $where . '"';
    
    // get all duty roster items for the event ids
    $sql = 'SELECT duty_roster.id, duty_roster.userID, 
        duty_roster.eventID, duty_roster.start, duty_roster.end, 
        duty_roster.description, users.first_name, users.last_name, events.name
        FROM duty_roster
        INNER JOIN users 
            ON duty_roster.userID = users.id
        INNER JOIN events
            ON duty_roster.eventID = events.id ' . $where . ' ORDER BY duty_roster.start ASC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $result = $s->fetchall();
    
    $duty_roster = array();
    
    // create array of duty roster items for output
    foreach ($result as $row) {
        $duty_roster[] = array(
            'duty_roster.id' => $row['id'], 
            'duty_roster.userID' => $row['userID'], 
            'duty_roster.eventID' => $row['eventID'],
            'duty_roster.start' => $row['start'],
            'duty_roster.end' => $row['end'],
            'duty_roster.description' => $row['description'],
            'users.first_name' => $row['first_name'],
            'events.name' => $row['name'],
            'users.last_name' => $row['last_name']);
    }
    
    // get list of all users with user_role 4 for duty roster search
    $result = $pdo->query('SELECT first_name, last_name, id 
        FROM lookup_users_userroles
        INNER JOIN users
        ON userID = id
        WHERE roleID = 4');
    foreach ($result as $row) {
        $user_list[] = array(
            'id' => $row['id'], 
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name']);
    }
    
    // gets a list of all current event properties
    $result = $pdo->query('SELECT * 
        FROM event_properties 
        ORDER BY property');
    foreach ($result as $row) {
        $eventproperties[] = array(
            'id' => $row['id'], 
            'property' => $row['property']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/view_duty_roster.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// event properties
//////////////////////////////////////////////////////////////////////////

// view event properties
if (isset($_GET['view_event_properties'])) {
    
    // gets a list of all current event properties
    $result = $pdo->query('SELECT * 
        FROM event_properties 
        ORDER BY property');
    foreach ($result as $row) {
        $eventproperties[] = array(
            'id' => $row['id'], 
            'property' => $row['property']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/view_event_properties.inc.html.php';
    exit();
}

// add new event property
if (isset($_POST['action']) and $_POST['action'] == 'create_event_property') {
    
    // add new event property
    $sql = 'INSERT INTO event_properties SET
    property = :property';
    $s = $pdo->prepare($sql);
    $s->bindValue(':property', $_POST['property']);
    $s->execute();
    
    // get id for newly-created event property
    $sql = 'SELECT id 
        FROM event_properties
        WHERE property = :property';
    $s = $pdo->prepare($sql);
    $s->bindValue(':property', $_POST['property']);
    $s->execute();
    $property_id = $s->fetch();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'created event property ' . $property_id['id']);
    
    // gets a list of all current event properties
    $result = $pdo->query('SELECT * 
        FROM event_properties 
        ORDER BY property');
    foreach ($result as $row) {
        $eventproperties[] = array(
            'id' => $row['id'], 
            'property' => $row['property']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/view_event_properties.inc.html.php';
    exit();
}

// show edit event property form
if (isset($_POST['action']) and $_POST['action'] == 'edit_event_property') {
    $sql = 'SELECT * FROM event_properties WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $result = $s->fetch();
    
    include $siteroot . 'demo2/app/pages_admin/edit_event_property.inc.html.php';
    exit();
}

// update event property
if (isset($_POST['action']) and $_POST['action'] == 'update_event_property') {

    // update the event property to the new user-submitted one
    $sql = 'UPDATE event_properties SET
        property =   :property WHERE
        id   =   :id LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':property', $_POST['property']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'edited event property ' . $_POST['id']);
    
    // gets a list of all current event properties
    $result = $pdo->query('SELECT * 
        FROM event_properties 
        ORDER BY property');
    foreach ($result as $row) {
        $eventproperties[] = array(
            'id' => $row['id'], 
            'property' => $row['property']);
    }
    
    include $siteroot . 'demo2/app/pages_admin/view_event_properties.inc.html.php';
    exit();
}

// delete event property
if (isset($_POST['action']) and $_POST['action'] == 'delete_event_property') {
    
    // deletes all lookup_events_eventproperties entries with that property id
    $sql = 'DELETE FROM lookup_events_eventproperties 
        WHERE eventpropertyID = :eventpropertyID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventpropertyID', $_POST['id']);
    $s->execute();
    
    $sql = 'DELETE FROM event_properties WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'deleted event property ' . $_POST['id']);
    
    // gets a list of all current event properties
    $result = $pdo->query('SELECT * 
        FROM event_properties 
        ORDER BY property');
    foreach ($result as $row) {
        $eventproperties[] = array(
            'id' => $row['id'], 
            'property' => $row['property']);
    }

    include $siteroot . 'demo2/app/pages_admin/view_event_properties.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// event overflow
//////////////////////////////////////////////////////////////////////////

// view all event overflow
if (isset($_GET['view_event_overflow'])) {
    
    // gets a list of all events with overflow requests
    $sql = 'SELECT COUNT(action), name, events.id
    FROM log 
    LEFT JOIN events ON
        eventID = events.id
    WHERE action = "overflow"
    GROUP BY eventID
    ORDER BY COUNT(action) DESC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $overflow_events = $s->fetchall();
    
    include $siteroot . 'demo2/app/pages_admin/view_event_overflow.inc.html.php';
    exit();
}

// show event overflow for event
if (isset($_POST['action']) and $_POST['action'] == 'overflow_view_event_requesters') {
    
    // gets a list of all users who requested another event like this one
    $sql = 'SELECT userID, first_name, last_name, email 
    FROM log 
    LEFT JOIN users ON
        userID = users.id
    WHERE eventID = :eventID AND action = "overflow"';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['eventID']);
    $s->execute();
    $requesters = $s->fetchall();
    
    // save the eventID
    $eventID = html($_POST['eventID']);
    
    // gets a list of all events with overflow requests
    $sql = 'SELECT COUNT(action), name, events.id
    FROM log 
    LEFT JOIN events ON
        eventID = events.id
    WHERE action = "overflow"
    GROUP BY eventID
    ORDER BY COUNT(action) DESC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $overflow_events = $s->fetchall();
    
    include $siteroot . 'demo2/app/pages_admin/view_event_overflow.inc.html.php';
    exit();
}

// email event overflow requesters for event
if (isset($_POST['action']) and $_POST['action'] == 'email_overflow_users') {

    // gets a list of all events with overflow requests
    $sql = 'SELECT COUNT(action), name, events.id
    FROM log 
    LEFT JOIN events ON
        eventID = events.id
    WHERE action = "overflow"
    GROUP BY eventID
    ORDER BY COUNT(action) DESC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $overflow_events = $s->fetchall();
    
    // gets a list of all users who requested another event like this one
    $sql = 'SELECT userID, first_name, last_name, email 
    FROM log 
    LEFT JOIN users ON
        userID = users.id
    WHERE eventID = :eventID AND action = "overflow"';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['eventID']);
    $s->execute();
    $requesters = $s->fetchall();
    
    // gets a list of email addresses for these users
    $email_bcc_recipients = $requesters;
    
    // send receipt email
    $subject = html($_POST['subject']);
    $body = '<html><body>' . html($_POST['body']) . '</body></html>';
    include $siteroot . 'demo2/app/includes_php/send_mail.php';
    
    $message = 'Message sent.';
    include $siteroot . 'demo2/app/pages_admin/view_event_overflow.inc.html.php';
    exit();
}

// clear event overflow for event
if (isset($_POST['action']) and $_POST['action'] == 'overflow_clear_log_entries') {
    
    // clear the log entries
    $sql = 'DELETE FROM log
        WHERE eventID = :eventID AND action = "overflow"';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['eventID']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], $_POST['eventID'], 'cleared overflow');
    
    // gets a list of all events with overflow requests
    $sql = 'SELECT COUNT(action), name, events.id
    FROM log 
    LEFT JOIN events ON
        eventID = events.id
    WHERE action = "overflow"
    GROUP BY eventID
    ORDER BY COUNT(action) DESC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $result = $s->fetchall();
    
    include $siteroot . 'demo2/app/pages_admin/view_event_overflow.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// static convention info
//////////////////////////////////////////////////////////////////////////

// view/edit contact info
if (isset($_GET['contact_info'])) {
    
    // gets static con info
    $con_info = getCurrentYearInfo();
    
    include $siteroot . 'demo2/app/pages_admin/view_contact_info.inc.html.php';
    exit();
}

// view/edit settings
if (isset($_GET['settings'])) {
    
    // gets static con info
    $con_info = getCurrentYearInfo();
    
    include $siteroot . 'demo2/app/pages_admin/view_settings.inc.html.php';
    exit();
}

// update contact info
if (isset($_POST['action']) and $_POST['action'] == 'update_contact_info') {
    
    // update static con info
    $sql = 'UPDATE static_con_info SET
        official_name       = :official_name,
        abbreviated_name    = :abbreviated_name,
        tagline             = :tagline,
        email               = :email,
        address1            = :address1,
        address2            = :address2,
        state               = :state,
        zip                 = :zip,
        city                = :city,
        twitter             = :twitter,
        facebook            = :facebook,
        web                 = :web 
        WHERE id = "1"';
    $s = $pdo->prepare($sql);
    $s->bindValue(':official_name', $_POST['official_name']);
    $s->bindValue(':abbreviated_name', $_POST['abbreviated_name']);
    $s->bindValue(':tagline', $_POST['tagline']);
    $s->bindValue(':email', $_POST['email']);
    $s->bindValue(':address1', $_POST['address1']);
    $s->bindValue(':address2', $_POST['address2']);
    $s->bindValue(':city', $_POST['city']);
    $s->bindValue(':state', $_POST['state']);
    $s->bindValue(':zip', $_POST['zip']);
    $s->bindValue(':twitter', $_POST['twitter']);
    $s->bindValue(':facebook', $_POST['facebook']);
    $s->bindValue(':web', $_POST['web']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'updated static con info');
    
    // display confirmation
    $title = "Convention Information Updated";
    $longdesc = 'You have successfully updated the convention information.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

// update settings
if (isset($_POST['action']) and $_POST['action'] == 'update_settings') {
    
    // update static con info
    $sql = 'UPDATE static_con_info SET
        schedule_shown              = :schedule_shown,
        allow_badges                = :allow_badges,
        store_on                    = :store_on,
        ads_on                      = :ads_on,
        badge_price                 = :badge_price,
        kid_badge_price             = :kid_badge_price,
        kid_badge_max_age           = :kid_badge_max_age,
        free_badge_max_age          = :free_badge_max_age,
        kid_badge_on                = :kid_badge_on,
        free_badge_on               = :free_badge_on,
        guests_on                   = :guests_on,
        vendors_on                  = :vendors_on,
        event_shoutboxes            = :event_shoutboxes,
        forums_on                   = :forums_on,
        public_submit_events_on     = :public_submit_events_on, 
        sponsors_on                 = :sponsors_on
        WHERE id = "1"';
    $s = $pdo->prepare($sql);
    $s->bindValue(':ads_on', $_POST['ads_on']);
    $s->bindValue(':schedule_shown', $_POST['schedule_shown']);
    $s->bindValue(':allow_badges', $_POST['allow_badges']);
    $s->bindValue(':store_on', $_POST['store_on']);
    $s->bindValue(':badge_price', $_POST['badge_price']);
    $s->bindValue(':kid_badge_price', $_POST['kid_badge_price']);
    $s->bindValue(':kid_badge_max_age', $_POST['kid_badge_max_age']);
    $s->bindValue(':free_badge_max_age', $_POST['free_badge_max_age']);
    $s->bindValue(':kid_badge_on', $_POST['kid_badge_on']);
    $s->bindValue(':free_badge_on', $_POST['free_badge_on']);
    $s->bindValue(':guests_on', $_POST['guests_on']);
    $s->bindValue(':vendors_on', $_POST['vendors_on']);
    $s->bindValue(':event_shoutboxes', $_POST['event_shoutboxes']);
    $s->bindValue(':forums_on', $_POST['forums_on']);
    $s->bindValue(':public_submit_events_on', $_POST['public_submit_events_on']);
    $s->bindValue(':sponsors_on', $_POST['sponsors_on']);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'updated static con info');
    
    // display confirmation
    header("Location: ." . '?settings');
    exit();
}

//////////////////////////////////////////////////////////////////////////
// year management
//////////////////////////////////////////////////////////////////////////

// show year management page
if (isset($_GET['years'])) {
    
    // get current year number
    $con_info = getCurrentYearInfo();
    
    // get all year info
    $years = getYears();
    
    include $siteroot . 'demo2/app/pages_admin/years.inc.html.php';
    exit();
}

// update a year
if (isset($_POST['action']) and $_POST['action'] == 'update_year') {
    
    // update year
    $sql = 'UPDATE years SET
        start    = :start,
        end      = :end 
        WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':start', $_POST['start']);
    $s->bindValue(':end', $_POST['end']);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    
    // get current year number
    $con_info = getCurrentYearInfo();
    
    // get all year info
    $years = getYears();
    
    include $siteroot . 'demo2/app/pages_admin/years.inc.html.php';
    exit();
}

// create a year
if (isset($_POST['action']) and $_POST['action'] == 'create_year') {
    
    // create new year
    $sql = 'INSERT INTO years SET
        start    = :start,
        end      = :end';
    $s = $pdo->prepare($sql);
    $s->bindValue(':start', $_POST['start']);
    $s->bindValue(':end', $_POST['end']);
    $s->execute();
    
    // get current year number
    $con_info = getCurrentYearInfo();
    
    // get all year info
    $years = getYears();
    
    include $siteroot . 'demo2/app/pages_admin/years.inc.html.php';
    exit();
}

// set upcoming convention date start/end
if (isset($_POST['action']) and $_POST['action'] == 'set_next_con') {
    
    // set next con dates
    $sql = 'UPDATE static_con_info SET
        current_year             = :current_year
        WHERE static_con_info.id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':current_year', $_POST['next_con']);
    $s->bindValue(':id', 1);
    $s->execute();
    
    // get current year number
    $con_info = getCurrentYearInfo();
    
    // get all year info
    $years = getYears();
    
    include $siteroot . 'demo2/app/pages_admin/years.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// advance to next year
//////////////////////////////////////////////////////////////////////////

// advance to next year
if (isset($_POST['action']) and $_POST['action'] == 'advance_to_next_year') {
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'move con to next year - began process');

    // create a backup of the mysql database
    backupDatabase();
    
    // zip up the csv files
    $source = $siteroot . 'public_html/demo2/temp/';
    $destination = $siteroot . 'public_html/demo2/temp/backup.zip';
    Zip($source, $destination);
    
    // email the backup file
    $address = "lintonrentfro@gmail.com";
    $subject = "Database Backup";
    $body = 'A backup of the convention database is attached to this email.';
    $attachment = $siteroot . 'public_html/demo2/temp/backup.zip';
    require $siteroot . 'demo2/app/includes_php/send_mail.php';   
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'created backup');
    
    // delete the local backup files
    deleteBackup();
    
    try {
        $pdo->beginTransaction();

        // delete duty_roster
        $sql = 'DELETE FROM duty_roster';
        $pdo->exec($sql);

        // delete events
        $sql = 'DELETE FROM events';
        $pdo->exec($sql);

        // delete log
        $sql = 'DELETE FROM log';
        $pdo->exec($sql);

        // delete lookup_events_eventproperties
        $sql = 'DELETE FROM lookup_events_eventproperties';
        $pdo->exec($sql);

        // delete lookup_users_events
        $sql = 'DELETE FROM lookup_users_events';
        $pdo->exec($sql);

        // delete shoutbox
        $sql = 'DELETE FROM shoutbox';
        $pdo->exec($sql);

        // add 1 to current_year field in static_con_info table 
        $sql = 'UPDATE static_con_info SET current_year = current_year + 1';
        $pdo->exec($sql);
        
        $pdo->commit();
    }
    catch (PDOException $e) {
        $pdo->rollBack();
        
        // log the event
        logevent($user_info['id'], NULL, 'move con to next year - process aborted');
    
        // display error message
        $error = 'Error advancing to next year.  Please contact application
            administrator.';
        include $siteroot . 'demo2/app/pages_admin/error.inc.html.php';
        exit();
    }
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'move con to next year - process complete');
    
    // display confirmation
    $title = "Convention Advanced to Next Dates";
    $longdesc = 'You have successfully backed up the database and prepared it
        for the next convention.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// badges
//////////////////////////////////////////////////////////////////////////

// show options for badges
if (isset($_GET['badges'])) {
    include $siteroot . 'demo2/app/pages_admin/badges_default.inc.html.php';
    exit();
}

// email admin csv file of all badges
if (isset($_POST['action']) and $_POST['action'] == 'create_pre-reg_badges') {
    
    // create the file
    $badges = $siteroot . 'public_html/demo2/temp/badges.csv';
    $fh = fopen($badges, 'w');
    
    // gets the current year
    $sql = 'SELECT current_year FROM static_con_info LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->execute();
    $current_year = $s->fetch();
    $year = $current_year['current_year'];
    
    // get info for all users who have purchased badges
    $sql = 'SELECT *
    FROM lookup_users_years
    INNER JOIN users
    ON lookup_users_years.userID = users.id
    WHERE lookup_users_years.yearID = :year';
    $s = $pdo->prepare($sql);
    $s->bindValue(':year', $year);
    $s->execute();
    $badge_list = $s->fetchall(PDO::FETCH_ASSOC);  
    
    // write the badge list to the file
    foreach ($badge_list as $row) :
        $string = $row['first_name'] . ',' . $row['last_name'] . ',' . 
                $row['address1'] . ',' . $row['address2'] . ',' . 
                $row['city'] . ',' . $row['state'] . ',' . 
                $row['zip'];
        fwrite($fh, $string);
        $string = "\n";
        fwrite($fh, $string);
    endforeach;
    fclose($fh);
    
    // email the csv file to the admin
    $address = "lintonrentfro@gmail.com";
    $subject = "Pre-Registered Badge List CSV File";
    $body = 'A list of all of the pre-registered users names and addresses are
        attached in csv format.';
    $attachment = $siteroot . 'public_html/demo2/temp/badges.csv';
    require $siteroot . 'demo2/app/includes_php/send_mail.php';    
    
    // delete the local csv file
    unlink($badges);
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'created badge csv file');
    
    // display confirmation
    $title = "Pre-Registration Badge List Sent";
    $longdesc = 'A list of all of the pre-registered users names and addresses
        were sent to you in an email.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// logs
//////////////////////////////////////////////////////////////////////////

// show log search forms
if (isset($_GET['view_log'])) {
    
    // create an array of event search fields
    $sql = 'SELECT id, name
    FROM events
    ORDER BY name ASC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $eventlist = $s->fetchall();
    
    // create an array of user search fields
    $sql = 'SELECT id, first_name, last_name
    FROM users
    ORDER BY last_name ASC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $userlist = $s->fetchall();

    include $siteroot . 'demo2/app/pages_admin/log_search.inc.html.php';
    exit();
}

// show log search results for events
if (isset($_POST['action']) and $_POST['action'] == 'search_event_log') {
    
    // returns the search page if no criteria was selected
    if ($_POST['searchby'] == '') {
        header("Location: ." . '?view_log');
        exit();
    }
    
    // create an array of event search fields
    $sql = 'SELECT id, name
    FROM events
    ORDER BY name ASC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $eventlist = $s->fetchall();
    
    // create an array of user search fields
    $sql = 'SELECT id, first_name, last_name
    FROM users
    ORDER BY last_name ASC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $userlist = $s->fetchall();

    // search event log for all entries elating to chosen event id
    $sql = 'SELECT first_name, last_name, name, action, time 
    FROM log
    INNER JOIN users
    ON userID = users.id
    INNER JOIN events
    ON eventID = events.id
    WHERE eventID = :eventID
    ORDER BY time DESC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':eventID', $_POST['searchby']);
    $s->execute();
    $search_result = $s->fetchall();
    
    include $siteroot . 'demo2/app/pages_admin/log_search.inc.html.php';
    exit();
}

// show log search results for users
if (isset($_POST['action']) and $_POST['action'] == 'search_user_log') {
    
    // returns the search page if no criteria was selected
    if ($_POST['searchby'] == '') {
        header("Location: ." . '?view_log');
        exit();
    }
    
    // create an array of event search fields
    $sql = 'SELECT id, name
    FROM events
    ORDER BY name ASC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $eventlist = $s->fetchall();
    
    // create an array of user search fields
    $sql = 'SELECT id, first_name, last_name
    FROM users
    ORDER BY last_name ASC';
    $s = $pdo->prepare($sql);
    $s->execute();
    $userlist = $s->fetchall();

    // search event log for all entries elating to chosen user id
    $sql = 'SELECT first_name, last_name, name, action, time 
    FROM log
    LEFT JOIN users
    ON userID = users.id
    LEFT JOIN events
    ON eventID = events.id
    WHERE userID = :userID
    ORDER BY time DESC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_POST['searchby']);
    $s->execute();
    $search_result = $s->fetchall();
    
    include $siteroot . 'demo2/app/pages_admin/log_search.inc.html.php';
    exit();
}

// show all users with free badges
if (isset($_POST['action']) and $_POST['action'] == 'show_free_badges') {
    
    // get user information for those user ids who have free badges
    $sql = 'SELECT id, first_name, last_name, email
        FROM log
        INNER JOIN users
        ON userID = id
        WHERE action = "badge is free"';
    $s = $pdo->prepare($sql);
    $s->execute();
    $users_with_free_badges = $s->fetchall(PDO::FETCH_ASSOC);  
    
    include $siteroot . 'demo2/app/pages_admin/view_free_badges.inc.html.php';
    exit();
}

// revoke a free badge
if (isset($_POST['action']) and $_POST['action'] == 'revoke_free_badge') {
    
    // gets the current year
    $sql = 'SELECT current_year FROM static_con_info LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->execute();
    $current_year = $s->fetch();
    $year = $current_year['current_year'];
    
    // update lookup_users_years so the user is now "unpaid"
    $sql = 'DELETE FROM lookup_users_years 
        WHERE userID = :userID AND yearID = :yearID
        LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_POST['id']);
    $s->bindValue(':yearID', $year);
    $s->execute();
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'revoked free badge for user id ' . $_POST['id']);
    
    // remove the log entry stating that the user has a free badge
    $sql = 'DELETE FROM log 
        WHERE userID = :userID AND action = "badge is free"
        LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_POST['id']);
    $s->execute();
    
    // get user information for those user ids who have free badges
    $sql = 'SELECT id, first_name, last_name, email
        FROM log
        INNER JOIN users
        ON userID = id
        WHERE action = "badge is free"';
    $s = $pdo->prepare($sql);
    $s->execute();
    $users_with_free_badges = $s->fetchall(PDO::FETCH_ASSOC);   
    
    include $siteroot . 'demo2/app/pages_admin/view_free_badges.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// backup
//////////////////////////////////////////////////////////////////////////

// show backup page
if (isset($_GET['backup'])) {
    include $siteroot . 'demo2/app/pages_admin/backup.inc.html.php';
    exit();
}

// email admin a backup
if (isset($_POST['action']) and $_POST['action'] == 'backup') {
    
    // create a backup of the mysql database
    backupDatabase();
    
    // zip up the csv files
    $source = $siteroot . 'public_html/demo2/temp/';
    $destination = $siteroot . 'public_html/demo2/temp/backup.zip';
    Zip($source, $destination);
    
    // email the backup file
    $address = "lintonrentfro@gmail.com";
    $subject = "Database Backup";
    $body = 'A backup of the convention database is attached to this email.';
    $attachment = $siteroot . 'public_html/demo2/temp/backup.zip';
    require $siteroot . 'demo2/app/includes_php/send_mail.php';   
    
    // get the user id and log the event
    logevent($user_info['id'], NULL, 'created backup');
    
    // delete the local backup files
    deleteBackup();
    
    // display confirmation
    $title = "Backup Complete";
    $longdesc = 'A backup file has been sent.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// advertising
//////////////////////////////////////////////////////////////////////////

// show advertising page
if (isset($_GET['advertising'])) {
    
    $sql = 'SELECT ads_on
        FROM static_con_info';
    $s = $pdo->prepare($sql);
    $s->execute();
    $coninfo = $s->fetch(PDO::FETCH_ASSOC);
    
    $sql = 'SELECT * FROM ads';
    $s = $pdo->prepare($sql);
    $s->execute();
    $ads = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/advertising.inc.html.php';
    exit();
}

// toggle ads
if (isset($_POST['action']) and $_POST['action'] == 'toggle_ads') {
    
    $sql = 'UPDATE static_con_info SET
        ads_on       = :ads_on 
        WHERE id = "1"';
    $s = $pdo->prepare($sql);
    $s->bindValue(':ads_on', $_POST['ads_on']);
    $s->execute();
    
    header("Location: ." . '?advertising');
    exit();
}

// create new ad
if (isset($_POST['action']) and $_POST['action'] == 'create_ad') {
    
    $sql = 'INSERT INTO ads SET
        sponsor_contact    =   :sponsor_contact,
        sponsor_company    =   :sponsor_company,
        sponsor_email      =   :sponsor_email,
        sponsor_phone      =   :sponsor_phone,
        image_url          =   :image_url,
        on_or_off          =   :on_or_off,
        link_url           =   :link_url';
    $s = $pdo->prepare($sql);
    $s->bindValue(':sponsor_contact', $_POST['sponsor_contact']);
    $s->bindValue(':sponsor_company', $_POST['sponsor_company']);
    $s->bindValue(':sponsor_email', $_POST['sponsor_email']);
    $s->bindValue(':sponsor_phone', $_POST['sponsor_phone']);
    $s->bindValue(':image_url', $_POST['image_url']);
    $s->bindValue(':on_or_off', $_POST['on_or_off']);
    $s->bindValue(':link_url', $_POST['link_url']);
    $s->execute();
    
    header("Location: ." . '?advertising');
    exit();
}

// update existing ad
if (isset($_POST['action']) and $_POST['action'] == 'update_ad') {
    
    $sql = 'UPDATE ads SET
        sponsor_contact    =   :sponsor_contact,
        sponsor_company    =   :sponsor_company,
        sponsor_email      =   :sponsor_email,
        sponsor_phone      =   :sponsor_phone,
        image_url          =   :image_url,
        on_or_off          =   :on_or_off,
        link_url           =   :link_url 
        WHERE adID = :adID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':sponsor_contact', $_POST['sponsor_contact']);
    $s->bindValue(':sponsor_company', $_POST['sponsor_company']);
    $s->bindValue(':sponsor_email', $_POST['sponsor_email']);
    $s->bindValue(':sponsor_phone', $_POST['sponsor_phone']);
    $s->bindValue(':image_url', $_POST['image_url']);
    $s->bindValue(':on_or_off', $_POST['on_or_off']);
    $s->bindValue(':link_url', $_POST['link_url']);
    $s->bindValue(':adID', $_POST['adID']);
    $s->execute();
    
    header("Location: ." . '?advertising');
    exit();
}

// delete ad
if (isset($_POST['action']) and $_POST['action'] == 'delete_ad') {
    
    $sql = 'DELETE FROM ads 
        WHERE adID = :adID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':adID', $_POST['adID']);
    $s->execute();
    
    header("Location: ." . '?advertising');
    exit();
}

//////////////////////////////////////////////////////////////////////////
// store
//////////////////////////////////////////////////////////////////////////

// view all items
if (isset($_GET['items'])) {
        
    $sql = 'SELECT * FROM store_items';
    $s = $pdo->query($sql);
    $items = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/store_items.inc.html.php';
    exit();
}

// add item form
if (isset($_GET['add_item'])) {
    
    $sql = 'SELECT * FROM store_categories';
    $s = $pdo->query($sql);
    $categories = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/store_item_add.inc.html.php';
    exit();
}

// add item to database
if (isset($_POST['action']) and $_POST['action'] == 'add_item') {
    
    $sql = 'INSERT INTO store_items SET
        category        =   :category,
        name            =   :name,
        price           =   :price,
        image           =   :image,
        description     =   :description';
    $s = $pdo->prepare($sql);
    $s->bindValue(':category', $_POST['category']);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':price', $_POST['price']);
    $s->bindValue(':image', $_POST['image']);
    $s->bindValue(':description', $_POST['description']);
    $s->execute();
    
    header("Location: ." . '?items');
    exit();
}

// delete item from database
if (isset($_POST['action']) and $_POST['action'] == 'delete_item') {
    
    $sql = 'DELETE FROM store_items 
        WHERE itemID = :itemID LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':itemID', $_POST['itemID']);
    $s->execute();
    
    header("Location: ." . '?items');
    exit();
}

// view item
if (isset($_POST['action']) and $_POST['action'] == 'view_item') {
    
    $sql = 'SELECT * FROM store_categories';
    $s = $pdo->query($sql);
    $categories = $s->fetchall(PDO::FETCH_ASSOC);
    
    $sql = 'SELECT * FROM store_items WHERE itemID = :itemID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':itemID', $_POST['itemID']);
    $s->execute();
    $item = $s->fetch(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/store_item_edit.inc.html.php';
    exit();
}

// update item
if (isset($_POST['action']) and $_POST['action'] == 'update_item') {
    
    $sql = 'UPDATE store_items SET
        category        =   :category,
        name            =   :name,
        price           =   :price,
        image           =   :image,
        description     =   :description 
        WHERE itemID    =   :itemID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':category', $_POST['category']);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':price', $_POST['price']);
    $s->bindValue(':image', $_POST['image']);
    $s->bindValue(':description', $_POST['description']);
    $s->bindValue(':itemID', $_POST['itemID']);
    $s->execute();
    
    header("Location: ." . '?items');
    exit();
}

// view categories
if (isset($_GET['categories'])) {
    
    $sql = 'SELECT * FROM store_categories';
    $s = $pdo->query($sql);
    $categories = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/store_categories.inc.html.php';
    exit();
}

// add category form
if (isset($_GET['add_category'])) {
    include $siteroot . 'demo2/app/pages_admin/store_category_add.inc.html.php';
    exit();
}

// add new category to database
if (isset($_POST['action']) and $_POST['action'] == 'add_category') {
    
    $sql = 'INSERT INTO store_categories SET
        name        =   :name';
    $s = $pdo->prepare($sql);
    $s->bindValue(':name', $_POST['name']);
    $s->execute();
    
    header("Location: ." . '?categories');
    exit();
}

// delete category of items
if (isset($_POST['action']) and $_POST['action'] == 'delete_category') {
    
    $sql = 'DELETE FROM store_categories 
        WHERE categoryID = :categoryID LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':categoryID', $_POST['categoryID']);
    $s->execute();
    
    header("Location: ." . '?categories');
    exit();
}

//////////////////////////////////////////////////////////////////////////
// static HTML pages
//////////////////////////////////////////////////////////////////////////

// show edit form
if (isset($_GET['action']) and $_GET['action'] == 'edit_html') {

    $htmlfile = $siteroot . 'demo2/app/includes_html/admin_provided/' . $_GET['page'] . '.html';
    $handle = fopen($htmlfile, 'r');
    $contents = fread($handle,filesize($htmlfile));
    fclose($handle);
    $page = $_GET['page'];
    include $siteroot . 'demo2/app/pages_admin/edit_html.inc.html.php';
    exit();
}

// update html file
if (isset($_POST['action']) and $_POST['action'] == 'update_html') {
    
    $htmlfile = $siteroot . 'demo2/app/includes_html/admin_provided/' . $_POST['page'] . '.html';
    $handle = fopen($htmlfile, 'w');
    fwrite($handle, $_POST['html']);
    fclose($handle);
    
    $title = "HTML Updated";
    $longdesc = 'The html for that page has been updated.';
    include $siteroot . 'demo2/app/pages_admin/confirmation.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// forum
//////////////////////////////////////////////////////////////////////////

// show forum setup page
if (isset($_GET['forum'])) {
        
    // topics
    $sql = '
        SELECT *
        FROM forum_topics
        ORDER BY topicorder ASC';
    $s = $pdo->query($sql);
    $topics = $s->fetchall(PDO::FETCH_ASSOC);
    
    // get an array of forum subtopics ordered by topic
    $sql = '
        SELECT *
        FROM forum_subtopics
        INNER JOIN forum_topics
        ON undertopicID = topicID
        ORDER BY topicorder ASC, subtopicorder ASC';
    $s = $pdo->query($sql);
    $subtopics = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/forum.inc.html.php';
    exit();
}

// add topic
if (isset($_POST['action']) and $_POST['action'] == 'add_topic') {
    $sql = 'INSERT INTO forum_topics SET
        topicname        =   :topicname';
    $s = $pdo->prepare($sql);
    $s->bindValue(':topicname', $_POST['topicname']);
    $s->execute();
    header("Location: ." . '?forum');
    exit();
}

// add subtopic
if (isset($_POST['action']) and $_POST['action'] == 'add_subtopic') {
    $sql = 'INSERT INTO forum_subtopics SET
        subtopicname        =   :subtopicname,
        undertopicID        =   :undertopicID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':topicname', $_POST['topicname']);
    $s->bindValue(':undertopicID', $_POST['undertopicID']);
    $s->execute();
    header("Location: ." . '?forum');
    exit();
}

// update topic
if (isset($_POST['action']) and $_POST['action'] == 'update_topic') {
    $sql = 'UPDATE forum_topics SET
        topicname        =   :topicname,
        topicorder       =   :topicorder
        WHERE topicID    =   :topicID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':topicname', $_POST['topicname']);
    $s->bindValue(':topicorder', $_POST['topicorder']);
    $s->bindValue(':topicID', $_POST['topicID']);
    $s->execute();
    header("Location: ." . '?forum');
    exit();
}

// update subtopic
if (isset($_POST['action']) and $_POST['action'] == 'update_subtopic') {
    $sql = 'UPDATE forum_subtopics SET
        subtopicname        =   :subtopicname,
        undertopicID        =   :undertopicID,
        subtopicorder       =   :subtopicorder
        WHERE subtopicID    =   :subtopicID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':subtopicname', $_POST['subtopicname']);
    $s->bindValue(':undertopicID', $_POST['undertopicID']);
    $s->bindValue(':subtopicorder', $_POST['subtopicorder']);
    $s->bindValue(':subtopicID', $_POST['subtopicID']);
    $s->execute();
    header("Location: ." . '?forum');
    exit();
}

//////////////////////////////////////////////////////////////////////////
// guests
//////////////////////////////////////////////////////////////////////////

// show guest page
if (isset($_GET['guests'])) {
    
    // guests
    $sql = '
        SELECT *
        FROM guests
        LEFT JOIN users
        ON userID = id
        ORDER BY professional_name ASC';
    $s = $pdo->query($sql);
    $guests = $s->fetchall(PDO::FETCH_ASSOC);
    
    // lookup_guests_events
    $sql = '
        SELECT *
        FROM lookup_guests_events';
    $s = $pdo->query($sql);
    $guest_events = $s->fetchall(PDO::FETCH_ASSOC);
    
    // complete event list
    $sql = '
        SELECT id, name, building, room, start, end
        FROM events
        ORDER BY name ASC';
    $s = $pdo->query($sql);
    $events = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/guests.inc.html.php';
    exit();
}

// update guest
if (isset($_POST['action']) and $_POST['action'] == 'update_guest') {
        
    $sql = 'UPDATE guests SET ' . $_POST['field_to_change'] . ' = :' . 
            $_POST['field_to_change'] . ' WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':' . $_POST['field_to_change'], $_POST['field']);
    $s->bindValue(':userID', $_POST['userID']);
    $s->execute();
    
    header("Location: ." . '?guests');
    exit();
}

// show guest schedule
if (isset($_GET['action']) and $_GET['action'] == 'guest_schedule') {
    
    // event types
    $sql = ' SELECT * FROM event_types';
    $s = $pdo->query($sql);
    $event_types = $s->fetchall(PDO::FETCH_ASSOC);
    
    // guest info
    $sql = '
        SELECT *
        FROM guests
        WHERE userID = :userID
        LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['id']);
    $s->execute();
    $guest = $s->fetch(PDO::FETCH_ASSOC);
    
    // lookup_guests_events
    $sql = '
        SELECT userID, eventID, start, end, name, building, room
        FROM lookup_guests_events
        INNER JOIN events
        ON eventID = events.id
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['id']);
    $s->execute();
    $guest_events = $s->fetchall(PDO::FETCH_ASSOC);
    
    // events by type or all events
    $sql = '
        SELECT id, name, building, room, start, end
        FROM events ';
    if ($_GET['type'] == 'all') {
        $sql .= 'ORDER BY start ASC';
    }
    else {
        $sql .= 'WHERE type = :type ORDER BY start ASC';
    }
    $s = $pdo->prepare($sql);
    $s->bindValue(':type', $_GET['type']);
    $s->execute();
    $events = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/guest_schedule.inc.html.php';
    exit();
}

// update guest schedule
if (isset($_GET['action']) and $_GET['action'] == 'update_guest_events') {
    
    if ($_GET['query'] == 'delete') {
        $sql = '
            DELETE FROM lookup_guests_events
            WHERE userID = :userID AND eventID = :eventID
            LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $_GET['userID']);
        $s->bindValue(':eventID', $_GET['eventID']);
        $s->execute();
    }
    
    if ($_GET['query'] == 'add') {
        
        $sql = '
            INSERT INTO lookup_guests_events
            SET userID  = :userID,
                eventID = :eventID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $_GET['userID']);
        $s->bindValue(':eventID', $_GET['eventID']);
        $s->execute();
    }
    
    header("Location: ." . '?id=' . $_GET['userID'] . '&type=' . $_GET['type'] . '&action=guest_schedule');
    exit();
}

// check all guests' schedules for conflicts
if (isset($_GET['guest_schedule_check'])) {
    
    // guest info
    $sql = '
        SELECT *
        FROM guests';
    $s = $pdo->query($sql);
    $s->execute();
    $guests = $s->fetchall(PDO::FETCH_ASSOC);
    
    // lookup_guests_events
    $sql = '
        SELECT userID, eventID, start, end
        FROM lookup_guests_events
        INNER JOIN events
        ON eventID = events.id';
    $s = $pdo->query($sql);
    $s->execute();
    $guests_events = $s->fetchall(PDO::FETCH_ASSOC);
    
    // complete event list
    $sql = '
        SELECT id, name, building, room, start, end
        FROM events
        ORDER BY start ASC';
    $s = $pdo->query($sql);
    $events = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/guest_schedule_check.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// email features
//////////////////////////////////////////////////////////////////////////

if (isset($_GET['email_features'])) {
    
    $con_info = getCurrentYearInfo();
    
    $sql = '
        SELECT *
        FROM event_types';
    $s = $pdo->query($sql);
    $s->execute();
    $event_types = $s->fetchall(PDO::FETCH_ASSOC);
    
    $sql = '
        SELECT *
        FROM event_properties';
    $s = $pdo->query($sql);
    $s->execute();
    $event_properties = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_admin/email_features.inc.html.php';
    exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'by_type_of_user') {
    
    // Everyone who has registered on this site.
    if ($_POST['by_type_of_user'] == 'Everyone who has registered on this site.') {
        $sql = '
            SELECT first_name, last_name, email
            FROM users';
        $s = $pdo->query($sql);
        $s->execute();
        $list = $s->fetchall(PDO::FETCH_ASSOC);
        
        createEmailList($list);
        
        $subject = "Email List - All Registered Users";
        $body = 'The attached file contains the emails of everyone who has registered on the convention website.';
    }
    
    // Everyone who has a badge.
    if ($_POST['by_type_of_user'] == 'Everyone who has a badge.') {
        
        // gets the current year
        $sql = '
            SELECT current_year 
            FROM static_con_info 
            LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->execute();
        $current_year = $s->fetch();
        $year = $current_year['current_year'];
        
        // get info for all users who have badges for current year
        $sql = '
            SELECT first_name, last_name, email
            FROM lookup_users_years
            INNER JOIN users
            ON lookup_users_years.userID = users.id
            WHERE lookup_users_years.yearID = :year';
        $s = $pdo->prepare($sql);
        $s->bindValue(':year', $year);
        $s->execute();
        $list = $s->fetchall(PDO::FETCH_ASSOC);
        
        createEmailList($list);
        
        $subject = "Email List - All Users With Badges";
        $body = 'The attached file contains the emails of everyone who has a badge.';
    }
    
    // Everyone who does NOT have a badge.
    if ($_POST['by_type_of_user'] == 'Everyone who does NOT have a badge.') {
        
        // gets all users
        $result = $pdo->query('
            SELECT id, first_name, last_name, email
            FROM users');
        foreach ($result as $row) {
            $all_users[] = $row['id'];
        }

        // gets the current year
        $sql = '
            SELECT current_year 
            FROM static_con_info 
            LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->execute();
        $current_year = $s->fetch();
        $year = $current_year['current_year'];

        // get info for all users who have badges for current year
        $result = $pdo->query('
            SELECT id, first_name, last_name, email
            FROM lookup_users_years
            INNER JOIN users
            ON lookup_users_years.userID = users.id
            WHERE lookup_users_years.yearID = ' . $year);
        foreach ($result as $row) {
            $users_with_badges[] = $row['id'];
        }

        $users_without_badges = array_diff($all_users, $users_with_badges);

        // create the file
        $file = $siteroot . 'public_html/demo2/temp/email_list.csv';
        $fh = fopen($file, 'w');
        foreach ($users_without_badges as $user):
            $sql = '
                SELECT id, first_name, last_name, email
                FROM users
                WHERE id = :id';
            $s = $pdo->prepare($sql);
            $s->bindValue(':id', $user);
            $s->execute();
            $user_without_a_badge = $s->fetch(PDO::FETCH_ASSOC);
            $string = $user_without_a_badge['first_name'] . ',' . $user_without_a_badge['last_name'] . ',' . 
                $user_without_a_badge['email'];
            fwrite($fh, $string);
            $string = "\n";
            fwrite($fh, $string);
        endforeach;
        fclose($fh);
        
        $subject = "Email List - Users Without Badges";
        $body = 'The attached file contains the emails of everyone who does not have a badge.';
    }
    
    // All convention staff.
    if ($_POST['by_type_of_user'] == 'All convention staff.') {
        $sql = '
            SELECT first_name, last_name, email
            FROM lookup_users_userroles
            INNER JOIN users
            ON userID = id
            WHERE roleID = 4';
        $s = $pdo->query($sql);
        $s->execute();
        $list = $s->fetchall(PDO::FETCH_ASSOC);
        
        createEmailList($list);
        
        $subject = "Email List - All Convention Staff";
        $body = 'The attached file contains the emails of all convention staff.';
    }
    
    // All guests.
    if ($_POST['by_type_of_user'] == 'All guests.') {
        $sql = '
            SELECT first_name, last_name, email
            FROM guests
            INNER JOIN users
            ON userID = id';
        $s = $pdo->query($sql);
        $s->execute();
        $list = $s->fetchall(PDO::FETCH_ASSOC);
        
        createEmailList($list);
        
        $subject = "Email List - All Convention Staff";
        $body = 'The attached file contains the emails of all convention staff.';
    }
    
    // email the file
    $address = "lintonrentfro@gmail.com";
    $attachment = $siteroot . 'public_html/demo2/temp/email_list.csv';
    require $siteroot . 'demo2/app/includes_php/send_mail.php';   
    
    // delete the local backup files
    deleteList();
    
    // reload the email features page
    $con_info = getCurrentYearInfo();
    $sql = '
        SELECT *
        FROM event_types';
    $s = $pdo->query($sql);
    $s->execute();
    $event_types = $s->fetchall(PDO::FETCH_ASSOC);
    $sql = '
        SELECT *
        FROM event_properties';
    $s = $pdo->query($sql);
    $s->execute();
    $event_properties = $s->fetchall(PDO::FETCH_ASSOC);
    include $siteroot . 'demo2/app/pages_admin/email_features.inc.html.php';
    exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'by_event_type') {
    
    $sql = '
        SELECT first_name, last_name, email
        FROM events
        LEFT JOIN users
        ON contact = users.id
        WHERE type = :type';
    $s = $pdo->prepare($sql);
    $s->bindValue(':type', $_POST['event_type']);
    $s->execute();
    $list = $s->fetchall(PDO::FETCH_ASSOC);

    createEmailList($list);

    $subject = "Email List - Primary Contacts of Event Type";
    $body = 'The attached file contains the emails of all primary contacts for this type of event: ' . $_POST['event_type'];
    
    // email the file
    $address = "lintonrentfro@gmail.com";
    $attachment = $siteroot . 'public_html/demo2/temp/email_list.csv';
    require $siteroot . 'demo2/app/includes_php/send_mail.php';   
    
    // delete the local backup files
    deleteList();
    
    // reload the email features page
    $con_info = getCurrentYearInfo();
    $sql = '
        SELECT *
        FROM event_types';
    $s = $pdo->query($sql);
    $s->execute();
    $event_types = $s->fetchall(PDO::FETCH_ASSOC);
    $sql = '
        SELECT *
        FROM event_properties';
    $s = $pdo->query($sql);
    $s->execute();
    $event_properties = $s->fetchall(PDO::FETCH_ASSOC);
    include $siteroot . 'demo2/app/pages_admin/email_features.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// dashboard (homepage)
//////////////////////////////////////////////////////////////////////////

// gets the current year
$sql = 'SELECT current_year FROM static_con_info LIMIT 1';
$s = $pdo->query($sql);
$s->execute();
$current_year = $s->fetch(PDO::FETCH_ASSOC);

// get the total number of registered users
$sql = 'SELECT COUNT(*) FROM users';
$s = $pdo->query($sql);
$s->execute();
$number_of_users = $s->fetch(PDO::FETCH_ASSOC);

// get the total number of users who bought a badge
$sql = 'SELECT COUNT(*) FROM lookup_users_years WHERE yearID = :yearID';
$s = $pdo->prepare($sql);
$s->bindValue(':yearID', $current_year['current_year']);
$s->execute();
$number_of_paid_users = $s->fetch(PDO::FETCH_ASSOC);

// get the total number of verified users
$sql = 'SELECT COUNT(*) FROM users WHERE verified = 1';
$s = $pdo->query($sql);
$s->execute();
$number_of_verified_users = $s->fetch(PDO::FETCH_ASSOC);

// get the number of free badges given
$sql = 'SELECT COUNT(*) FROM log WHERE action = "badge is free"';
$s = $pdo->query($sql);
$s->execute();
$number_of_free_badges = $s->fetch(PDO::FETCH_ASSOC);

// get the total number of events_on_schedule
$sql = 'SELECT COUNT(*) FROM events WHERE status="on schedule - all clear"';
$s = $pdo->query($sql);
$s->execute();
$number_of_events_on_schedule = $s->fetch(PDO::FETCH_ASSOC);

// get the total number of events_pending
$sql = 'SELECT COUNT(*) FROM events WHERE status="pending approval"';
$s = $pdo->query($sql);
$s->execute();
$number_of_events_pending = $s->fetch(PDO::FETCH_ASSOC);

// event overflow requests
$sql = 'SELECT COUNT(*) 
    FROM log 
    WHERE action = "overflow"';
$s = $pdo->query($sql);
$s->execute();
$number_of_overflow_requests = $s->fetch(PDO::FETCH_ASSOC);

// total number of event shoutbox comments
$sql = 'SELECT COUNT(*) 
    FROM shoutbox';
$s = $pdo->query($sql);
$s->execute();
$number_of_event_comments = $s->fetch(PDO::FETCH_ASSOC);

// get the total number of guests
$sql = 'SELECT COUNT(*) FROM guests';
$s = $pdo->query($sql);
$s->execute();
$number_of_guests = $s->fetch(PDO::FETCH_ASSOC);

// number of guests with no events on their schedule
$sql = '
    SELECT *
    FROM guests';
$s = $pdo->query($sql);
$s->execute();
$guest_list = $s->fetchall(PDO::FETCH_ASSOC);
$sql = '
    SELECT *
    FROM lookup_guests_events';
$s = $pdo->query($sql);
$s->execute();
$guests_events = $s->fetchall(PDO::FETCH_ASSOC);
$count_of_guests_with_no_events = 0;
foreach ($guest_list as $guest):
    $count = 0;
    foreach ($guests_events as $event):
        if ($guest['userID'] == $event['userID']) {
            $count = $count + 1;
        }
    endforeach;
    if ($count == 0) {
        $count_of_guests_with_no_events = $count_of_guests_with_no_events + 1;
    }
endforeach;

include $siteroot . 'demo2/app/pages_admin/defaultadmin.inc.html.php';