<?php

session_start();

/*
 * --------------------------------------------------------------------
 * Store Admin Controller
 * 
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
    include $siteroot . 'demo2/app/pages_storeadmin/login.inc.html.php';
    exit();
}

/*
 * responds to login form data
 */
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
        include $siteroot . 'demo2/app/pages_storeadmin/confirmation.inc.html.php';
        exit();
    }
    
    if (userIsLoggedIn()) {
        logevent($user_info['id'], NULL, 'login');
        
        // reset the failed_logins field
        recordGoodLogin($user_info['id']);
        
        header("Location: .");
    }
    else {
        
        logevent($user_info['id'], NULL, 'bad login');
        
        // add to the failed_logins field
        recordBadLogin($user_info['id']);
        
        $title = 'Unauthorized User';
        $longdesc = "Email and password combination not found.";
        include $siteroot . 'demo2/app/pages_storeadmin/confirmation.inc.html.php';
        exit();
    }
}

/*
 * check to see if session is set
 */
if (!isset($_SESSION['loggedIn'])) {
    $title = 'Unauthorized User';
    $longdesc = "You need to log in to view this part of the site.";
    include $siteroot . 'demo2/app/pages_storeadmin/confirmation.inc.html.php';
    exit ();
}

/*
 * checks to see if user has required role
 */
if (!userHasRole(6)) {
    $title = 'Unauthorized User';
    $longdesc = "You do not have permission to access this part of the site.";
    include $siteroot . 'demo2/app/pages_storeadmin/confirmation.inc.html.php';
    exit();
}

/*
 * responds to logout attempt
 */
if (isset($_GET['logout'])) {
    logevent($user_info['id'], NULL, 'logout');
    
    unset($_SESSION['loggedIn']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    $longdesc = "You are now logged out.";
    include $siteroot . 'demo2/app/pages_storeadmin/confirmation.inc.html.php';
    exit();
}


/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                store                                                |
 *                                                                     |
 * --------------------------------------------------------------------
 */

// view items
if (isset($_GET['items'])) {
    $items = array();
    $sql = 'SELECT * FROM store_items';
    $s = $pdo->query($sql);
    $items = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_storeadmin/store_items.inc.html.php';
    exit();
}

// add item form
if (isset($_GET['add_item'])) {
    $categories = array();
    $sql = 'SELECT * FROM store_categories';
    $s = $pdo->query($sql);
    $categories = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_storeadmin/store_item_add.inc.html.php';
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
    
    include $siteroot . 'demo2/app/pages_storeadmin/store_item_edit.inc.html.php';
    exit();
}

// update item in database
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
    $categories = array();
    $sql = 'SELECT * FROM store_categories';
    $s = $pdo->query($sql);
    $categories = $s->fetchall(PDO::FETCH_ASSOC);
    
    include $siteroot . 'demo2/app/pages_storeadmin/store_categories.inc.html.php';
    exit();
}

// add category form
if (isset($_GET['add_category'])) {
    include $siteroot . 'demo2/app/pages_storeadmin/store_category_add.inc.html.php';
    exit();
}

// add category to database
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

/*
 * --------------------------------------------------------------------
 *                                                                     |
 *                default (dashboard)                                  |
 *                                                                     |
 * --------------------------------------------------------------------
 */

$sql = 'SELECT * FROM store_items';
$s = $pdo->query($sql);
$items = $s->fetchall(PDO::FETCH_ASSOC);

include $siteroot . 'demo2/app/pages_storeadmin/store_items.inc.html.php';
exit();