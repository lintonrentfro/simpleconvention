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
                    <h4>Email Features</h4>
                    <p>
                        To make sending bulk email easier, select the list you want
                        and it will be emailed directly to "<?php echo $con_info['email']; ?>".
                    </p>
                    <form class="form-inline" action="?" method="post">
                        <span class="help-block">Lists of users.</span>
                        <select name="by_type_of_user" id="by_type_of_user">
                            <option value="">select one</option>
                            <option value="Everyone who has registered on this site.">Everyone who has registered on this site.</option>
                            <option value="Everyone who has a badge.">Everyone who has a badge.</option>
                            <option value="Everyone who does NOT have a badge.">Everyone who does NOT have a badge.</option>
                            <option value="All convention staff.">All convention staff.</option>
                            <option value="All guests.">All guests.</option>
                        </select>
                        <button class="btn btn-primary" type="submit" value="by_type_of_user" name="action" 
                            title="send list">send list</button>
                    </form>
                    <form class="form-inline" action="?" method="post">
                        <span class="help-block">Primary contacts of events for one event type.</span>
                        <select name="event_type" id="event_type">
                            <option value="">select one</option>
                            <?php foreach ($event_types as $row): ?>
                            <option value="<?php htmlout($row['event_type_desc']); ?>"><?php htmlout($row['event_type_desc']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-primary" type="submit" value="by_event_type" name="action" 
                            title="send list">send list</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>