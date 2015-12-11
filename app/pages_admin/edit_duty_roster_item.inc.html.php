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
                    <h4>Edit Duty Roster Item</h4>
                    <form action="?" method="post">
                        <input type="hidden" name="eventID" value="<?php echo $duty_roster_item['eventID']; ?>">
                        <input type="hidden" name="id" value="<?php echo $duty_roster_item['id']; ?>">

                        <div>
                            <label for="type">User:</label>
                            <select name="userID" id="userID">
                            <option value="">Select one</option>
                            <?php foreach ($user_list as $user): ?>
                            <option value="<?php echo $user['id']; ?>"
                                <?php if ($duty_roster_item['userID'] == 
                                    $user['id']) {echo ' selected';}?>>
                            <?php 
                                htmlout($user['last_name']); 
                                echo ', '; 
                                htmlout($user['first_name']); 
                                ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>

                        <p></p>
                        <div>

                            <label for ="start">Start of Duty (in format year:month:day hours:minutes:seconds): 
                                <br>* Event starts at: <?php htmlout($eventinfo['start']); ?>
                                <input type="text"
                            name="start" id="start" value="<?php htmlout($duty_roster_item['start']); ?>"></label>
                        </div>
                        <p></p>
                        <div>

                            <label for ="end">End of Duty (in format year:month:day hours:minutes:seconds): 
                                <br>* Event end at: <?php htmlout($eventinfo['end']); ?>
                                <input type="text"
                            name="end" id="end" value="<?php htmlout($duty_roster_item['end']); ?>"></label>
                        </div>
                        <p></p>
                        <div>
                            <label for="description">Description: </label>
                            <textarea id="description" name="description" rows="5" 
                                cols="100"><?php htmlout($duty_roster_item['description']); ?></textarea>
                        </div>
                        <p></p>
                        <div>
                            <button class="btn btn-primary" type="submit" value="update_duty" 
                            name="action" title="update">update</button>
                        </div>
                        <p></p>
                        <div>
                            <button class="btn btn-danger" type="submit" value="delete_duty" 
                            name="action" title="update">delete</button>
                        </div>
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