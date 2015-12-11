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
                        <li class="active">Topics</li>
                    </ul>
                    <?php foreach ($topics as $topic): ?>
                        <h5><?php htmlout($topic['topicname']); ?></h5>
                        <?php
                            foreach ($subtopics as $subtopic): 
                                if ($subtopic['undertopicID'] == $topic['topicID']) {
                                    echo '<a href="/?id=' . $subtopic['subtopicID'] . '&action=subtopic">' . $subtopic['subtopicname'] . '</a><br>';
                                }
                            endforeach;
                        ?>
                    <?php endforeach; ?>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>