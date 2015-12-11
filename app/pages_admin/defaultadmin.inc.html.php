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
                        <div class="span5">
                            <h5>Events</h5>
                            <table class="table">
                                <tr>
                                    <td>events pending approval</td>
                                    <td><?php echo $number_of_events_pending['COUNT(*)']; ?></td>
                                </tr>
                                <tr>
                                    <td>events on schedule</td>
                                    <td><?php echo $number_of_events_on_schedule['COUNT(*)']; ?></td>
                                </tr>
                                <tr>
                                    <td>event overflow requests</td>
                                    <td><?php echo $number_of_overflow_requests['COUNT(*)']; ?></td>
                                </tr>
                                <tr>
                                    <td>event comments</td>
                                    <td><?php echo $number_of_event_comments['COUNT(*)']; ?></td>
                                </tr>
                            </table>
                            <h5>Guests</h5>
                            <table class="table">
                                <tr>
                                    <td>number of guests</td>
                                    <td><?php echo $number_of_guests['COUNT(*)']; ?></td>
                                </tr>
                                <tr>
                                    <td>guests with nothing on schedule</td>
                                    <td><?php echo $count_of_guests_with_no_events; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="span5">
                            <h5>Users</h5>
                            <table class="table">
                                <tr>
                                    <td>registered users</td>
                                    <td><?php echo $number_of_users['COUNT(*)']; ?></td>
                                </tr>
                                <tr>
                                    <td>verified users</td>
                                    <td><?php echo $number_of_verified_users['COUNT(*)']; ?></td>
                                </tr>
                                <tr>
                                    <td>badges purchased</td>
                                    <td><?php echo $number_of_paid_users['COUNT(*)']; ?></td>
                                </tr>
                                <tr>
                                    <td>free badges given</td>
                                    <td><?php echo $number_of_free_badges['COUNT(*)']; ?></td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>