<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Forums</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <img src="/img/logo.png" />
                </div>
                <div class="span1"></div>
            </div>
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_public.inc.html.php'; ?>
                </div>
                <div class="span1"></div>
            </div>
            <div class="row-fluid">
                <div class="span1"></div>
                <div class="span10">
                    <ul class="breadcrumb">
                        <li><a href="/?forums">Topics</a> <span class="divider">/</span></li>
                        <li><a href="/?id=<?php htmlout($subtopic['subtopicID']); ?>&action=subtopic"><?php htmlout($subtopic['subtopicname']); ?></a> <span class="divider">/</span></li>
                        <li class="active"><?php htmlout($thread['threadname']); ?></li>
                    </ul>
                    <table class="table table-condensed">
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td>
                                <?php htmlout($post['first_name']); echo ' '; htmlout($post['last_name']); ?>
                                <br><?php htmlout(date("m/d/y g:i A", strtotime($post['createdon']))); ?>
                                <p>
                                    <?php htmlout($post['posttext']); ?>
                                </p>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php
                        if ($posts ==  null) {
                            echo '<tr><td><p>There are no posts yet for this subject.</p></td></tr>';                            
                        }
                    ?>
                    </table>
                    <hr>
                    <p></p>
                    <form action="?" method="post" class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="posttext">New Post:</label>
                            <div class="controls">
                                <textarea class="span9" id="posttext" name="posttext" rows="6"></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <input type="hidden" name="underthreadID" value="<?php echo $thread['threadID']; ?>">
                                <button class="btn"  type="submit" value="create_post" name="action" title="submit">submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>