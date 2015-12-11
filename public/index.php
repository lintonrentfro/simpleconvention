<?php

session_start();

//////////////////////////////////////////////////////////////////////////
// Public Controller
// 
// Includes
// Start Code
// Badge Purchases
// Logging In/Out
// Password Reset Generation
// Password Reset Code Entry
// My Account
// My Personal Info
// Registration
// Schedule
// Con Store
// Forums
// Guests
// Static Pages
//////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////
// Includes
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
// Badge Purchases
//////////////////////////////////////////////////////////////////////////

// regular badge purchase
if (isset($_GET['buybadge'])) {
    if (isset($_POST['stripeToken'])) {
        
        // check to see if session is set
        if (!isset($_SESSION['loggedIn'])) {
            $title = 'Please Log In';
            $longdesc = "You need to log in to buy a badge.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
            exit ();
        }
        
        // charge the card
        Stripe::setApiKey("sk_test_y4j5rHbNnOXWIgtsMbQUvWFh");
        $token = $_POST['stripeToken'];
        $description = $_SESSION['email'] . ' bought badge';
        $charge = Stripe_Charge::create(array(
            "amount" => $con_info['badge_price'], 
            "currency" => "usd",
            "card" => $token,
            "description" => $description));

        // record that the user paid in lookup_users_years
        $sql = 'INSERT INTO lookup_users_years SET 
        userID        =   :userID,
        yearID       =   :yearID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $user_info['id']);
        $s->bindValue(':yearID', $con_info['current_year']);
        $s->execute();
        
        // log the action
        logevent($user_info['id'], NULL, 'paid for badge');
        
        // convert the stored badge price in cents w/o formatting to a formatted string
        setlocale(LC_MONETARY, 'en_US');
        $price = money_format('%.2n', $con_info['badge_price']/100);
        
        // send receipt email
        $address = $_SESSION['email'];
        $subject = "Convention Badge Receipt";
        $body = '<html><body><p>Thank you for purchasing a badge for the convention ' . 
                'in the amount of ' . $price . '.</p>' . 
                '<p></p></body></html>';
        include $siteroot . 'demo2/app/includes_php/send_mail.php';

        // show confirmation
        $title = "Badge Purchased";
        $longdesc = 'You have purchased a badge for ' . $user_info['first_name'] . ' ' . 
                $user_info['last_name'] . ' in the amount of ' . $price . '.  A receipt was emailed to ' . $user_info['email'] . '.';
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }
    
    // if badges are not currently being sold, direct the user to a message
    if ($con_info['allow_badges'] == 0) {
        $title = 'Badges Are Not Currently For Sale';
        $longdesc = "Badges are not currently for sale.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // check to see if session is set
    if (!isset($_SESSION['loggedIn'])) {
        $title = 'Please Log In';
        $longdesc = "You need to log in to buy a badge.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // checks to see if the user has already bought a badge for current year
    $sql = 'SELECT COUNT(*) FROM lookup_users_years
        WHERE userID = :userID AND yearID = :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    $paidforbadge = $s->fetch();
    
    // convert the stored badge price in cents w/o formatting to a formatted string
    setlocale(LC_MONETARY, 'en_US');
    $price = money_format('%.2n', $con_info['badge_price']/100);
    $kidprice = money_format('%.2n', $con_info['kid_badge_price']/100);
    
    // display form
    include $siteroot . 'demo2/app/pages_public/buy_badge.inc.html.php';
    exit();
}

// show all badge purchases & allows registration of kids and purchase of kid badges
if (isset($_GET['mybadges'])) {
    
    if (isset($_POST['stripeToken'])) {
        
        // check to see if session is set
        if (!isset($_SESSION['loggedIn'])) {
            $title = 'Please Log In';
            $longdesc = "You need to log in to buy a badge.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
            exit ();
        }
        
        // make sure a child is selected
        if (!$_POST['childID']) {
            $title = 'No Child Selected';
            $longdesc = "Please select a child for a badge.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
            exit ();
        }
        
        // if kid badge is on, use that price; if kid badge is off, use full price
        if ($con_info['kid_badge_on'] == 0) {
            $amount_to_charge = $con_info['badge_price'];
        }
        else {
            $amount_to_charge = $con_info['kid_badge_price'];
        }
        
        
        // charge the card
        Stripe::setApiKey("sk_test_y4j5rHbNnOXWIgtsMbQUvWFh");
        $token = $_POST['stripeToken'];
        $description = $_SESSION['email'] . ' bought child badge';
        $charge = Stripe_Charge::create(array(
            "amount" => $amount_to_charge, 
            "currency" => "usd",
            "card" => $token,
            "description" => $description));

        // record that the child has a badge
        $sql = 'INSERT INTO lookup_users_years SET 
        userID        =   :userID,
        yearID       =   :yearID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $_POST['childID']);
        $s->bindValue(':yearID', $con_info['current_year']);
        $s->execute();
        
        // log the action
        logevent($user_info['id'], NULL, 'paid for badge for child with id ' . $_POST['childID']);
        
        // convert the stored badge price in cents w/o formatting to a formatted string
        setlocale(LC_MONETARY, 'en_US');
        $price_in_email = money_format('%.2n', $amount_to_charge/100);
        
        // send receipt email
        $address = 'lintonrentfro@gmail.com';
        $subject = "Convention Badge Receipt";
        $body = '<html><body><p>Thank you for purchasing a child badge for the convention ' . 
                'in the amount of ' . $price_in_email . '.</p>' . 
                '<p></p></body></html>';
        include $siteroot . 'demo2/app/includes_php/send_mail.php';

        // show confirmation
        $title = "Badge Purchased";
        $longdesc = 'You have purchased a child badge in the amount of ' . $price_in_email . '.  A receipt was emailed to lintonrentfro@gmail.com.';
        $longdesc .= '<p><a href="/?mybadges">return to your convention badges</a></p>';
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }
    
    if (isset($_POST['action']) and $_POST['action'] == 'free_badge') {

        // record that the child has a badge
        $sql = 'INSERT INTO lookup_users_years SET 
            userID        =   :userID,
            yearID       =   :yearID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $_POST['childID']);
        $s->bindValue(':yearID', $con_info['current_year']);
        $s->execute();

        header("Location: ." . '?mybadges');
        exit();
    }
    
    // check to see if session is set
    if (!isset($_SESSION['loggedIn'])) {
        $title = 'Please Log In';
        $longdesc = "You need to log in to view your badge purchase information.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // checks to see if the user has already bought a badge for current year
    $sql = 'SELECT COUNT(*) FROM lookup_users_years
        WHERE userID = :userID AND yearID = :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    $paidforbadge = $s->fetch();
    
    if ($paidforbadge['COUNT(*)'] == 1) {
        $primary_user_badge_status = '<i class="icon-ok icon-white"></i>';
    }
    if ($paidforbadge['COUNT(*)'] != 1) {
        $primary_user_badge_status = '<a href="?buybadge">buy</a>';
    }
    
    // gets all kids under this user
    $sql = '
        SELECT first_name, last_name, id
        FROM users
        WHERE parentID = :parentID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':parentID', $user_info['id']);
    $s->execute();
    $kids = $s->fetchall(PDO::FETCH_ASSOC);
    
    // build an array of those kids' ids and a badge status of 1 or 0
    foreach ($kids as $kid):
        $sql = 'SELECT COUNT(*) FROM lookup_users_years
            WHERE userID = :userID AND yearID = :yearID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $kid['id']);
        $s->bindValue(':yearID', $con_info['current_year']);
        $s->execute();
        $result = $s->fetch(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $badges[] = array(
                'id' => $kid['id'],
                'badge' => $row['COUNT(*)']);
        }
    endforeach;
    
    // build a final array of kids ids, names, and badge status
    $kids_with_badge_status = array();
    foreach ($kids as $kid):
        foreach ($badges as $badge):
            if (($kid['id'] == $badge['id']) and ($badge['badge'] == 1)) {
                $kids_with_badge_status[] = array('id' => $kid['id'], 'first_name' => $kid['first_name'], 'last_name' => $kid['last_name'], 'badge_status' => '<i class="icon-ok icon-white"></i>');
            }
            if (($kid['id'] == $badge['id']) and ($badge['badge'] == 0)) {
                $kids_with_badge_status[] = array('id' => $kid['id'], 'first_name' => $kid['first_name'], 'last_name' => $kid['last_name'], 'badge_status' => 'no badge');
            }
        endforeach;
    endforeach;
    
    // format prices
    setlocale(LC_MONETARY, 'en_US');
    $price = money_format('%.2n', $con_info['badge_price']/100);
    $kidprice = money_format('%.2n', $con_info['kid_badge_price']/100);
    
    // create age range strings for use in the html page
    if (($con_info['kid_badge_on'] == 1) and ($con_info['free_badge_on'] == 1)) {
        $kid_badge_age_range = $con_info['free_badge_max_age'] + 1 . '-' . $con_info['kid_badge_max_age'];
    }
    if (($con_info['kid_badge_on'] == 1) and ($con_info['free_badge_on'] == 0)) {
        $kid_badge_age_range = 'Under ' . $con_info['kid_badge_max_age'];
    }
    
    include $siteroot . 'demo2/app/pages_public/mybadges.inc.html.php';
    exit();
}

// add a child to a user's account
if (isset($_POST['action']) and $_POST['action'] == 'add_kid') {
    
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
    
    // checks to see if all required fields are set
    if (!isset($_POST['first_name'])
            or !isset($_POST['last_name'])) {
        $title = "Error";
        $longdesc = 'Please fill out all required fields.';
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }
    
    // gets parent info
    $sql = 'SELECT * FROM users WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['parentID']);
    $s->execute();
    $parent_info = $s->fetch();
    
    // create a random login and password
    $login = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
    $passwordtext = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
    
    // create new user row
    $sql = '
        INSERT INTO users 
        SET
            address1        =   :address1,
            address2        =   :address2,
            city            =   :city,
            state           =   :state,
            zip             =   :zip,
            first_name      =   :first_name,
            last_name       =   :last_name,
            verified        =   :verified,
            email           =   :email,
            parentID        =   :parentID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':first_name', $_POST['first_name']);
    $s->bindValue(':last_name', $_POST['last_name']);
    $s->bindValue(':parentID', $parent_info['id']);
    $s->bindValue(':address1', $parent_info['address1']);
    $s->bindValue(':address2', $parent_info['address2']);
    $s->bindValue(':city', $parent_info['city']);
    $s->bindValue(':state', $parent_info['state']);
    $s->bindValue(':zip', $parent_info['zip']);
    $s->bindValue(':verified', '1');
    $s->bindValue(':email', $login);
    $s->execute();
    
    // adds password hash
    $salt = generateSalt($login);
    $password = generateHash($salt, $passwordtext);
    $sql = 'UPDATE users SET
        password = :password
        WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $login);
    $s->bindValue(':password', $password);
    $s->execute();
    
    // get the user id
    $id = userID($login);

    // add user role of 1
    $sql = 'INSERT INTO lookup_users_userroles (userID, roleID) VALUES
        (:id, 1)';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $id);
    $s->execute();
    
    // send email with login/password for child
    $address = 'lintonrentfro@gmail.com';
    $subject = "Child Account Created";
    $body = '<html><body><p>You have created an account for your child on the convention website.</p>' . 
        '<p>Your child can log in to the convention website and register for events if you give them this login and password.  They will not have access to the forums or your account info.</p>' . 
        '<p>login: ' . $login . '</p><p>password: ' . $passwordtext . '</p><p>If you do not want your child to register for events, do not give them the login and password.</p></body></html>';
    require $siteroot . 'demo2/app/includes_php/send_mail.php';
    
    header("Location: ." . '?mybadges');
    exit();
}

//////////////////////////////////////////////////////////////////////////
// logging in/out
//////////////////////////////////////////////////////////////////////////

// responds to request to login and sends login form
if (isset($_GET['loginform'])) {
    include $siteroot . 'demo2/app/pages_public/login.inc.html.php';
    exit();
}

// takes login form data and checks it against database
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
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }

    if (userIsLoggedIn()) {
        
        // get the user id
        $id = userID($_SESSION['email']);
        
        // log the action
        logevent($id, NULL, 'good login');
        
        // reset the failed_logins field
        recordGoodLogin($id);
        
        // return to the previous page
        header("Location: .");
        exit();
    }
    else {
        
        // get the user id
        $id = userID($_POST['email']);
        
        // log the action
        logevent($id, NULL, 'bad login');
        
        // add to the failed_logins field
        recordBadLogin($id);
        
        // display error
        $title = "Incorrect Login";
        $longdesc = "That email/password combination does not belong to a 
            registered user or you have not verified your email address yet.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }
}

// responds to logout attempt
if (isset($_GET['logout'])) {
        
    // get the user id
    $id = userID($_SESSION['email']);

    // log the action
    logevent($id, NULL, 'logout');
    
    // logout
    unset($_SESSION['loggedIn']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    header("Location: index.php");
    exit();
}

//////////////////////////////////////////////////////////////////////////
// password reset code generation
//////////////////////////////////////////////////////////////////////////
if (isset($_GET['forgot_password'])) {
    include $siteroot . 'demo2/app/pages_public/lost_password.inc.html.php';
    exit ();
}

// responds to lost password form
if (isset($_POST['action']) and $_POST['action'] == 'lost_password_form') {
    
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
    
    // check to see if posted email belongs to a registered user
    $sql = 'SELECT COUNT(*) 
        FROM users 
        WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->execute();
    $result = $s->fetch();
    
    // if no matching email address is found
    if ($result['COUNT(*)'] < 1) {
        
        // log the action
        logevent(NULL, NULL, 'bad email for password recovery');
        
        // get user IP
        $userIP = $_SERVER['REMOTE_ADDR'];
        
        // get current time
        $current_time = date('Y-m-d H:i:s');
        
        // calculate time 24 hours ago
        $one_day_ago = date('Y-m-d H:i:s', strtotime($current_time . ' - 1 days'));
        
        // get number of guesses made from same IP in last 24 hours
        $sql = 'SELECT COUNT(*) 
            FROM log 
            WHERE userIP = :userIP AND time > :time AND action = :action';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userIP', $userIP);
        $s->bindValue(':time', $one_day_ago);
        $s->bindValue(':action', 'bad email for password recovery');
        $s->execute();
        $attempts = $s->fetch();
        
        // if guesses in last 24 hours are > 10, block further attempts from IP
        if ($attempts['COUNT(*)'] > 10) {
            
            // log the action
            logevent(NULL, NULL, 'ip banned');
            
            // display error message
            $title = "Your IP Address Has Been Banned";
            $longdesc = "We have detected more than 10 attempts from your IP to
                recover passwords for email addresses not found in our system
                during the previous 24 hours. Your IP address is now banned from
                logging into this website, recovering a password, or registering
                a new account.  If you feel this is a mistake, please contact
                the convention staff for assistance.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
            exit ();
            
        }
        
        // display error message
        $title = "Email Address Not Found";
        $longdesc = "That email address does not belong to a registered user.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // check to see if recovery code already exists for user
    $sql = 'SELECT COUNT(*) 
        FROM password_recovery 
        WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->execute();
    $result = $s->fetch();
    
    // if recovery code already exists
    if ($result['COUNT(*)'] > 0) {
        
        // get the recovery code's expiration
        $sql = 'SELECT expires_on  
            FROM password_recovery 
            WHERE email = :email';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $_POST['email']);
        $s->execute();
        $expires_on = $s->fetch();
        
        // if the expiration is greater than the current time, delete that row
        if (date('Y-m-d H:i:s') > $expires_on['expires_on']) {
            $sql = 'DELETE FROM password_recovery  
                WHERE email = :email';
            $s = $pdo->prepare($sql);
            $s->bindValue(':email', $_POST['email']);
            $s->execute();
        }
        
        // if the expiration still has some time left, display message
        $title = "Recovery Email Already Sent";
        $longdesc = "A password recovery email has already been sent to you.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }

    // insert password recovery row into table
    $recovery_string = generateRandom32CharString();
    $created_time = date('Y-m-d H:i:s');
    $created_ip = $_SERVER['REMOTE_ADDR'];
    $expires_on = date('Y-m-d H:i:s', strtotime($created_time . ' + 1 days'));
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
    
    // get the user id
    $id = userID($_POST['email']);
    
    // log the action
    logevent($id, NULL, 'password recovery created');
    
    // send recovery email
    $address = 'lintonrentfro@gmail.com';
    // $address = $_POST['email'];
    $subject = "Password Reset";
    $body = '<html><body><p>Click this link and paste the reset code below into
        the form provided.</p>' . '<p>
        <a href="localhost/index.php?reset_password">
        click</a></p>' . $recovery_string . '</body></html>';
    require $siteroot . 'demo2/app/includes_php/send_mail.php'; 
    
    // display confirmation
    $title = "Recovery Email Sent";
    $longdesc = "Check your email.  You should receive a message that will tell
        you how to reset your password.  This must be done within 24 hours.";
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit ();
}

//////////////////////////////////////////////////////////////////////////
// password reset code entry
//////////////////////////////////////////////////////////////////////////
if (isset($_GET['reset_password'])) {
    include $siteroot . 'demo2/app/pages_public/enter_code.inc.html.php';
    exit ();
}

// checks code
if (isset($_POST['action']) and $_POST['action'] == 'password_recovery') {
    
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
    
    // check to see if this is a valid code
    $sql = 'SELECT COUNT(*) 
        FROM password_recovery 
        WHERE recovery_string = :recovery_string';
    $s = $pdo->prepare($sql);
    $s->bindValue(':recovery_string', $_POST['recovery_code']);
    $s->execute();
    $result = $s->fetch();
    
    // if code does not exist
    if ($result['COUNT(*)'] < 1) {
        $title = "Recovery Code Not Found";
        $longdesc = "Did you type/paste that code correctly?  No such code was
            found.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // get the row for that code
    $sql = 'SELECT *  
        FROM password_recovery 
        WHERE recovery_string = :recovery_string';
    $s = $pdo->prepare($sql);
    $s->bindValue(':recovery_string', $_POST['recovery_code']);
    $s->execute();
    $code_info = $s->fetch();

    // if code is expired, delete row and display message
    if (date('Y-m-d H:i:s') > $code_info['expires_on']) {
        $sql = 'DELETE FROM password_recovery  
            WHERE recovery_string = :recovery_string';
        $s = $pdo->prepare($sql);
        $s->bindValue(':recovery_string', $_POST['recovery_code']);
        $s->execute();
        $title = "Recovery Code Expired";
        $longdesc = "That password recovery code has expired.  They only last 24
            hours.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // if code has not expired, provide form to reset password
    if (date('Y-m-d H:i:s') < $code_info['expires_on']) {
        $email = $code_info['email'];
        $recovery_string = $_POST['recovery_code'];
        include $siteroot . 'demo2/app/pages_public/reset_password.inc.html.php';
        exit ();
    }
}

// reset password
if (isset($_POST['action']) and $_POST['action'] == 'reset_password') {
    
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
    
    // if both password fields aren't the same
    if ($_POST['password'] != $_POST['password2']) {
    $title = "Error";
    $longdesc = 'The passwords did not match.';
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit();
    }
    
    // delete password recovery code's row
    $sql = 'DELETE FROM password_recovery  
            WHERE recovery_string = :recovery_string';
    $s = $pdo->prepare($sql);
    $s->bindValue(':recovery_string', $_POST['recovery_string']);
    $s->execute();
    
    // set new password for user
    $salt = generateSalt($_POST['email']);
    $password = generateHash($salt, $_POST['password']);
    $sql = 'UPDATE users SET
        password = :password
        WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->bindValue(':password', $password);
    $s->execute();
    
    // get the user id
    $id = userID($_POST['email']);
        
    // log the action
    logevent($id, NULL, 'password reset');
    
    // display confirmation
    $title = "Password Reset";
    $longdesc = "Your password has been reset.";
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit ();
}

//////////////////////////////////////////////////////////////////////////
// my schedule
//////////////////////////////////////////////////////////////////////////
if (isset($_GET['myschedule'])) {
    
    // if schedule should not be shown, user directed to a message
    if ($con_info['schedule_shown'] == 0) {
        $title = 'Not Available Yet';
        $longdesc = "When event registration begins, this is where you'll find
            your schdule.  It will show the events you've signed up for, the
            status of any events you're running, and any convention duties you
            have if you're part of the convention staff.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }

    // check to see if session is set
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            $title = 'Please Log In';
            $longdesc = "You need to log in to view your convention schedule.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
            exit ();
        }
    }
    
    // gets events the user is running
    $sql = 'SELECT * 
        FROM events 
        WHERE contact = :contact';
    $s = $pdo->prepare($sql);
    $s->bindValue(':contact', $user_info['id']);
    $s->execute();
    $result = $s->fetchall();
    foreach ($result as $row) {
        $events_running[] = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'building' => $row['building'],
            'room' => $row['room'],
            'start' => $row['start'],
            'end' => $row['end'],
            'type' => $row['type'],
            'currentusers' => $row['currentusers'],
            'maxusers' => $row['maxusers'],
            'status' => $row['status']);
    }
    
    // gets number of events user is running
    $sql = 'SELECT COUNT(*) FROM events
        WHERE contact = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $eventsrunningnumber = $s->fetch();
    
    // gets if the user has paid for a badge or not
    $sql = 'SELECT COUNT(*) FROM lookup_users_years
        WHERE userID = :userID AND yearID = :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    $paidforbadge = $s->fetch();
    
    // gets events for which the user is registered
    $sql = 'SELECT id, name, start, end, building, room, currentusers, maxusers, 
            type, registration_required, can_conflict 
        FROM lookup_users_events 
        INNER JOIN events ON eventID = id 
        WHERE userID = :userID
        ORDER BY start ASC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
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
            'maxusers' => $row['maxusers'],
            'registration_required' => $row['registration_required']);
    }
    
    // gets number of events for which user is registered
    $sql = 'SELECT COUNT(*) FROM lookup_users_events
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $eventsregisterednumber = $s->fetch();
    
    // gets events for which the user is a helper
    $sql = 'SELECT duty_roster.start, duty_roster.end, events.name, 
        events.building, events.room, events.id, duty_roster.description  
        FROM duty_roster 
        INNER JOIN events 
        ON duty_roster.eventID = events.id 
        WHERE duty_roster.userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $result = $s->fetchall();
    foreach ($result as $row) {
        $events_helper[] = array(
            'events.id' => $row['id'],
            'events.name' => $row['name'],
            'events.building' => $row['building'],
            'events.room' => $row['room'],
            'duty_roster.start' => $row['start'],
            'duty_roster.description' => $row['description'],
            'duty_roster.end' => $row['end']);
    }
    
    // gets number of duty roster items for this user
    $sql = 'SELECT COUNT(*) FROM duty_roster
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $eventshelpernumber = $s->fetch();
    
    // check if user is a guest
    $sql = 'SELECT COUNT(*) FROM guests
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $user_is_guest_or_not = $s->fetch();
    
    // get all info for events on schedule for guest
    $sql = '
        SELECT *
        FROM lookup_guests_events
        INNER JOIN events
        ON eventID = events.id
        WHERE userID = :userID and events.status = "on schedule - all clear"
        ORDER BY start ASC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $events_as_guest = $s->fetchall(PDO::FETCH_ASSOC);    
    
    include $siteroot . 'demo2/app/pages_public/myschedule.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// my personal info
//////////////////////////////////////////////////////////////////////////
if (isset($_GET['mypersonalinfo'])) {
    
    // check to see if session is set
    if (!isset($_SESSION['loggedIn'])) {
        $title = 'Please Log In';
        $longdesc = "You need to log in to view your account information.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // check if user is a guest
    $sql = 'SELECT COUNT(*) FROM guests
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $user_is_guest_or_not = $s->fetch();
    
    // get guest info for user
    $sql = 'SELECT * FROM guests WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $guest_info = $s->fetch(PDO::FETCH_ASSOC);  
    
    include $siteroot . 'demo2/app/pages_public/mypersonalinfo.inc.html.php';
    exit();
}

// responds to request to update guest info
if (isset($_POST['action']) and $_POST['action'] == 'update_guest_info') {
    $sql = '
        UPDATE guests
        SET
            short_description = :short_description,
            full_description = :full_description,
            professional_name = :professional_name
        WHERE
            guestID = :guestID
        LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':short_description', $_POST['short_description']);
    $s->bindValue(':full_description', $_POST['full_description']);
    $s->bindValue(':professional_name', $_POST['professional_name']);
    $s->bindValue(':guestID', $_POST['guestID']);
    $s->execute();
        
    // display confirmation
    $title = "Guest Information Updated";
    $longdesc = 'You have successfully updated your guest information';
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit();
    
}

// responds to request from user to update own info
if (isset($_POST['action']) and $_POST['action'] == 'update_myuserinfo') {
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
        $error = 'Error updating your information!';
        include $siteroot . 'demo2/app/pages_public/error.inc.html.php';
        exit();
    }
    
    // log the action
    logevent($_POST['id'], NULL, 'personal info modified');
    
    // display confirmation
    $title = "Information Updated";
    $longdesc = 'You have successfully updated your information';
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// registration
//////////////////////////////////////////////////////////////////////////

// responds to request to register and sends registration form
if (isset($_GET['register'])) {
    // check to see if session is set
    if (isset($_SESSION['loggedIn'])) {
        $title = 'Please log out';
        $longdesc = "You need to log out in order to register a new account.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    include $siteroot . 'demo2/app/pages_public/register.inc.html.php';
    exit();
}

// create new user
if (isset($_POST['action']) and $_POST['action'] == 'registerform') {
    
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
    
    // checks to see if password fields match
    if ($_POST['password'] != $_POST['password2']) {
        $title = "Error";
        $longdesc = 'The passwords did not match.';
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }
    
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
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }

    // checks to see if all required fields are set or blank
    if (!isset($_POST['firstname'])
            or ($_POST['firstname'] == '')
            or !isset($_POST['lastname'])
            or ($_POST['lastname'] == '')
            or !isset($_POST['email'])
            or ($_POST['email'] == '')
            or !isset($_POST['address1'])
            or ($_POST['address1'] == '')
            or !isset($_POST['city'])
            or ($_POST['city'] == '')
            or !isset($_POST['state'])
            or ($_POST['state'] == '')
            or !isset($_POST['zip'])
            or ($_POST['zip'] == '')
            or ($_POST['password'] == '')
            or !isset($_POST['password'])) {
        $title = "Error";
        $longdesc = 'Please fill out all required fields.';
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
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
    home        =   :home,
    work        =   :work,
    verified    =   :verified,
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
    $s->bindValue(':verified', 0);
    $s->execute();

    // adds password hash
    $salt = generateSalt($_POST['email']);
    $password = generateHash($salt, $_POST['password']);
    $sql = 'UPDATE users SET
        password = :password
        WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->bindValue(':password', $password);
    $s->execute();

    // get the user id
    $id = userID($_POST['email']);

    // add user role of 1
    $sql = 'INSERT INTO lookup_users_userroles (userID, roleID) VALUES
        (:id, 1)';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $id);
    $s->execute();
    
    // create an entry in the user_verification table
    $verification_string = generateRandom32CharString();
    $created_time = date('Y-m-d H:i:s');
    $created_ip = $_SERVER['REMOTE_ADDR'];
    $expires_on = date('Y-m-d H:i:s', strtotime($created_time . ' + 7 days'));
    $sql = 'INSERT INTO user_verification SET
        email               =   :email,
        verification_string =   :verification_string,
        created_time        =   :created_time,
        created_ip          =   :created_ip,
        expires_on          =   :expires_on';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_POST['email']);
    $s->bindValue(':verification_string', $verification_string);
    $s->bindValue(':created_time', $created_time);
    $s->bindValue(':created_ip', $created_ip);
    $s->bindValue(':expires_on', $expires_on);
    $s->execute();
    
    // send account verification email
    $address = $_POST['email'];
    $subject = "Account Verification";
    $body = '<html><body><p>Click this link and paste the code below into the form provided.</p>' . 
        '<p><a href="http://demo.simpleconvention.com/?verify_email">click</a></p>' . 
        '<p>If the link is not visible, paste this into your broswer: http://demo.simpleconvention.com/?verify_email' . '</p>' . 
        $verification_string . '</body></html>';
    require $siteroot . 'demo2/app/includes_php/send_mail.php';
    
    // log the action
    logevent($id, NULL, 'registered');
    
    // display confirmation
    $title = "Email Verification Message Sent";
    $longdesc = 'An email has been sent to the address you provided.  Follow its
        instructions to verify your account within the next 7 days and log in.';
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit();
}

// email verification form
if (isset($_GET['verify_email'])) {
    include $siteroot . 'demo2/app/pages_public/verify_email.inc.html.php';
    exit();
}

// check email verification code
if (isset($_POST['action']) and $_POST['action'] == 'email_verification') {
    
    // check to see if this is a valid code
    $sql = 'SELECT COUNT(*) 
        FROM user_verification 
        WHERE verification_string = :verification_string';
    $s = $pdo->prepare($sql);
    $s->bindValue(':verification_string', $_POST['verification_code']);
    $s->execute();
    $result = $s->fetch();
    
    // if code does not exist
    if ($result['COUNT(*)'] < 1) {
        $title = "Verification Code Not Found";
        $longdesc = "Did you type/paste that code correctly?  No such code was found.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // get the row for that code
    $sql = 'SELECT *  
        FROM user_verification 
        WHERE verification_string = :verification_string';
    $s = $pdo->prepare($sql);
    $s->bindValue(':verification_string', $_POST['verification_code']);
    $s->execute();
    $code_info = $s->fetch();

    // if code is expired
    if (date('Y-m-d H:i:s') > $code_info['expires_on']) {
        
        // delete user verification row
        $sql = 'DELETE FROM user_verification  
            WHERE verification_string = :verification_string';
        $s = $pdo->prepare($sql);
        $s->bindValue(':verification_string', $_POST['verification_code']);
        $s->execute();
        
        // delete user
        $sql = 'DELETE FROM users  
            WHERE email = :email';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $code_info['email']);
        $s->execute();
        
        // display confirmation
        $title = "Verification Code Expired";
        $longdesc = "That email verification code has expired.  They only
            last 7 days.  You must re-register.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // if code has not expired
    if (date('Y-m-d H:i:s') < $code_info['expires_on']) {
        
        // update the user row so it's verified
        $sql = 'UPDATE users SET
            verified = :verified WHERE
            email    = :email LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $code_info['email']);
        $s->bindValue(':verified', 1);
        $s->execute();
        
        // delete user verification row
        $sql = 'DELETE FROM user_verification  
            WHERE verification_string = :verification_string';
        $s = $pdo->prepare($sql);
        $s->bindValue(':verification_string', $_POST['verification_code']);
        $s->execute();
        
        // display confirmation
        $title = "User Verified";
        $longdesc = "Your email has been verified.  You may now log in.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
}

//////////////////////////////////////////////////////////////////////////
// schedule
//////////////////////////////////////////////////////////////////////////

// shows static event info page when events are not "live" to the public
if (isset($_GET['events'])) {
    include $siteroot . 'demo2/app/pages_public/default_events_not_live.inc.html.php';
    exit();
}

// responds to request to view events of a certain type
if (isset($_GET['action']) and $_GET['action'] == 'view_events_of_type') {
    
    // if schedule should not be shown, user directed to "off_season" page
    if ($con_info['schedule_shown'] == 0) {
        include $siteroot . 'demo2/app/pages_public/default_events_not_live.inc.html.php';
        exit();
    }
    
    if ($_GET['type'] == 'all') {
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
        $event_type_shown = 'all events';
        } else {
        // get all info for all events of type requested
        $sql = 'SELECT * FROM events WHERE 
            type=:type AND status="on schedule - all clear" 
            ORDER BY start';
        $s = $pdo->prepare($sql);
        $s->bindValue(':type', $_GET['type']);
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
        $event_type_shown = $_GET['type'];
    }

    // check to see if session is set
    if (isset($_SESSION['loggedIn'])) {

        // get list of events for which user is registered
        $sql = 'SELECT eventID  
            FROM lookup_users_events 
            WHERE userID = :userID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $user_info['id']);
        $s->execute();
        $result = $s->fetchall(PDO::FETCH_ASSOC);
        $registered_events = array();
        foreach ($result as $row):
            $registered_events[] = $row['eventID'];
        endforeach;
    }

    include $siteroot . 'demo2/app/pages_public/view_type_of_event.inc.html.php';
    exit();
}

// responds to request to view events for a specific guest
if (isset($_GET['action']) and $_GET['action'] == 'view_events_of_guest') {
    
    // if schedule should not be shown, user directed to "off_season" page
    if ($con_info['schedule_shown'] == 0) {
        include $siteroot . 'demo2/app/pages_public/default_events_not_live.inc.html.php';
        exit();
    }
    
    // check to see if session is set
    if (isset($_SESSION['loggedIn'])) {

        // get list of events for which user is registered
        $sql = 'SELECT eventID  
            FROM lookup_users_events 
            WHERE userID = :userID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $user_info['id']);
        $s->execute();
        $result = $s->fetchall(PDO::FETCH_ASSOC);
        $registered_events = array();
        foreach ($result as $row):
            $registered_events[] = $row['eventID'];
        endforeach;
    }
    
    // get guest info
    $sql = 'SELECT * FROM guests WHERE userID = :userID LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['userID']);
    $s->execute();
    $guest_info = $s->fetch(PDO::FETCH_ASSOC);
    
    $event_type_shown = 'All Events for ' . $guest_info['professional_name'];
    
    // get all info for events on schedule for guest
    $sql = '
        SELECT *
        FROM lookup_guests_events
        INNER JOIN events
        ON eventID = events.id
        WHERE userID = :userID and events.status = "on schedule - all clear"
        ORDER BY start ASC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $_GET['userID']);
    $s->execute();
    $events = $s->fetchall(PDO::FETCH_ASSOC);

    // display results
    include $siteroot . 'demo2/app/pages_public/view_events_for_guest.inc.html.php';
    exit();
}

// responds to request to view all details of one event
if (isset($_GET['action']) and $_GET['action'] == 'event') {
    
    // if schedule should not be shown, user directed to "off_season" page
    if ($con_info['schedule_shown'] == 0) {
        include $siteroot . 'demo2/app/pages_public/default_events_not_live.inc.html.php';
        exit();
    }
    
    // check to see if session is set
    if (isset($_SESSION['loggedIn'])) {

        // get list of events for which user is registered
        $sql = 'SELECT eventID  
            FROM lookup_users_events 
            WHERE userID = :userID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $user_info['id']);
        $s->execute();
        $result = $s->fetchall(PDO::FETCH_ASSOC);
        $registered_events = array();
        foreach ($result as $row):
            $registered_events[] = $row[eventID];
        endforeach;
    }
    
    // get info for requested event
    $sql = 'SELECT events.id, name, building, room, start, end, type, contact, 
        currentusers, maxusers, description, first_name, last_name, email, 
        registration_required, contact_email_displayed, status, shoutbox
        FROM events INNER JOIN users ON events.contact = users.id 
        WHERE events.id=:id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_GET['id']);
    $s->execute();
    $event = $s->fetch();
    
    // get the user id
    $id = userID($_SESSION['email']);
    
    // log the action
    logevent($id, $_GET['id'], 'viewed');
    
    include $siteroot . 'demo2/app/pages_public/view_one_event.inc.html.php';
    exit();
}

// responds to request to put an event on the user's itinerary
if (isset($_POST['action']) and $_POST['action'] == 'put_event_on_itinerary') {
    
    // check to see if user is logged in
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            include $siteroot . '/demo2/app/pages_public/login.inc.html.php';
            exit ();
        }
    }
    
    // get the user's id from the email stored in session
    $id = userID($_SESSION['email']);
    
    // check to see if user has paid for badge
    $sql = 'SELECT COUNT(*) FROM lookup_users_years
        WHERE userID = :userID AND yearID = :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $id);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    $paidforbadge = $s->fetch();
    
    // if user has not paid for a badge for the current year, they are directed
    // to a message displaying this and not allowed to register for an event
    if ($paidforbadge['COUNT(*)'] < 1) {
        $title = "You Need a Convention Badge!";
        $longdesc = "Please purchase a badge for this year's convention.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
     }
    
    // see if the user has already put that event on their itinerary
    $sql = 'SELECT COUNT(*) FROM lookup_users_events
        WHERE userID = :userID AND eventID = :eventID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $id);
    $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    $row = $s->fetch();
    if ($row[0] > 0) {
        $title = "It's Already on Your Itinerary";
        $longdesc = "You have already put that event on your itinerary.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // get event start and end for all events user has already registered for
    // which can conflict with the new event
    $sql = 'SELECT name, start, end 
        FROM lookup_users_events
        LEFT JOIN events
        ON eventID = id 
        WHERE userID = :userID
            AND can_conflict = 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $id);
    $s->execute();
    $registered_events = $s->fetchall(PDO::FETCH_ASSOC);
    
    // get info for the new event
    $sql = 'SELECT name, start, end, can_conflict  
        FROM events
        WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $new_event = $s->fetch();
    
    // if event is set to check for time conflicts, check for them
    if ($new_event['can_conflict'] == 1) {
        foreach ($registered_events as $row):
            if ( (($row['start'] < $new_event['start']) AND ($row['end'] > $new_event['start'])) or
                (($row['end'] > $new_event['end']) AND ($row['start'] < $new_event['end'])) or
                (($row['start'] > $new_event['start']) AND ($row['end'] < $new_event['end'])) ) {
                $title = "Event Conflict";
                $longdesc = 'Sorry, but "' . $new_event['name'] . '" conflicts with "' . 
                        $row['name'] . '".';
                include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
                exit ();
            }
        endforeach;
    }
    
    
    // register the user for the event (put it on their itinerary)
    $sql = 'INSERT INTO lookup_users_events SET 
        userID        =   :userID,
        eventID       =   :eventID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $id);
        $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    
    // log the action
    logevent($id, $_POST['id'], 'on itinerary');
    
    // display registration confirmation
    $title = "Event is Now on Your Itinerary";
    $longdesc = "That event is now on your itinerary.";
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit ();
}

// responds to request to register for an event
if (isset($_POST['action']) and $_POST['action'] == 'register_for_event') {
    
    // check to see if user is logged in
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            include $siteroot . '/demo2/app/pages_public/login.inc.html.php';
            exit ();
        }
    }
    
    // get the user's id from the email stored in session
    $id = userID($_SESSION['email']);
    
    // check to see if user has paid for badge
    $sql = 'SELECT COUNT(*) FROM lookup_users_years
        WHERE userID = :userID AND yearID = :yearID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $id);
    $s->bindValue(':yearID', $con_info['current_year']);
    $s->execute();
    $paidforbadge = $s->fetch();
    
    // if user has not paid for a badge for the current year, they are directed
    // to a message displaying this and not allowed to register for an event
    if ($paidforbadge['COUNT(*)'] < 1) {
        $title = "You Need a Convention Badge!";
        $longdesc = "Please purchase a badge for this year's convention.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
     }
    
    // see if the user has already registered for that event
    $sql = 'SELECT COUNT(*) FROM lookup_users_events
        WHERE userID = :userID AND eventID = :eventID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $id);
    $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    $row = $s->fetch();
    if ($row[0] > 0) {
        $title = "Already Registered";
        $longdesc = "You have already registered for that event.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // get the current and max seats and name for event
    $sql = 'SELECT currentusers, maxusers, name FROM events WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $event = $s->fetch();
    
    // check to see if event has any room left
    if ($event['maxusers'] - $event['currentusers'] < 1) {
        
        // log the action
        logevent($id, $_POST['id'], 'event full');
        
        // display confirmation
        $title = "Event is Full";
        $longdesc = "Sorry, but " . $event['name'] . " is full.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // get event start and end for all events user has already registered for
    // that can conflict with the new event
    $sql = 'SELECT name, start, end 
        FROM lookup_users_events
        LEFT JOIN events
        ON eventID = id 
        WHERE userID = :userID
            AND can_conflict = 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $id);
    $s->execute();
    $registered_events = $s->fetchall();
    
    // get info for the new event
    $sql = 'SELECT name, start, end, can_conflict  
        FROM events
        WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    $new_event = $s->fetch();
    
    // check to see if new event time would conflict with the time for any
    // events for which the user has already registered
    if ($new_event['can_conflict'] == 1) {
        foreach ($registered_events as $row):
            if ( (($row['start'] < $new_event['start']) AND ($row['end'] > $new_event['start'])) or
                (($row['end'] > $new_event['end']) AND ($row['start'] < $new_event['end'])) or
                (($row['end'] > $new_event['start']) AND ($row['end'] < $new_event['end'])) or
                (($row['start'] > $new_event['start']) AND ($row['end'] < $new_event['end'])) ) {
                $title = "Event Conflict";
                $longdesc = 'Sorry, but "' . $new_event['name'] . '" conflicts with "' . 
                        $row['name'] . '".';
                include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
                exit ();
            }
        endforeach;
    }

    // register the user for the event
    $sql = 'INSERT INTO lookup_users_events SET 
        userID        =   :userID,
        eventID       =   :eventID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $id);
        $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    
    // update the event's currentusers field
    $sql = 'UPDATE events SET currentusers = currentusers + 1 
        WHERE id = :id LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    
    // log the action
    logevent($id, $_POST['id'], 'registered');
    
    // display registration confirmation
    $title = "Registered!";
    $longdesc = "You are now registered for that event.";
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit ();
}

// responds to request from user for "more events like this"
if (isset($_POST['action']) and $_POST['action'] == 'overflow_notification') {
    
    // check to see if user is logged in
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            include $siteroot . 'demo2/app/pages_public/login.inc.html.php';
            exit ();
        }
    }
    
    // get the user id
    $id = userID($_SESSION['email']);
    
    // check to see if the user has already requested more events like this for
    // this particular event id
    $sql = 'SELECT COUNT(*) 
        FROM log
        WHERE   userID  = :userID AND
                eventID = :eventID AND
                action = :action';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $id);
    $s->bindValue(':eventID', $_POST['id']);
    $s->bindValue(':action', 'overflow');
    $s->execute();
    $row = $s->fetch();
    if ($row['COUNT(*)'] > 0){
        $title = "Sorry";
        $longdesc = "We have already received a request from you to make more events like this.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit ();
    }

    // log the action
    logevent($id, $_POST['id'], 'overflow');

    $title = "Message Received";
    $longdesc = "Thank you for your feedback.  We will try to add another event like this.";
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit ();
}

// responds to request to remove event from itinerary
if (isset($_POST['action']) and $_POST['action'] == 'remove_event_from_itinerary') {
    
    // get the user id
    $id = userID($_SESSION['email']);
    
    // de-register the user for the event
    $sql = 'DELETE FROM lookup_users_events WHERE 
        userID        =   :userID AND
        eventID       =   :eventID LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $id);
        $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
        
    // log the action
    logevent($id, $_POST['id'], 'de-registered');

    // display registration confirmation
    $title = "Removed From Itinerary";
    $longdesc = "That event has been removed from your itinerary.";
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit ();
}

// responds to request to un-register from an event
if (isset($_POST['action']) and $_POST['action'] == 'un_register_for_event') {
    
    // check to see if user is logged in
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            include $siteroot . 'demo2/app/pages_public/login.inc.html.php';
            exit ();
        }
    }
    
    // get the user's id from the email stored in session
    $id = userID($_SESSION['email']);
    
    // make sure the user has registered for that event
    $sql = 'SELECT COUNT(*) FROM lookup_users_events
        WHERE userID = :userID AND eventID = :eventID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $id);
    $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    $row = $s->fetch();
    if ($row[0] < 1) {
        $title = "Error Processing Request";
        $longdesc = "You are not registered for that event.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    // de-register the user for the event
    $sql = 'DELETE FROM lookup_users_events WHERE 
        userID        =   :userID AND
        eventID       =   :eventID LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $id);
        $s->bindValue(':eventID', $_POST['id']);
    $s->execute();
    
    // update the event's currentusers field
    $sql = 'UPDATE events SET currentusers = currentusers - 1 
        WHERE id = :id LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
    
    // log the action
    logevent($id, $_POST['id'], 'de-registered');

    // display registration confirmation
    $title = "De-Registered!";
    $longdesc = "You are now de-registered for that event.";
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit ();
}

// responds to request to create an event from the general public
if (isset($_POST['action']) and $_POST['action'] == 'create_event') {
    // check to see if user is logged in
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            include $siteroot . 'demo2/app/pages_public/login.inc.html.php';
            exit ();
        }
    }
    
    // get the user's id from the email stored in session
    $id = userID($_SESSION['email']);
    
    // get the list of event types
    $result = $pdo->query('SELECT event_type_desc FROM event_types
        ORDER BY event_type_desc ASC');
    foreach ($result as $row) {
        $types[] = array(
            'event_type_desc' => $row['event_type_desc']);
    }
    
    // get the range of dates for the upcoming convention
    $sql = 'SELECT start, end 
        FROM static_con_info
        INNER JOIN years 
        ON current_year = years.id
        WHERE   current_year  = current_year';
    $s = $pdo->prepare($sql);
    $s->execute();
    $con_dates = $s->fetch(PDO::FETCH_ASSOC);

    include $siteroot . 'demo2/app/pages_public/create_event.inc.html.php';
    exit();
}

// responds to data from event creation form from the general public
if (isset($_POST['action']) and $_POST['action'] == 'submit_event') {
    
    //convert the user submitted start & end to datetime format for mysql
    $start = date_create($_POST['start']);
    $mysql_start = date_format($start, 'Y-m-d H:i:s');
    $end = date_create($_POST['end']);
    $mysql_end = date_format($end, 'Y-m-d H:i:s');

    try {
        $sql = 'INSERT INTO events SET
        name                    =   :name,
        start                   =   :start,
        end                     =   :end,
        type                    =   :type,
        contact                 =   :contact,
        contact_email_displayed =   :contact_email_displayed, 
        maxusers                =   :maxusers,
        status                  =   :status,
        registration_required   =   :registration_required,
        year_id                 =   :year_id, 
        description             =   :description';
        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $_POST['name']);
        $s->bindValue(':start', $mysql_start);
        $s->bindValue(':end', $mysql_end);
        $s->bindValue(':type', $_POST['type']);
        $s->bindValue(':contact', $_POST['contact']);
        $s->bindValue(':contact_email_displayed', $_POST['contact_email_displayed']);
        $s->bindValue(':maxusers', $_POST['maxusers']);
        $s->bindValue(':status', $_POST['status']);
        $s->bindValue(':description', $_POST['description']);
        $s->bindValue(':year_id', $con_info['current_year']);
        $s->bindValue(':registration_required', $_POST['registration_required']);
        $s->execute();
    }
    catch (PDOException $e) {
        $error = 'Error adding event!';
        include $siteroot . 'demo2/app/pages_public/error.inc.html.php';
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
    $s->bindValue(':start', $mysql_start);
    $s->bindValue(':end', $mysql_end);
    $s->bindValue(':contact', $_POST['contact']);
    $s->bindValue(':description', $_POST['description']);
    $s->execute();
    $event_id = $s->fetch();
    
    // log the action
    logevent($_POST['contact'], $event_id['id'], 'created event');
    
    $title = "Event Added";
    $longdesc = "You have successfully created an event (pending approval).";
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit();
}

// responds to attempt to comment on an event
if (isset($_POST['action']) and $_POST['action'] == 'create_comment') {
    
    // if user isn't logged in, display message
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            $title = 'Please Log In';
            $longdesc = "You need to log in to comment on an event.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
            exit ();
        }
    }
    
    // get the user's id from the email stored in session
    $userID = userID($_SESSION['email']);
    
    // get the event id from the post data
    $eventID = $_POST['id'];
    
    // sanitize the comment
    $comment = html($_POST['comment']);
    
    // create the comment
    shoutboxComment($eventID, $userID, $comment);
    
    // log the action
    logevent($userID, $eventID, 'created comment');
    
    // display confirmation
    $title = "Comment Added";
    $longdesc = "Your comment was successfully created.";
    include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
    exit();
}

//////////////////////////////////////////////////////////////////////////
// con store
//////////////////////////////////////////////////////////////////////////

// show store
if (isset($_GET['constore'])) {
    
    $sql = 'SELECT * FROM store_categories';
    $s = $pdo->query($sql);
    $categories = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_public/store.inc.html.php';
    exit();
}

// show category of items
if (isset($_GET['action']) and $_GET['action'] == 'search_items_by_category') {
    
    $sql = 'SELECT * FROM store_categories';
    $s = $pdo->query($sql);
    $categories = $s->fetchall(PDO::FETCH_ASSOC);
    
    if ($_GET['category'] == 'All') {
        $sql = 'SELECT * FROM store_items';
        $s = $pdo->query($sql);
        $s->execute();
        $items = $s->fetchall(PDO::FETCH_ASSOC);
        
        include $siteroot . 'demo2/app/pages_public/store.inc.html.php';
        exit();
    }

    $sql = 'SELECT * FROM store_items WHERE category = :category';
    $s = $pdo->prepare($sql);
    $s->bindValue(':category', $_GET['category']);
    $s->execute();
    $items = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_public/store.inc.html.php';
    exit();
}

// show item details
if (isset($_GET['action']) and $_GET['action'] == 'item') {
    
    $sql = 'SELECT * FROM store_items WHERE itemID = :itemID LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':itemID', $_GET['itemID']);
    $s->execute();
    $item = $s->fetch(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_public/store_view_item.inc.html.php';
    exit();
}

// add item to cart
if (isset($_POST['action']) and $_POST['action'] == 'add_to_cart') {
    
    // check to see if session is set
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            $title = 'Please Log In';
            $longdesc = "You need to log in to buy things.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
            exit ();
        }
    }
        
    $userID = userID($_SESSION['email']);
    
    $sql = 'INSERT INTO store_carts SET
        itemID   = :itemID, 
        userID   = :userID,
        quantity = :quantity';
    $s = $pdo->prepare($sql);
    $s->bindValue(':itemID', $_POST['itemID']);
    $s->bindValue(':userID', $userID);
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->execute();
    
    header("Location: ." . '?cart');
    exit();
}

// update cart
if (isset($_POST['action']) and $_POST['action'] == 'update_cart') {
        
    if ($_POST['quantity'] == 0) {
        $sql = 'DELETE FROM store_carts 
            WHERE ID = :ID LIMIT 1';
        $s = $pdo->prepare($sql);
        $s->bindValue(':ID', $_POST['ID']);
        $s->execute();
    
        header("Location: ." . '?cart');
        exit();
    }
    
    $sql = 'UPDATE store_carts SET
        quantity = :quantity
        WHERE ID = :ID LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':ID', $_POST['ID']);
    $s->bindValue(':quantity', $_POST['quantity']);
    $s->execute();
    
    header("Location: ." . '?cart');
    exit();
}

// delete cart
if (isset($_POST['action']) and $_POST['action'] == 'remove_cart') {
        
    $sql = 'DELETE FROM store_carts 
        WHERE ID = :ID LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':ID', $_POST['ID']);
    $s->execute();
    
    header("Location: ." . '?cart');
    exit();
}

// show cart
if (isset($_GET['cart'])) {
    
    // check to see if session is set
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            $title = 'Please Log In';
            $longdesc = "You need to log in to buy things.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
            exit ();
        }
    }
    
    $sql = 'SELECT * 
        FROM store_carts 
        LEFT JOIN store_items
        ON store_carts.itemID = store_items.itemID
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $cart_items = $s->fetchall(PDO::FETCH_ASSOC);
    
    $total = 0;
    foreach ($cart_items as $item):
        $total = $total + ($item['price'] * $item['quantity']);
    endforeach;
    setlocale(LC_MONETARY, 'en_US');
    $total = money_format('%.2n', $total/100);
    
    include $siteroot . 'demo2/app/pages_public/store_cart.inc.html.php';
    exit();
}

// check out
if (isset($_GET['check_out'])) {
    
    // check to see if session is set
    if (!isset($_SESSION['loggedIn'])) {
        if (!isset($_SESSION['loggedIn'])) {
            $title = 'Please Log In';
            $longdesc = "You need to log in to buy things.";
            include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
            exit ();
        }
    }
    
    if (isset($_POST['stripeToken'])) {

        $sql = 'SELECT * 
            FROM store_carts 
            LEFT JOIN store_items
            ON store_carts.itemID = store_items.itemID
            WHERE userID = :userID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $user_info['id']);
        $s->execute();
        $cart_items = $s->fetchall(PDO::FETCH_ASSOC);

        $total = 0;
        foreach ($cart_items as $item):
            $total = $total + ($item['price'] * $item['quantity']);
        endforeach;
        
        // charge the card
        Stripe::setApiKey("sk_test_y4j5rHbNnOXWIgtsMbQUvWFh");
        $token = $_POST['stripeToken'];
        $description = 'Store purchase by ' . $_SESSION['email'];
        $charge = Stripe_Charge::create(array(
            "amount" => $total, 
            "currency" => "usd",
            "card" => $token,
            "description" => $description));
        
        // convert the stored badge price in cents to a readable string
        setlocale(LC_MONETARY, 'en_US');
        $total = money_format('%.2n', $total/100);
        
        // empty the cart
        $userID = userID($_SESSION['email']);
        $sql = 'DELETE FROM store_carts 
            WHERE userID = :userID';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userID', $userID);
        $s->execute();
        
        // email the convention the order
        $address = "server@simpleconvention.com";
        $subject = "Convention Store Order";
        include $siteroot . 'demo2/app/includes_php/store_order.inc.php';
        include $siteroot . 'demo2/app/includes_php/send_mail.php';

        // show confirmation
        include $siteroot . 'demo2/app/pages_public/store_receipt.inc.html.php';
        exit();
    }
    
    $sql = 'SELECT * 
        FROM store_carts 
        LEFT JOIN store_items
        ON store_carts.itemID = store_items.itemID
        WHERE userID = :userID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $user_info['id']);
    $s->execute();
    $cart_items = $s->fetchall(PDO::FETCH_ASSOC);
    
    $total = 0;
    foreach ($cart_items as $item):
        $total = $total + ($item['price'] * $item['quantity']);
    endforeach;
    setlocale(LC_MONETARY, 'en_US');
    $total = money_format('%.2n', $total/100);
    
    if ($total == "$0.00") {
        $title = "Nothing to Buy";
        $longdesc = "Your cart is currently empty.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit();
    }
        
    // display form
    include $siteroot . 'demo2/app/pages_public/store_check_out.inc.html.php';
    exit();

}

//////////////////////////////////////////////////////////////////////////
// forums
//////////////////////////////////////////////////////////////////////////

// forums start page
if (isset($_GET['forums'])) {
    forumsAvailable();
    
    // topics
    $sql = '
        SELECT *
        FROM forum_topics
        ORDER BY topicorder ASC';
    $s = $pdo->query($sql);
    $topics = $s->fetchall(PDO::FETCH_ASSOC);
    
    // subtopics
    $sql = '
        SELECT *
        FROM forum_subtopics
        ORDER BY subtopicorder ASC';
    $s = $pdo->query($sql);
    $subtopics = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_public/forums.inc.html.php';
    exit();
}

// displays all threads for a subtopic
if (isset($_GET['action']) and $_GET['action'] == 'subtopic') {
    forumsAvailable();
    
    // get threads for subtopic
    $sql = '
        SELECT threadID, threadname, last_post, number_of_posts, users.id, first_name, last_name
        FROM forum_threads 
        LEFT JOIN users
        ON creatorID = users.id
        WHERE undersubtopicID = :undersubtopicID
        ORDER BY last_post DESC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':undersubtopicID', $_GET['id']);
    $s->execute();
    $threads = $s->fetchall(PDO::FETCH_ASSOC);
    
    // get subtopic info
    $sql = '
        SELECT *
        FROM forum_subtopics
        WHERE subtopicID = :subtopicID
        ORDER BY subtopicorder ASC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':subtopicID', $_GET['id']);
    $s->execute();
    $subtopic = $s->fetch(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_public/forums_subtopic.inc.html.php';
    exit();
}

// displays all posts for a thread
if (isset($_GET['action']) and $_GET['action'] == 'thread') {
    forumsAvailable();
    
    // get posts for thread
    $sql = '
        SELECT postID, posttext, createdon, users.first_name, users.last_name
        FROM forum_posts 
        LEFT JOIN users
        ON creatorID = users.id
        WHERE underthreadID = :underthreadID
        ORDER BY createdon DESC';
    $s = $pdo->prepare($sql);
    $s->bindValue(':underthreadID', $_GET['id']);
    $s->execute();
    $posts = $s->fetchall(PDO::FETCH_ASSOC);
    
    // get thread info
    $sql = '
        SELECT *
        FROM forum_threads
        WHERE threadID = :threadID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':threadID', $_GET['id']);
    $s->execute();
    $thread = $s->fetch(PDO::FETCH_ASSOC);
    
    // get subtopic info
    $sql = '
        SELECT *
        FROM forum_subtopics
        WHERE subtopicID = :subtopicID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':subtopicID', $thread['undersubtopicID']);
    $s->execute();
    $subtopic = $s->fetch(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_public/forums_thread.inc.html.php';
    exit();
}

// create a new thread
if (isset($_POST['action']) and $_POST['action'] == 'create_thread') {
    forumsAvailable();
    if (!isset($_SESSION['loggedIn'])) {
        $title = 'Please Log In';
        $longdesc = "You need to log in to post in the forums.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    $id = userID($_SESSION['email']);
    $current_time = date('Y-m-d H:i:s');
    
    // create the new thread
    $sql = '
        INSERT INTO forum_threads 
        SET threadname        =   :threadname,
            creatorID         =   :creatorID,
            createdon         =   :createdon,
            createdip         =   :createdip,
            undersubtopicID   =   :undersubtopicID,
            last_post         =   :last_post,
            number_of_posts   =   :number_of_posts';
    $s = $pdo->prepare($sql);
    $s->bindValue(':threadname', $_POST['threadname']);
    $s->bindValue(':creatorID', $id);
    $s->bindValue(':createdon', $current_time);
    $s->bindValue(':createdip', $_SERVER['REMOTE_ADDR']);
    $s->bindValue(':undersubtopicID', $_POST['undersubtopicID']);
    $s->bindValue(':last_post', $current_time);
    $s->bindValue(':number_of_posts', 0);
    $s->execute();

    header("Location: ." . '?id=' . $_POST['undersubtopicID'] . '&action=subtopic');
    exit();
}

// create a new post
if (isset($_POST['action']) and $_POST['action'] == 'create_post') {
    forumsAvailable();
    if (!isset($_SESSION['loggedIn'])) {
        $title = 'Please Log In';
        $longdesc = "You need to log in to post in the forums.";
        include $siteroot . 'demo2/app/pages_public/confirmation.inc.html.php';
        exit ();
    }
    
    $id = userID($_SESSION['email']);
    $current_time = date('Y-m-d H:i:s');
    
    // create the new thread
    $sql = '
        INSERT INTO forum_posts 
        SET posttext         =   :posttext,
            creatorID        =   :creatorID,
            createdon        =   :createdon,
            createdip        =   :createdip,
            underthreadID    =   :underthreadID';
    $s = $pdo->prepare($sql);
    $s->bindValue(':posttext', $_POST['posttext']);
    $s->bindValue(':creatorID', $id);
    $s->bindValue(':createdon', $current_time);
    $s->bindValue(':createdip', $_SERVER['REMOTE_ADDR']);
    $s->bindValue(':underthreadID', $_POST['underthreadID']);
    $s->execute();
    
    // update the thread
    $sql = 'UPDATE forum_threads 
            SET
            last_post = :last_post,
            number_of_posts = number_of_posts +1 
            WHERE
            threadID = :threadID 
            LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':threadID', $_POST['underthreadID']);
    $s->bindValue(':last_post', $current_time);
    $s->execute();

    header("Location: ." . '?id=' . $_POST['underthreadID'] . '&action=thread');
    exit();
}

//////////////////////////////////////////////////////////////////////////
// guests
//////////////////////////////////////////////////////////////////////////

// show guest page
if (isset($_GET['guests'])) {
    if ($con_info['guests_on'] == 1) {
    
        // guests
        $sql = '
            SELECT *
            FROM guests
            ORDER BY professional_name ASC';
        $s = $pdo->query($sql);
        $guests = $s->fetchall(PDO::FETCH_ASSOC);

        include $siteroot . 'demo2/app/pages_public/guests.inc.html.php';
        exit();
    }
}

//////////////////////////////////////////////////////////////////////////
// static pages
//////////////////////////////////////////////////////////////////////////

// privacy policy
if (isset($_GET['privacy_policy'])) {
    include $siteroot . 'demo2/app/pages_public/privacy_policy.inc.html.php';
    exit();
}

// con policy
if (isset($_GET['con_policy'])) {
    include $siteroot . 'demo2/app/pages_public/con_policy.inc.html.php';
    exit();
}

// lodging
if (isset($_GET['lodging'])) {
    include $siteroot . 'demo2/app/pages_public/lodging.inc.html.php';
    exit();
}

// vendors
if (isset($_GET['vendors'])) {
    if ($con_info['vendors_on'] == 1) {
       include $siteroot . 'demo2/app/pages_public/vendors.inc.html.php';
        exit(); 
    }
}

// rules
if (isset($_GET['rules'])) {
    include $siteroot . 'demo2/app/pages_public/rules.inc.html.php';
    exit();
}

// sponsors
if (isset($_GET['sponsors'])) {
    if ($con_info['sponsors_on'] == 1) {
        include $siteroot . 'demo2/app/pages_public/sponsors.inc.html.php';
        exit();
    }
}

//contact us
if (isset($_GET['contact_us'])) {
    include $siteroot . 'demo2/app/pages_public/contact_us.inc.html.php';
    exit();
}

// homepage
include $siteroot . 'demo2/app/pages_public/defaulthome.inc.html.php';