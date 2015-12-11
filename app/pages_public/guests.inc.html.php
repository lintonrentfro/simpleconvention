<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Guests</title>
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
                    <?php foreach ($guests as $guest): ?>
                    <div class="media">
                        <a class="pull-left">
                            <img class="media-object img-rounded" src="<?php echo $guest['photo_url']; ?>" width="150" height="150">
                        </a>
                        <div class="media-body">
                            <h3 class="media-heading"><?php echo $guest['professional_name']; ?></h3>
                            <a href="/?userID=<?php echo $guest['userID']; ?>&action=view_events_of_guest"><i class="icon-list-alt icon-white"></i></a> <small>EVENTS</small><br>
                            <p><?php echo $guest['short_description'] . ' '; ?>
                            <i class="icon-info-sign icon-white" data-toggle="collapse" data-target="#<?php echo 'info' . $guest['userID']; ?>"></i></p>
                            <div id="<?php echo 'info' . $guest['userID']; ?>" class="collapse">
                                <p><?php htmlout ($guest['full_description']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>