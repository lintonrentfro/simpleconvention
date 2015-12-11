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
                    <h4>Create a New Duty Roster Item</h4>
                    <h5>Event: "<?php htmlout($eventinfo['name']); ?>"</h5>
                    <form action="?" method="post" class="form-horizontal">
                        <input type="hidden" name="eventID" value="<?php echo $eventinfo['id']; ?>">
                        <div class="control-group">
                            <label class="control-label" for="userID">User:</label>
                            <div class="controls">
                                <select name="userID" id="userID">
                                <option value="">Select one</option>
                                <?php foreach ($user_list as $user): ?>
                                <option value="<?php htmlout($user['id']); ?>">    
                                <?php 
                                    htmlout($user['last_name']); 
                                    echo ', '; 
                                    htmlout($user['first_name']); 
                                    ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <span class="help-block">Event starts at <?php htmlout(date("m/d/y g:i A", strtotime($eventinfo['start']))); ?> and 
                            ends at <?php htmlout(date("m/d/y g:i A", strtotime($eventinfo['end']))); ?>.</span>
                        <div class="control-group">
                            <label class="control-label" for="start">Start of Duty:</label>
                            <div class="controls">
                                <input type="text" name="start" id="start" value="<?php htmlout(date("m/d/y g:i A", strtotime($eventinfo['start']))); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="end">End of Duty:</label>
                            <div class="controls">
                                <input type="text" name="end" id="end" value="<?php htmlout(date("m/d/y g:i A", strtotime($eventinfo['end']))); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="description">Description: </label>
                            <div class="controls">
                                <textarea id="description" name="description" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" value="submit_duty" name="action" title="submit">submit</button>
                            </div>
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