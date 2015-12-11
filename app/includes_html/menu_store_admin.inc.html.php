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


if ($menu_login_status == 'TRUE') {
    if (userHasRole(6)) {
        echo '      <ul class="nav nav-list">
                        <li><a href="?logout">logout</a></li>
                        <li class="nav-header">STORE</li>
                        <li><a href="?items">items</a></li>
                        <li><a href="?categories">categories</a></li>
                    </ul>';
    }
    else {
        echo '      <ul class="nav nav-list">
                        <li><a href="?loginform">login</a></li>
                    </ul>';
    }
}
else {
    echo '      <ul class="nav nav-list">
                    <li><a href="?loginform">login</a></li>
                </ul>';
}