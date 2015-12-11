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
                    <h4>Logs</h4>
                    <form class="form-inline" action="?" method="post">
                        <label for="searchby">Search by User:</label>
                        <select name="searchby" id="searchby">
                        <option value="">Select one</option>
                        <?php foreach ($userlist as $row): ?>
                        <option value="<?php htmlout($row['id']); ?>">
                        <?php htmlout($row['first_name']); echo ' '; htmlout($row['last_name']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <button type="submit" value="search_user_log" name="action" title="search user log">search</button>
                    </form>
                    <form class="form-inline" action="?" method="post">
                        <label for="searchby">Search by Event:</label>
                        <select name="searchby" id="searchby">
                        <option value="">Select one</option>
                        <?php foreach ($eventlist as $row): ?>
                        <option value="<?php htmlout($row['id']); ?>">
                        <?php htmlout($row['name']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <button type="submit" value="search_event_log" name="action" title="search user log">search</button>
                    </form>
                    <?php 
                        if (isset($search_result)) {
                        echo '<table class="table table-striped">
                            <thead><tr><th>User</th><th>Action</th><th>Event</th><th>Time</th></tr></thead>';
                        foreach ($search_result as $row) :
                            echo '<tr><td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td><td>' . $row['action'] . '</td><td>' . $row['name'] . '</td><td>' . date("m/d g:i a", strtotime($row['time'])) . '</td></tr>';
                        endforeach;
                        echo '</table';
                    } ?>
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