<?php

if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn'] = TRUE) {
        $menu_login_status = TRUE;
        
        $sql = 'SELECT parentID FROM users WHERE email = :email';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $_SESSION['email']);
        $s->execute();
        $child_check = $s->fetch();
    }
    else {
        $menu_login_status = FALSE;
    }
}
else {
    $menu_login_status = FALSE;
}

// get a list of all event types
$result = $pdo->query('SELECT event_type_desc FROM event_types
    ORDER BY event_type_desc ASC');
foreach ($result as $row) {
$event_types[] = array(
    'event_type_desc' => $row['event_type_desc']);
}

if ($menu_login_status == 'TRUE') {
    echo '       <ul class="nav nav-tabs">
                    <li><a href="?">home</a></li>
                    <li class="dropdown">
                    <a class="dropdown-toggle"
                    data-toggle="dropdown"
                    href="#">
                    upcoming
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">';
    if ($con_info['guests_on'] == 1) {
        echo '<li><a href="?guests">guests</a></li>'; }
    if ($con_info['vendors_on'] == 1) {
        echo '<li><a href="?vendors">vendors</a></li>'; }
    if ($con_info['sponsors_on'] == 1) {
        echo '<li><a href="?sponsors">sponsors</a></li>'; }
    echo '</ul></li>';
    echo            '<li class="dropdown">
                    <a class="dropdown-toggle"
                    data-toggle="dropdown"
                    href="#">
                    events
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">';
    
    if ($con_info['schedule_shown'] == 0) {
        echo '<li><a href="?events">event info</a></li></ul></li>'; }
    else {
        echo '          <li><a href="/?type=all&action=view_events_of_type">all events</a></li>';
        foreach ($event_types as $type):
        echo '<li>';
        echo "<a href=\"/?type=" . $type['event_type_desc'] . "&action=view_events_of_type\">" . $type['event_type_desc'] . "</a>";
        echo '</li>';
        endforeach;
        echo '</ul></li>'; }
        
        
        
        if ($child_check['parentID'] == 0) {
    echo '          <li class="dropdown">
                    <a class="dropdown-toggle"
                    data-toggle="dropdown"
                    href="#">
                    store
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                    <li><a href="?buybadge">buy badge</a></li>';
    if ($con_info['store_on'] == 1) {
        echo '<li><a href="?constore">other items</a></li>
            <li><a href="?cart">view cart</a></li>
            <li><a href="?check_out">check out</a></li>';
        echo '</ul></li>'; }
        else {
            echo '</ul></li>';
        }
        }
    
    
    
    echo           '<li class="dropdown">
                    <a class="dropdown-toggle"
                    data-toggle="dropdown"
                    href="#">
                    info
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                    <li><a href="?lodging">lodging</a></li>
                    <li><a href="?contact_us">contact us</a></li>
                    <li><a href="?rules">rules</a></li>
                    <li><a href="?con_policy">policies</a></li></ul></li>
                    
                    <li class="dropdown">
                    <a class="dropdown-toggle"
                    data-toggle="dropdown"
                    href="#">
                    your account
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">';
    if ($child_check['parentID'] == 0) {
    echo           '<li><a href="?mybadges">badges</a></li>
                    <li><a href="?mypersonalinfo">contact info</a></li>';
    }
    echo            '<li><a href="?myschedule">my schedule</a></li>
                    <li><a href="?logout">logout</a></li></ul></li>';
    if ($child_check['parentID'] == 0) {
    if ($con_info['forums_on'] == 1) {
        echo '<li><a href="?forums">forums</a></li>'; } }
    echo '</ul>'; }
else {
    echo '       <ul class="nav nav-tabs">
                    <li><a href="?">home</a></li>
                    <li class="dropdown">
                    <a class="dropdown-toggle"
                    data-toggle="dropdown"
                    href="#">
                    upcoming
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">';
    if ($con_info['guests_on'] == 1) {
        echo '<li><a href="?guests">guests</a></li>'; }
    if ($con_info['vendors_on'] == 1) {
        echo '<li><a href="?vendors">vendors</a></li>'; }
    echo '</ul></li>';
    echo            '<li class="dropdown">
                    <a class="dropdown-toggle"
                    data-toggle="dropdown"
                    href="#">
                    events
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">';
    
    if ($con_info['schedule_shown'] == 0) {
        echo '<li><a href="?events">event info</a></li></ul></li>'; }
    else {
        echo '          <li><a href="/?type=all&action=view_events_of_type">all events</a></li>';
        foreach ($event_types as $type):
        echo '<li>';
        echo "<a href=\"/?type=" . $type['event_type_desc'] . "&action=view_events_of_type\">" . $type['event_type_desc'] . "</a>";
        echo '</li>';
        endforeach;
        echo '</ul></li>'; }
        

    echo '          <li class="dropdown">
                    <a class="dropdown-toggle"
                    data-toggle="dropdown"
                    href="#">
                    store
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                    <li><a href="?buybadge">buy badge</a></li>';
    if ($con_info['store_on'] == 1) {
        echo '<li><a href="?constore">other items</a></li>
            <li><a href="?cart">view cart</a></li>
            <li><a href="?check_out">check out</a></li>';
        echo '</ul></li>'; }
        else {
            echo '</ul></li>';
        }
    
    
    
    echo           '<li class="dropdown">
                    <a class="dropdown-toggle"
                    data-toggle="dropdown"
                    href="#">
                    info
                    <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                    <li><a href="?lodging">lodging</a></li>
                    <li><a href="?contact_us">contact us</a></li>
                    <li><a href="?rules">rules</a></li>
                    <li><a href="?con_policy">policies</a></li></ul></li>';
        if ($con_info['forums_on'] == 1) {
        echo '<li><a href="?forums">forums</a></li>'; }
     echo '<li><a href="?register">register</a></li>
                    <li><a href="?loginform">login</a></li>
                </ul>'; }




