<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_admin/_head.inc.html'; ?>
        
        <title>Game Convention Template - Admin</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <img src="/img/logo.png" />
                </div>
            </div>
            <div class="row-fluid">
                <div class="span2">
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_admin.inc.html.php'; ?>
                </div>
                <div class="span10">
                    <h4>Users</h4>
                    <h5>Add New User</h5>
                    <a href="?add_user">add new user</a>
                    <h5>User Search</h5>
                    <form action="?" method="post" class="form-inline">
                        <select name="searchby" id="searchby">
                        <option value="">Search By</option>
                        <?php foreach ($user_columns as $col): ?>
                        <option value="<?php htmlout($col['column_name']); ?>">
                        <?php htmlout($col['column_name']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <input type="text" name="search_text" id="search_text">
                        <button class="btn" type="submit" value="search_users" name="action" title="search">search</button>
                    </form>
                    <h5>User Filter</h5>
                    <form action="?" method="post" class="form-inline">
                        <select name="searchby" id="searchby">
                        <option value="">User Roles</option>
                        <?php foreach ($user_roles as $role): ?>
                        <option value="<?php htmlout($role['id']); ?>">
                        <?php htmlout($role['role']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <button class="btn" type="submit" value="search_user_role" name="action" title="search users">search</button>
                    </form>
                    <h5>User is Registered for These Events</h5>
                    <?php 
                        if ($eventsregisterednumber['COUNT(*)'] < 1) {
                            echo 'None.';
                        }
                        else {
                            echo '<table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Event</th>
                                <th>Location</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Signed-Up</th>
                            </tr>
                        </thead>';
                        }
                    ?>
                    <?php foreach ($events_registered as $event_r): ?>
                    <tr>
                        <form action="?" method="post">
                            <div>
                                <td><?php htmlout($event_r['name']); ?></td>
                                <td><?php 
                                    htmlout($event_r['building']);
                                    echo ' ';
                                    htmlout($event_r['room']); 
                                    ?></td>
                                <td><?php htmlout(date("m/d g:i A", strtotime($event_r['start']))); ?></td>
                                <td><?php htmlout(date("m/d g:i A", strtotime($event_r['end']))); ?></td>
                                <td><?php 
                                    htmlout($event_r['currentusers']); 
                                    echo ' / ';
                                    htmlout($event_r['maxusers']); 
                                    ?></td>
                            </div>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                    <?php 
                        if ($eventsregisterednumber['COUNT(*)'] > 0) {
                            echo '</table>';
                        }
                    ?>
                </div>
            </div>
<!--            <div class="row-fluid">
                <div class="span12">
                    <h4>footer</h4>
                </div>
            </div>-->
        </div>
    </body>
</html>