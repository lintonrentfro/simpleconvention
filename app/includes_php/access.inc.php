<?php

/**
 * This function compares the submitted email & password to those in the user
 * table for a match and starts a session with ['loggedIn'} = TRUE if found.
 * @return boolean
 */

function userIsLoggedIn() {
    $salt = generateSalt($_POST['email']);
    $password = generateHash($salt, $_POST['password']);

    if (databaseContainsUser($_POST['email'], $password)) {
        $_SESSION['loggedIn'] = TRUE;
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['password'] = $password;
        return TRUE;
    }
    else {
        unset($_SESSION['loggedIn']);
        unset($_SESSION['email']);
        unset($_SESSION['password']);
        return FALSE;
    }
}


function databaseContainsUser($email, $password) {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';

    $sql = 'SELECT COUNT(*), first_name FROM users
        WHERE email = :email AND password = :password AND verified = :verified';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $email);
    $s->bindValue(':password', $password);
    $s->bindValue(':verified', 1);
    $s->execute();

    $row = $s->fetch();
    if ($row[0] > 0) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function userHasRole($requiredrole) {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    $sql = 'SELECT id FROM users WHERE email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_SESSION['email']);
    $s->execute();
    $row = $s->fetch();
    $id = $row['id'];
    
    $sql = 'SELECT COUNT(*) FROM lookup_users_userroles
        WHERE userID = :userID AND roleID = :userRole';
    $s = $pdo->prepare($sql);
    $s->bindValue(':userID', $id);
    $s->bindValue(':userRole', $requiredrole);
    $s->execute();

    $row = $s->fetch();
    if ($row[0] > 0) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function recordBadLogin ($userID) {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    $sql = 'UPDATE users SET failed_logins = failed_logins + 1 
        WHERE id = :id LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $userID);
    $s->execute();
}

function recordGoodLogin ($userID) {
    require '/home/simpleco/demo2/app/includes_php/db.inc.php';
    $sql = 'UPDATE users SET failed_logins = 0 
        WHERE id = :id LIMIT 1';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $userID);
    $s->execute();
}