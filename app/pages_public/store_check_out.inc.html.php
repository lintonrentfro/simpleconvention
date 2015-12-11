<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/pages_public/_head.inc.html'; ?>
        <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <script type="text/javascript">
            // this identifies your website in the createToken call below
            Stripe.setPublishableKey('pk_test_BLVGeqEk7kmWWr1tfWaFj6o4');
 
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    // re-enable the submit button
                    $('.submit-button').removeAttr("disabled");
                    // show the errors on the form
                    $(".payment-errors").html(response.error.message);
                } else {
                    var form$ = $("#payment-form");
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                    // and submit
                    form$.get(0).submit();
                }
            }
 
            $(document).ready(function() {
            $("#payment-form").submit(function(event) {
              // disable the submit button to prevent repeated clicks
              $('.submit-button').attr("disabled", "disabled");

              Stripe.createToken({
                  number: $('.card-number').val(),
                  cvc: $('.card-cvc').val(),
                  exp_month: $('.card-expiry-month').val(),
                  exp_year: $('.card-expiry-year').val()
              }, stripeResponseHandler);

              // prevent the form from submitting with the default action
              return false;
            });
          });
 
        </script>
        <title>Game Convention Template - Checkout</title>
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
                            <td>Quantity: <?php echo $item['quantity']; ?></td>
                        </tr>
                        <?php endforeach ?>
                        <tr><td></td><td></td><td></td><td></td></tr>
                    </table>
                    <h5>Total: <?php echo $total; ?></h5>
                    <h4>Shipping Information</h4>
                    <p>
                        <?php htmlout($user_info['first_name']); ?> <?php htmlout($user_info['last_name']); ?><br>
                        <?php htmlout($user_info['address1']); echo ', '; htmlout($user_info['address2']);?><br>
                        <?php htmlout($user_info['city']); ?>, <?php htmlout($user_info['state']); echo '  '; ?><?php htmlout($user_info['zip']); ?>
                    </p>
                    <!-- to display errors returned by createToken -->
                    <span class="payment-errors"></span>

                    <form class="form-horizontal" action="" method="POST" id="payment-form">
                        <div class="control-group"><div class="form-row">
                            <label class="control-label">Card Number</label>
                            <div class="controls">
                                <input type="text" size="20" autocomplete="off" class="card-number" />
                            </div></div>
                        </div>
                        <div class="control-group"><div class="form-row">
                            <label class="control-label">CVC</label>
                            <div class="controls">
                                <input type="text" autocomplete="off" class="card-cvc input-mini" />
                            </div></div>
                        </div>
                        <div class="control-group"><div class="form-row">
                            <label class="control-label">Expiration (MM/YYYY)</label>
                            <div class="controls">
                                <input type="text" size="2" class="card-expiry-month input-mini"/>
                                <span> / </span>
                                <input type="text" size="4" class="card-expiry-year input-mini"/>
                            </div></div>
                        </div>
                        <div class="control-group"><div class="form-row">
                            <div class="controls">
                                <button class="btn" type="submit" class="submit-button" value="check_out" name="action">Purchase</button>
                            </div>
                        </div></div>
                    </form>
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