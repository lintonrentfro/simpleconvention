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
                    <h4>Confirm Event Deletion</h4>
                    <p>Are you sure you want to delete "<?php htmlout($event['name']);  ?>"?</p>
                    <p>The event will be removed from the schedules of these users:</p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <?php foreach ($registered_users as $user): ?>
                            <tr><td class="none">
                            <?php htmlout($user['first_name']);
                            echo ' ';
                            htmlout($user['last_name']); ?>
                            </td><td class="none">
                            <?php htmlout($user['email']);  ?>
                            </td></tr>
                        <?php endforeach; ?>
                    </table>
                    <p>These duty roster items will be deleted:</p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Duty</th>
                            </tr>
                        </thead>
                        <?php foreach ($duty_roster_for_event as $duty): ?>
                            <tr><td class="none">
                            <?php htmlout($duty['users.first_name']);
                            echo ' ';
                            htmlout($duty['users.last_name']); ?>
                            </td><td class="none">
                            <?php htmlout($duty['users.email']);  ?>
                            </td><td class="none">
                            <?php htmlout($duty['duty_roster.description']);  ?>
                            </td></tr>
                        <?php endforeach; ?>
                    </table>
                    <form action="?" method="post">
                        <input type="hidden" name="id" value="<?php htmlout($event['id']); ?>">
                        <button type="submit" value="yes_delete_event" name="action" title="delete event">delete event</button>
                    </form>
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