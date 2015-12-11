<?php

/*
 * This turns a password into the hash with the correct method used for the
 * users table.  It is intended to be used once to generate the password for
 * the first (superuser) user in the users table.
 * To generate other users, the superuser should log in and do it via the
 * website interface.
 */


function generateSalt($username) {
	$salt = '$2a$13$';
	$salt = $salt . md5(strtolower($username));
	return $salt;
}

function generateHash($salt, $password) {
	$hash = crypt($password, $salt);
	$hash = substr($hash, 29);
	return $hash;
}

$salt = generateSalt("PUT YOUR ADMIN EMAIL HERE");
$password = generateHash($salt, "PUT YOUR ADMIN PASSWORD HERE");

echo $password;