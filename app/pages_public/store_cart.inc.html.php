<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <title>Game Convention Template - Cart</title>
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
                    <h4>Cart</h4>
                    <?php if ($total == "$0.00") {
                        echo 'empty'; 
                        } ?>
                    <table class="table">
                        <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><a href="<?php echo 'localhost/?itemID=' . $item['itemID'] . '&action=item'; ?>"><img class="img-rounded" src="<?php echo $item['image']; ?>" width="40" height="40" alt="item image"></a></td>
                            <td><a href="<?php echo 'localhost/?itemID=' . $item['itemID'] . '&action=item'; ?>"><?php echo $item['name']; ?></a></td>
                            <td><strong>
                                <?php 
                                setlocale(LC_MONETARY, 'en_US');
                                echo money_format('%.2n', $item['price']/100);  ?></a>
                                </strong>
                            </td>
                            <td>
                                <form  action="?" method="post" class="form-inline">
                                    <div class="input-append">
                                        <input type="text" class="span2" name="quantity" value="<?php echo $item['quantity']; ?>">
                                        <input type="hidden" name="ID" value="<?php echo $item['ID']; ?>">
                                        <button type="submit" class="btn" value="update_cart" name="action" title="update">update</button>
                                        <button type="submit" class="btn" value="remove_cart" name="action" title="remove"><i class="icon-remove"></i></button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach ?>
                        <tr><td></td><td></td><td></td><td></td></tr>
                    </table>
                    <h4>Total: <?php echo $total; ?></h4>
                    <a href="?check_out">check out</a>
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