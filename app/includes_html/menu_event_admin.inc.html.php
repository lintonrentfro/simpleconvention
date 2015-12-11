<?php

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


if ($menu_login_status == 'TRUE' AND (userHasRole(2) == TRUE)){
    echo '       <ul class="nav nav-list">
                    <li><a href="?view_events">events</a></li>
                    <li><a href="?logout">logout</a></li>
                </ul>';
}
else {
    echo '       <ul class="nav nav-list">
                    <li><a href="?logout">logout</a></li>
                    <li><a href="?loginform">login</a></li>
                </ul>';
}