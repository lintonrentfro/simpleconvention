<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_schedule/_head.inc.html'; ?>
        <meta http-equiv="refresh" content="600"> 
        <title>Game Convention Schedule</title>
    </head>
    <body>
        
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <h3 style="font-size: 70px;line-height: 80px;text-align: center;">Game Convention Schedule</h3>
                    <h5 style="font-size: 60px;line-height: 90px;text-align: center;">updated every 5 minutes</h5>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="font-size: 50px;line-height: 60px;">Name</th>
                                <th style="font-size: 50px;line-height: 60px;">Start</th>
                                <th style="font-size: 50px;line-height: 60px;">End</th>
                                <th style="font-size: 50px;line-height: 60px;">Seats Left</th>
                                <th style="font-size: 50px;line-height: 60px;">Location</th>
                            </tr>
                        </thead>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td style="font-size: 50px;line-height: 60px;"><?php htmlout($event['name']); ?></td>
                        <td style="font-size: 50px;line-height: 60px;"><?php htmlout(date("m/d g:i a", strtotime($event['start']))); ?></td>
                        <td style="font-size: 50px;line-height: 60px;"><?php htmlout(date("m/d g:i a", strtotime($event['end']))); ?></td>
                        <td style="font-size: 50px;line-height: 60px;">
                            <?php 
                                if ($event['registration_required'] == 1) {
                                    $taken = $event['maxusers'] - $event['currentusers'];
                                    htmlout($taken); }
                                else {
                                    echo 'n/a'; } ?>
                        </td>
                        <td style="font-size: 50px;line-height: 60px;">
                            <?php 
                                htmlout($event['building']); 
                                echo ' ';
                                htmlout($event['room']); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </body>
</html>

<p style="font-size: 20px;">