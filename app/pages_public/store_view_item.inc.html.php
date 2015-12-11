<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - <?php echo $item['name']; ?></title>
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
                    <div class="row-fluid">
                        <div class="span4">
                            <p></p>
                            <img class="img-rounded" src="<?php echo $item['image']; ?>" width="175" height="175" alt="item image">
                        </div>
                        <div class="span6">
                            <h4><?php echo $item['name']; ?></h4>
                            <small>Price: </small>
                            <strong><?php 
                                setlocale(LC_MONETARY, 'en_US');
                                echo money_format('%.2n', $item['price']/100);  ?></strong>
                            <p>
                                <?php htmlout($item['description']); ?>
                            </p>
                            <form  action="?" method="post" class="form-inline">
                                <input type="text" class="input-mini" value="1" name="quantity">
                                <input type="hidden" name="itemID" value="<?php echo $item['itemID']; ?>">
                                <button type="submit" class="btn btn-inverse" value="add_to_cart" name="action" title="add to cart">add to cart</button>
                            </form>
                        </div>
                        <div class="span2">
                        </div>
                    </div>
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