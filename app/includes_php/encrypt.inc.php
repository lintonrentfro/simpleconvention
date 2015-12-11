<?php

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