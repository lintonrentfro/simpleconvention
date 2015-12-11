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
                    <div class="row-fluid">
                        <div class="span8">
                            <h4>Event Overflow</h4>
                            <h5>Events With Extra Demand</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Users</th>
                                        <th>Clear Log Entries</th>
                                    </tr>
                                </thead>
                                <?php foreach ($overflow_events as $row): ?>
                                <tr>
                                    <td>
                                        <form action="?" method="post">
                                            <button class="btn btn-mini" type="submit" value="edit_event" name="action" title="edit event">
                                                <?php htmlout($row['name']); ?></button>
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        </form>
                                    </td>
                                    <td>
                                        <form action="?" method="post">
                                            <button class="btn btn-mini" type="submit" value="overflow_view_event_requesters" 
                                                name="action" title="event details">
                                            <?php htmlout($row['COUNT(action)']); ?> users</button>
                                            <input type="hidden" name="eventID" value="<?php echo $row['id']; ?>">
                                        </form>
                                    </td>
                                    <td>
                                        <form action="?" method="post">
                                            <button class="btn btn-mini" type="submit" value="overflow_clear_log_entries" 
                                                name="action" title="clear log entries">clear log entries</button>
                                        <input type="hidden" name="eventID" value="<?php echo $row['id']; ?>">
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                            <?php
                                if (isset($requesters)) {
                                echo '<h5>Email These Users</h5><table class="table table-striped"><thead><tr><th>Name</th><th>Email</th></tr></thead>'; } ?>
                            <?php foreach ($requesters as $row): ?>

                            <tr>
                                <td class="none"><?php 
                                    htmlout($row['first_name']);
                                    echo ' ';
                                    htmlout($row['last_name']); ?></td>
                                <td class="none"><?php 
                                    htmlout($row['email']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (isset($requesters)) {
                                echo '</table>'; } ?>
                            <p></p>
                            <?php if (isset($requesters) AND !isset($message)) {
                                echo '<form action="?" method="post">
                                <label for="subject">Subject:</label><input type="text" name="subject"
                                id="subject" size="80">
                                <label for="body">Body:</label>
                                <textarea id="body" name="body" rows="10" cols="80"></textarea><br>
                                <input type="hidden" name="eventID" value="' . $eventID . '">
                                <button class="btn btn-small" type="submit" value="email_overflow_users" name="action"
                                title="email these users">email these users</button>'; } ?>
                            <?php if (isset($message)) {
                                echo '<p>' . $message . '</p>'; } ?>
                        </div>
                        <div class="span4">
                        </div>
                    </div>
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