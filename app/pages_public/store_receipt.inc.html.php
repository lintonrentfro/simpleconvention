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
                    <h4>Store Receipt</h4>
                    <h5>Purchase Date: <?php echo date('m-d-y'); ?></h5>
                    <h5>Shipping Information</h5>
                    <p>
                        <?php htmlout($user_info['first_name']); ?> <?php htmlout($user_info['last_name']); ?><br>
                        <?php htmlout($user_info['address1']); echo ', '; htmlout($user_info['address2']);?><br>
                        <?php htmlout($user_info['city']); ?>, <?php htmlout($user_info['state']); echo '  '; ?><?php htmlout($user_info['zip']); ?>
                    </p>
                    <p></p>
                    <h5>Items</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Price Each</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>
                                <?php 
                                setlocale(LC_MONETARY, 'en_US');
                                echo money_format('%.2n', $item['price']/100);  ?>
                            </td>
                            <td><?php echo $item['quantity']; ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                    <strong>Total: <?php echo $total; ?></strong>
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