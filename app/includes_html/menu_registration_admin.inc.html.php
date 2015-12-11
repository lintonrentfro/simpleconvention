<?php

session_start();
if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn'] = TRUE) {
        $menu_login_status = TRUE;
    }
    else {
        $menu_login_status = FALSE;
    }
}
else {
    $menu_login_status = FALSE;
}


if ($menu_login_status == 'TRUE' AND (userHasRole(5) == TRUE)){
    echo '       <ul class="nav nav-list">
                    <li><a href="?register_new">register new user</a></li>
                    <li class="nav-header"></li>
                    <li><a href="?register_existing">register existing user</a></li>
                    <li class="nav-header"></li>
                    <li><a href="?logout">logout</a></li>
                </ul>';
}
else {
    echo '       <ul class="nav nav-list">
                    <li><a href="?logout">logout</a></li>
                    <li><a href="?loginform">login</a></li>
                </ul>';
}