<?php

session_start();

/*
 * --------------------------------------------------------------------
 * Registration Admin Controller
 * 
 * 
 * Includes
 * Loggin In/Out
 * Registering New Users
 * Registering Existing Users
 * Process Credit Card
 * Process Cash/Check
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


require '/home/simpleco/demo2/app/includes_php/magicquotes.inc.php';
require '/home/simpleco/demo2/app/includes_php/encrypt.inc.php';
require '/home/simpleco/demo2/app/includes_php/access.inc.php';
require '/home/simpleco/demo2/app/includes_php/db.inc.php';
require '/home/simpleco/demo2/app/includes_php/functions.inc.php';
require '/home/simpleco/stripe-php/lib/Stripe.php';


$includemailer = '/home/simpleco/demo2/app/PHPMailer_5.2.2/*.php';
foreach (glob($includemailer) as $filename)
{
    include $filename;
}

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
    include '/home/simpleco/demo2/app/pages_registrationadmin/login.inc.html.php';
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
        include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
        exit();
    }
    
    if (userIsLoggedIn()) {
        
        // get the user id and log the event
        session_start();
        $id = userID($_SESSION['email']);
        logevent($id, NULL, 'good login');
        
        // reset the failed_logins field
        recordGoodLogin($id);
        
        // return to index.php
        header("Location: .");
    }
    else {
        // get the user id and log the event
        session_start();
        $id = userID($_POST['email']);
        logevent($id, NULL, 'bad login');
        
        // add to the failed_logins field
        recordBadLogin($id);
        
        $title = 'Unauthorized User';
        $longdesc = "That email and password combination was not found.";
        include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
        exit();
    }
}

/*
 * responds to logout attempt
 */
if (isset($_GET['logout'])) {
    session_start();
    
    // get the user id and log the event
    $id = userID($_SESSION['email']);
    logevent($id, NULL, 'logout');
    
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
    session_start();
    if (!isset($_SESSION['loggedIn'])) {
        $title = 'Unauthorized User';
        $longdesc = "You need to log in as a user with appropriate credentials 
            to view this part of the site.";
        include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
        exit ();
    }
}

/*
 * checks to see if user is logged in
 */
if (!userHasRole(5)) {
    $title = 'Unauthorized User';
    $longdesc = "You do not have permission to access this part of the site.";
    include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
    exit();
}

/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                registering new users                                |
 *                                                                     |
 * --------------------------------------------------------------------
 */

/*
 * responds to request to register a new user
 */
if (isset($_GET['register_new'])) {
    include '/home/simpleco/demo2/app/pages_registrationadmin/register_new.inc.html.php';
    exit();
}

/*
 * takes registration form data and creates a new user with role id=1
 * required fields are first_name, last_name, email, address1, city, state, zip,
 */
if (isset($_POST['action']) and $_POST['action'] == 'registernew') {

    // get the id of the staff member from the session
    $staffid = userID($_SESSION['email']);
    
    // checks if email address is already in use
    $sql = 'SELECT COUNT(*) FROM users WHERE 
    email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->execute();
    $row = $s->fetch();
    
    // if email address is already in use
    if ($row[0] > 0) {
        
        // gets user info
        $sql = 'SELECT * FROM users WHERE email = :email AND password = :password';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $_SESSION['email']);
        $s->bindValue(':password', $_SESSION['password']);
        $s->execute();
        $user_info = $s->fetch();
        
        // get current year number
        $con_info = getCurrentYearInfo();
        
        // checks to see if the user has already bought a badge for current year
        $sql = 'SELECT COUNT(*) FROM lookup_users_years
            WHERE userID = :userID AND yearID = :yearID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $user_info['id']);
        $s->bindValue(':yearID', $con_info['current_year']);
        $s->execute();
        $paidforbadge = $s->fetch();
        
        // if user already has a badge
        if ($paidforbadge['COUNT(*)'] > 0) {
            
            // get the date and time of when user purchased badge
            $sql = 'SELECT time FROM log WHERE 
            userID = :userID AND action = :action';
            $s = $pdo->prepare($sql);
            $s->bindValue(':userID', $user_info['id']);
            $s->bindValue(':action', 'paid for badge');
            $s->execute();
            $purchase_info = $s->fetch();
            
            // display message
            $title = 'User Has Already Paid for Badge';
            $longdesc = "The email address you entered (" . $_POST['email'] . 
                    ") belongs to an existing user (" . $user_info['first_name'] .
                    " " . $user_info['last_name'] . ") who already purchased a
                    badge on " . date("m/d g:i a", strtotime($purchase_info['time'])) . ".";
            include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
            exit ();
        }
        
        // if user does not have a badge
        $title = 'User Already Registered';
        $longdesc = "The email address you entered (" . $_POST['email'] . 
                ") belongs to an existing user (" . $user_info['first_name'] .
                " " . $user_info['last_name'] . ") who has not purchased
                a badge.";
        include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
        exit ();
    }

    // checks to see if all required fields are set
    if (!isset($_POST['firstname'])
            or !isset($_POST['lastname'])
            or !isset($_POST['email'])
            or !isset($_POST['address1'])
            or !isset($_POST['city'])
            or !isset($_POST['state'])
            or !isset($_POST['zip'])) {
        $title = "Error";
        $longdesc = 'Please fill out all required fields.';
        include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
        exit();
    }
    
    // add new user without the password field
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
    verified    =   :verified';
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
    $s->bindValue(':verified', 1);
    $s->execute();

    // gives new user the password seen below
    // no one knows this password; the user will receive a password recovery
    // email in order to set the password they want
    $salt = generateSalt($_POST['email']);
    $password = generateHash($salt, 'dh48djs385hyf64grd5et4e5u6');
    $sql = 'UPDATE users SET
        password = :password
        WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->bindValue(':password', $password);
    $s->execute();

    // get the user id
    $id = userID($_POST['email']);

    // give user the role of 1
    $sql = 'INSERT INTO lookup_users_userroles (userID, roleID) VALUES
        (:id, 1)';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $id);
    $s->execute();
    
    // insert password recovery row into table
    $recovery_string = generateRandom32CharString();
    $created_time = date('Y-m-d H:i:s');
    $created_ip = $_SERVER['REMOTE_ADDR'];
    $expires_on = date('Y-m-d H:i:s', strtotime($created_time . ' + 7 days'));
    $sql = 'INSERT INTO password_recovery 
        SET email           = :email,
            recovery_string = :recovery_string,
            created_time    = :created_time,
            created_ip      = :created_ip,
            expires_on      = :expires_on';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->bindValue(':recovery_string', $recovery_string);
    $s->bindValue(':created_time', $created_time);
    $s->bindValue(':created_ip', $created_ip);
    $s->bindValue(':expires_on', $expires_on);
    $s->execute();
        
    // log the action
    logevent($id, NULL, 'password recovery created');
    
    // send recovery email
    $address = "server@simpleconvention.com";
    $subject = "Password Reset";
    $body = '<html><body><p>Click this link and paste the reset code below into
        the form provided.</p>' . '<p>
        <a href="localhost/demo2_con_app_template/public/index.php?reset_password">
        click</a></p>' . $recovery_string . '</body></html>';
    require '/home/simpleco/demo2/app/includes_php/send_mail.php'; 
    
    // log the action
    logevent($staffid, NULL, 'registered new user id ' . $id);
    
    // get userinfo from database
    $sql = 'SELECT * FROM users WHERE email = :email LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->execute();
    $user_info = $s->fetch();
        
    // displays payment page
    include '/home/simpleco/demo2/app/pages_registrationadmin/payforbadge.inc.html.php';
    exit();
}

/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                registering existing users                           |
 *                                                                     |
 * --------------------------------------------------------------------
 */

/*
 * responds to request to register an existing user
 */
if (isset($_GET['register_existing'])) {
    
    // create an array of search fields
    $sql = "SELECT column_name FROM information_schema.columns WHERE table_name='users'";
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $user_columns = $s->fetchall();
    
    include '/home/simpleco/demo2/app/pages_registrationadmin/register_existing_step1.inc.html.php';
    exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'search_users') {
    
    // if search by dropdown or search field is left blank
    if (($_POST['searchby'] == '') or ($_POST['search_text']) == '') {
        header("Location: .");
    }
    
    // search users with submitted criteria
    $select = 'SELECT * FROM ';
    $from = 'users ';
    $where = 'WHERE ' . $_POST['searchby'] . ' ';
    $like = 'LIKE' . '"%' . $_POST['search_text'] . '%"';
    $sql = $select . $from . $where . $like;
    $s = $pdo->prepare($sql);
    $s->execute();
    $search_result = $s->fetchall();
    
    include '/home/simpleco/demo2/app/pages_registrationadmin/register_existing_step2.inc.html.php';
    exit();
}

if (isset($_POST['action']) and $_POST['action'] == 'buy_badge') {
        
    // get userinfo from database
    $sql = 'SELECT * FROM users WHERE id = :id LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['userID']);
    $s->execute();
    $user_info = $s->fetch();
    
    // get current year number
    $con_info = getCurrentYearInfo();
    
    // check to see if this user already has a badge
    $sql = 'SELECT COUNT(*) FROM lookup_users_years
        WHERE userID = :userID AND yearID = :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    $paidforbadge = $s->fetch();
    if ($paidforbadge['COUNT(*)'] > 0) {
        $title = 'That User Already Has a Convention Badge';
        $longdesc = "Try searching by name or cell phone number to find the
            right account.  If you can't find it, don't worry.  Just make a new
            account.";
        include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
        exit ();
    }
        
    // displays payment page
    include '/home/simpleco/demo2/app/pages_registrationadmin/payforbadge.inc.html.php';
    exit();
}

/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                process credit card                                  |
 *                                                                     |
 * --------------------------------------------------------------------
 */

if (isset($_POST['stripeToken'])) {

    // gets user info
    $sql = 'SELECT * FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['customerID']);
    $s->execute();
    $user_info = $s->fetch();

    // charge the card
    Stripe::setApiKey("sk_test_y4j5rHbNnOXWIgtsMbQUvWFh");
    $token = $_POST['stripeToken'];
    $description = $user_info['email'] . ' bought badge';
    $charge = Stripe_Charge::create(array(
        "amount" => 2000, 
        "currency" => "usd",
        "card" => $token,
        "description" => $description));

    // get current year number
    $con_info = getCurrentYearInfo();

    // record that the user paid in lookup_users_years
    $sql = 'INSERT INTO lookup_users_years SET 
    userID        =   :userID,
    yearID       =   :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    
    // get the id of the staff member from the session
    $staffid = userID($_SESSION['email']);
    
    // log the action
    logevent($staffid, NULL, 'accepted card from id ' . $user_info['id'] . 
            ' for badge');
    
    // send recovery email
    $address = "server@simpleconvention.com";
    $subject = "Convention Badge Receipt";
    $body = '<html><body><p>Thank you for purchasing a badge for the convention.</p>' . 
            '<p>Put nice looking simple receipt here.</p></body></html>';
    require '/home/simpleco/demo2/app/includes_php/send_mail.php'; 
    
    // show confirmation
    $title = "Badge Purchased!";
    $longdesc = 'A badge has been purchased for ' . $user_info['first_name'] . 
            ' ' . $user_info['last_name'] . ' in the amount of $20.  A receipt
            has been emailed to ' . $user_info['email'] . '.';
    include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
    exit();
}

/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                process cash/check                                   |
 *                                                                     |
 * --------------------------------------------------------------------
 */
if (isset($_POST['action']) and $_POST['action'] == 'cash_or_check') {
    
    // gets user info
    $sql = 'SELECT * FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['customerID']);
    $s->execute();
    $user_info = $s->fetch();

    // get current year number
    $con_info = getCurrentYearInfo();

    // record that the user paid in lookup_users_years
    $sql = 'INSERT INTO lookup_users_years SET 
    userID        =   :userID,
    yearID       =   :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    
    // get the id of the staff member from the session
    $staffid = userID($_SESSION['email']);
    
    // log the action
    logevent($staffid, NULL, 'accepted cash or check from id ' . $user_info['id'] . 
            ' for badge');
    
    // send recovery email
    $address = "server@simpleconvention.com";
    $subject = "Convention Badge Receipt";
    $body = '<html><body><p>Thank you for purchasing a badge for the convention.</p>' . 
            '<p>Put nice looking simple receipt here.</p></body></html>';
    require '/home/simpleco/demo2/app/includes_php/send_mail.php'; 
    
    // show confirmation
    $title = "Badge Purchased!";
    $longdesc = 'A badge has been purchased for ' . $user_info['first_name'] . 
            ' ' . $user_info['last_name'] . ' in the amount of $20.  A receipt
            has been emailed to ' . $user_info['email'] . '.';
    include '/home/simpleco/demo2/app/pages_registrationadmin/confirmation.inc.html.php';
    exit();
}

/*
 * ----------------------------------------------------------------------------- DEFAULT
 */

include '/home/simpleco/demo2/app/pages_registrationadmin/default.inc.html.php';
exit();