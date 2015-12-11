<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Contact Us</title>
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
                    <h4>How to Reach Us</h4>
                    <h5>Mail</h5>
                    <p>
                        <?php htmlout($coninfo['official_name']); ?>
                        <br>
                        <?php htmlout($coninfo['address1']); ?>
                        <?php if ($coninfo['address2'] != '') { 
                            htmlout($coninfo['address2']); 
                            echo '<br>'; } ?>
                        <?php htmlout($coninfo['city']); echo ', '; htmlout($coninfo['state']); echo ' '; htmlout($coninfo['zip']); ?>
                    </p>
                    <h5>Online</h5>
                    <p>
                        Web: <a href="<?php htmlout($coninfo['web']); ?>"><?php htmlout($coninfo['web']); ?></a>
                        <br>
                        Facebook: <a href="<?php htmlout($coninfo['facebook']); ?>"><?php htmlout($coninfo['facebook']); ?></a>
                        <br>
                        Twitter: <a href="http://twitter.com/<?php htmlout($coninfo['twitter']); ?>">@<?php htmlout($coninfo['twitter']); ?></a>
                    </p>
                    <?php include '/home/simpleco/demo2/app/includes_html/admin_provided/contact.html'; ?>
                </div>
            <div class="span1"></div>
            </div>
<!--            <div class="row-fluid">
                <div class="span12">
                    <h4>footer</h4>
                </div>
            </div>-->
        </div>
    </body>
</html>