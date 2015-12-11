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
    if (userHasRole(2)) {
        echo '      <ul class="nav nav-list">
                        <li><a href="?">home</a></li>
                        <li><a href="?logout">logout</a></li>
                        <li class="nav-header">EVENTS</li>
                        <li><a href="?view_events">view events</a></li>
                        <li><a href="?view_event_types">edit types</a></li>
                        <li><a href="?view_event_properties">edit properties</a></li>
                        <li class="nav-header">USERS</li>
                        <li><a href="?view_users">view users</a></li>
                        <li><a href="?duty_roster">duty roster</a></li>
                        <li><a href="?badges">badges</a></li>
                        <li class="nav-header">GUESTS</li>
                        <li><a href="?guests">manage</a></li>
                        <li><a href="?guest_schedule_check">check schedule</a></li>
                        <li class="nav-header">STORE</li>
                        <li><a href="?items">items</a></li>
                        <li><a href="?categories">categories</a></li>
                        <li class="nav-header">HTML</li>
                        <li><a href="?page=home&action=edit_html">home</a></li>
                        <li><a href="?page=vendors&action=edit_html">vendors</a></li>
                        <li><a href="?page=rules&action=edit_html">rules</a></li>
                        <li><a href="?page=policies&action=edit_html">policies</a></li>
                        <li><a href="?page=lodging&action=edit_html">lodging</a></li>
                        <li><a href="?page=contact&action=edit_html">contact</a></li>
                        <li><a href="?page=sponsors&action=edit_html">sponsors</a></li>
                        <li class="nav-header">SETUP</li>
                        <li><a href="?forum">forum</a></li>
                        <li><a href="?advertising">advertising</a></li>
                        <li><a href="?contact_info">contact info</a></li>
                        <li><a href="?settings">settings</a></li>
                        <li><a href="?years">con dates</a></li>
                        <li><a href="?backup">backup</a></li>
                        <li><a href="?view_log">logs</a></li>
                        <li><a href="?email_features">bulk email</a></li>
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