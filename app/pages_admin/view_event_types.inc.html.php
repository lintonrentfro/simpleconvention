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
                    <h4>Event Types</h4>
                    <h5>Current Event Types</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <?php foreach ($event_types as $type): ?>
                        <tr>
                            <form action="?" method="post">
                                <div>
                                    <td><?php htmlout($type['event_type_desc']); ?></td>
                                    <input type="hidden" name="id" 
                                        value="<?php echo $type['event_type_id']; ?>">
                                    <input type="hidden" name="event_type_desc" 
                                        value="<?php echo $type['event_type_desc']; ?>">
                                    <td><button class="btn btn-mini" type="submit" value="edit_event_type" 
                                        name="action" title="edit">edit</button></td>
                                    <td><button class="btn btn-mini" type="submit" value="delete_event_type" 
                                        name="action" title="edit">delete</button></td>
                                </div>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                <h5>Add New Event Type</h5>
                    <form class="form-inline" action="?" method="post">
                        <div>
                            <input type="text" name="type" id="event_type_desc">
                            <button class="btn" type="submit" value="create_event_type" name="action" title="create">create</button>
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