<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_storeadmin/_head.inc.html'; ?>
        <title>Game Convention Template</title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <img src="/img/logo.png" />
                </div>
            </div>
            <div class="row-fluid">
                <div class="span3">
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_store_admin.inc.html.php'; ?>
                </div>
                <div class="span9">
                    <h4>
                        <?php 
                            if (isset($title)) {
                                echo $title;
                            }
                        ?>
                    </h4>
                    <p>
                        <?php 
                            if (isset($longdesc)) {
                                echo $longdesc;
                            }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>



