
        
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '/home/simpleco/demo2/app/includes_html/css.inc.html.php'; ?>
        <meta charset="utf-8">
        
        <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <!-- jQuery is used only for this example; it isn't required to use Stripe -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
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
                    // createToken returns immediately - the supplied callback submits the form if there are no errors
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                    return false; // submit from callback
                });
            });
 
        </script>

        <title>Game Convention Template - Registration Admin</title>
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
                    <?php include '/home/simpleco/demo2/app/includes_html/menu_registration_admin.inc.html.php'; ?>
                </div>
                <div class="span9">
                    <h4>Purchase Badge</h4>
                    <p>
                        If this is for a new account, the email address supplied has been sent a link which will
                         allow the user to set their password for the site.  This can be done 
                         quickly and easily within the next 7 days from any phone, tablet, or computer.
                    </p>
                    <h5>Customer:</h5>
                    <p>
                        <?php htmlout($user_info['first_name']); ?> <?php htmlout($user_info['last_name']); ?><br>
                        <?php htmlout($user_info['address1']); echo ', '; htmlout($user_info['address2']);?><br>
                        <?php htmlout($user_info['city']); ?>, <?php htmlout($user_info['state']); echo '  '; ?><?php htmlout($user_info['zip']); ?>
                    </p>
                    <!-- to display errors returned by createToken -->
                    <span class="payment-errors"></span>
                    <h5>Buy Badge With Credit/Debit Card</h5>
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
                                <button class="btn" type="submit" class="submit-button" value="charge_card" name="action">Purchase Convention Badge for <?php echo $price; ?></button>
                            </div>
                        </div></div>
                    </form>
                    <h5>Buy Badge With Cash or Check</h5>
                    <form action="" method="POST">
                         <input type="hidden" name="customerID" value="<?php echo $user_info['id']; ?>">
                         <button type="submit" value="cash_or_check" name="action" title="record as paid and email receipt">Record as Paid and Email Receipt</button>
                    </form>
                </div>
            </div>
<!--            <div class="row-fluid">
                <div class="span12">
                    <h4>footer</h4>
                </div>
            </div>-->
        </div>
    </body>
</html>