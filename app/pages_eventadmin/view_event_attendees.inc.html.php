<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/includes_html/css.inc.html.php'; ?>
        <meta charset="utf-8">
        <title>Game Convention Template - Event Admin</title>
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
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_event_admin.inc.html.php'; ?>
                </div>
                <div class="span10">
                    <h4>Attendees for Event</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <?php foreach ($registered_users as $user): ?>
                        <tr>
                            <form action="?" method="post">
                                <div>
                                    <td><?php htmlout($user['id']); ?></td>
                                    <td><?php 
                                        htmlout($user['first_name']); 
                                        echo ' ';
                                        htmlout($user['last_name']);
                                        ?></td>
                                    <td><?php htmlout($user['email']); ?></td>
                                </div>
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