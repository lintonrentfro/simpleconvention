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
                    <h4>Duty Roster</h4>
                    <h5>Create a New Duty Roster Item</h5>
                    <a href="?view_events">for an event</a><br>
                    <a href="?create_duty_item">not tied to an event</a>
                    <h5>Searching and Filtering</h5>
                    <form action="?" method="post">
                        <div>
                        <label for="duties_for_user">All duties for this user:</label>
                        <select name="duties_for_user" id="duties_for_user">
                        <option value="">Select one</option>
                        <?php foreach ($user_list as $user): ?>
                        <option value="<?php htmlout($user['id']); ?>">
                        <?php htmlout($user['first_name']);
                            echo ' ';
                            htmlout($user['last_name']); ?></td></option>
                        <?php endforeach; ?>
                        </select>
                        </div>
                        <div>
                        <button type="submit" value="search_roster_by_user" 
                        name="action" title="search roster">search</button>
                        </div>
                    </form>
                    <form action="?" method="post">
                        <div>
                        <label for="duties_for_property">All duties for events with this property:</label>
                        <select name="duties_for_property" id="duties_for_property">
                        <option value="">Select one</option>
                        <?php foreach ($eventproperties as $property): ?>
                        <option value="<?php htmlout($property['id']); ?>">
                        <?php htmlout($property['property']); ?></td></option>
                        <?php endforeach; ?>
                        </select>
                        </div>
                        <div>
                        <button type="submit" value="search_roster_by_event_property" 
                        name="action" title="search roster">search</button>
                        </div>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Event</th>
                                <th>Start/End</th>
                                <th>Healper/Duty</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <?php foreach ($duty_roster as $duty): ?>
                        <tr>
                            <form action="?" method="post">
                                <td><?php htmlout($duty['events.name']); ?></td>
                                <td><?php 
                                    htmlout(date("m/d g:i a", strtotime($duty['duty_roster.start'])));
                                    echo '<br>';
                                    htmlout(date("m/d g:i a", strtotime($duty['duty_roster.end']))); 
                                    ?></td>
                                <td><?php htmlout($duty['users.first_name']);
                                    echo ' ';
                                    htmlout($duty['users.last_name']);
                                    echo '<br>';
                                    htmlout($duty['duty_roster.description']); ?></td>
                                <input type="hidden" name="id" 
                                        value="<?php echo $duty['duty_roster.id']; ?>">
                                <td><button class="btn btn-mini" type="submit" value="edit_duty_roster_item" 
                                    name="action" title="edit">edit</button></td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </table>
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