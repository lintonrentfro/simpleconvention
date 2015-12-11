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
                    <h4>Convention Date Management</h4>
                    
                    <h5>Edit Convention Dates</h5>
                <table class="table">
                    <?php foreach($years as $year): ?>
                    
                    <form action="?" method="post">
                    <input type="hidden" name="id" value="<?php echo $year['id']; ?>">
                    <tr class="none">
                        <td>Con <?php htmlout($year['id']); if($year['id'] === $con_info['current_year']) {echo ' - Current Convention'; } ?></td>
                        <td>
                            <input type="text" name="start" id="start" style="width: 6em;" value="<?php htmlout($year['start']); ?>">
                        </td>
                        <td>to</td>
                        <td>
                            <input type="text" name="end" id="start" style="width: 6em;" value="<?php htmlout($year['end']); ?>">
                        </td>
                        <td>
                            <button class="btn" type="submit" value="update_year" name="action" title="update">update</button>
                        </td>
                    </tr>
                    </form>
                    <?php endforeach; ?>
                </table>
                <p></p>
                <h5>Create New Convention Dates</h5>
                    <form class="form-inline" action="?" method="post">
                        <input type="hidden" name="id" value="<?php echo $year['id']; ?>">
                        <input type="text" name="start" id="start" style="width: 6em;" value="xxxx-xx-xx">
                         to 
                        <input type="text" name="end" id="start" style="width: 6em;" value="xxxx-xx-xx">
                        <button class="btn" type="submit" value="create_year" name="action" title="create">create</button>
                    </form>
                <h5>End Current Convention</h5>
                <table>
                    <form action="?" method="post">
                    <tr class="none">
                        <td class="none" style="vertical-align:middle">
                            <p><em>************ Warning ************</em></p>
                            <p>This will do all of the following:</p>
                            <ol>
                                <li>email you backups of all convention data as csv files</li>
                                <li>delete all event information and the comments for events</li>
                                <li>delete the duty roster</li>
                                <li>delete the record of which users registered for which events</li>
                                <li>delete all event information</li>
                                <li>it will KEEP everything else</li>
                                <li>the convention will advance to the next set of dates</li>
                            </ol></td></tr>
                    <tr class="none"><td class="none" style="vertical-align:middle">
                            <button class="btn" type="submit" value="advance_to_next_year" 
                            name="action" title="submit">I'm Sure -- Do the Things Listed Above</button></td>
                        
                    </tr></form>
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