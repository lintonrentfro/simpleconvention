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
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <?php foreach ($attendees as $attendee): ?>
                        <tr>
                            <form action="?" method="post">
                                <div>
                                    <td><?php 
                                        htmlout($attendee['first_name']); 
                                        echo ' ';
                                        htmlout($attendee['last_name']);
                                        ?></td>
                                    <td><?php htmlout($attendee['email']); ?></td>
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