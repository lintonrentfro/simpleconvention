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
                        <li class="active"><?php htmlout($subtopic['subtopicname']); ?></li>
                    </ul>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Author</th>
                                <th>Replies</th>
                            </tr>
                        </thead>
                    <?php foreach ($threads as $thread): ?>
                        <tr>
                            <td><a href="<?php echo '/?id='; ?><?php htmlout($thread['threadID']); ?>&action=thread"><?php htmlout($thread['threadname']); ?></a></td>
                            <td><?php htmlout($thread['first_name']); echo ' '; htmlout($thread['last_name']); ?></td>
                            <td><?php htmlout($thread['number_of_posts']); ?></td>
                    <?php endforeach; ?>
                        </tr>
                    </table>
                    <hr>
                    <p></p>
                    <form action="?" method="post" class="form-inline">
                        <input type="text" name="threadname" id="threadname" placeholder="new subject">
                        <input type="hidden" name="undersubtopicID" value="<?php echo $subtopic['subtopicID']; ?>">
                        <button class="btn"  type="submit" value="create_thread" name="action" title="submit">submit</button>
                    </form>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>