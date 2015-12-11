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
        <title>Game Convention Template - Buy a Badge</title>
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
                    <?php
                        if ($paidforbadge['COUNT(*)'] < 1) {
                            include '/home/simpleco/demo2/app/includes_html/htmlsnippets/badge_form.html';
                        }
                        else {
                            echo '<p>You have already purchased a badge for yourself.</p>';
                            echo '<p>You can register and purchase badges for kids <a href="localhost/?mybadges">here</a>.</p>';
                        }
                    ?>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>