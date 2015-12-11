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
                    <h4>Forum Management</h4>
                    <h5>Add a New Topic</h5>
                    <form action="?" method="post" class="form-inline">
                        <input type="text" name="topicname" id="topicname">
                        <button class="btn" type="submit" value="add_topic" name="action" title="add">add</button>
                    </form>
                    <h5>Add a New Subtopic</h5>
                    <form action="?" method="post" class="form-inline">
                        <input type="text" name="subtopicname" id="subtopicname">
                        <select name="undertopicID" id="undertopicID">
                        <option value="">under topic</option>
                        <?php foreach ($topics as $topic): ?>
                        <option value="<?php htmlout($topic['topicID']); ?>">
                        <?php htmlout($topic['topicname']); ?></option>
                        <?php endforeach; ?>
                        </select>
                        <button class="btn" type="submit" value="add_subtopic" name="action" title="add">add</button>
                    </form>
                    <h5>Current Topics</h5>
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Topic</th>
                                <th>Order</th>
                                <th>Update</th>
                            </tr>
                            <?php foreach ($topics as $topic): ?>
                                    <form action="?" method="post" class="form-inline">
                                    <input type="hidden" name="topicID" value="<?php echo $topic['topicID']; ?>">
                                    <tr>
                                    <td><input class="input-xlarge"type="text" name="topicname" id="topicname" value="<?php echo $topic['topicname']; ?>"></td>
                                    <td><input class="input-mini" type="text" name="topicorder" id="topicorder" value="<?php echo $topic['topicorder']; ?>"></td>
                                    <td><button class="btn btn-mini" type="submit" value="update_topic" name="action" title="update">update</button></td>
                                    </tr></form>
                            <?php endforeach; ?>
                        </thead>
                    </table>
                    
                    <h5>Current Subtopics</h5>
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>Subtopic</th>
                                <th>Under Topic</th>
                                <th>Subtopic Order</th>
                            </tr>
                            <?php foreach ($subtopics as $subtopic): ?>
                                <form action="?" method="post" class="form-inline">
                                <input type="hidden" name="subtopicID" value="<?php echo $subtopic['subtopicID']; ?>">
                                <tr>
                                <td><input class="input-xlarge"type="text" name="subtopicname" id="subtopicname" value="<?php echo $subtopic['subtopicname']; ?>"></td>
                                <td>
                                    <select name="undertopicID" id="undertopicID">
                                    <?php foreach ($topics as $topic): ?>
                                    <option value="<?php htmlout($topic['topicID']); ?>"<?php if ($topic['topicID'] == 
                                    $subtopic['undertopicID']) {echo ' selected';}?>><?php htmlout($topic['topicname']); ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input class="input-mini" type="text" name="subtopicorder" id="subtopicorder" value="<?php echo $subtopic['subtopicorder']; ?>"></td>
                                <td><button class="btn btn-mini" type="submit" value="update_subtopic" name="action" title="update">update</button></td>
                                </tr></form>
                            <?php endforeach; ?>
                        </thead>
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