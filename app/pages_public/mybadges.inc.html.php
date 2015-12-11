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
        <title>Game Convention Template - My Info</title>
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
                    <h4>Badge Information</h4>
                    <div class="span5">
                        <h5>Badge Prices</h5>
                        <table class="table table-condensed">
                            <tr>
                                <td>Regular Price</td>
                                <td><?php echo $price; ?></td>
                                <td><?php echo $primary_user_badge_status; ?></td>
                            </tr>
                                <?php
                                    if ($con_info['kid_badge_on'] == 1) {
                                        echo '
                                            <tr>
                                                <td>Children Ages ' . $kid_badge_age_range . '</td>
                                                <td>' . $kidprice . '</td><td>';
                                        include '/home/simpleco/demo2/app/includes_html/htmlsnippets/kid_badge.html';
                                        echo '</td></tr>'; }
                                    if ($con_info['free_badge_on'] == 1) {
                                        echo '
                                            <tr>
                                                <td>Children Under ' . $con_info['free_badge_max_age'] . '</td>
                                                <td> free </td><td>';
                                        include '/home/simpleco/demo2/app/includes_html/htmlsnippets/free_badge.html';
                                        echo '</td></tr>'; }
                                    if (($con_info['free_badge_on'] == 0) and ($con_info['kid_badge_on'] == 0)) {
                                        echo '
                                            <tr>
                                                <td>Child Badge</td>
                                                <td>' . $price . '</td><td>';
                                        include '/home/simpleco/demo2/app/includes_html/htmlsnippets/kid_badge.html';
                                        echo '</td></tr>';
                                    } ?>
                        </table>
                    </div>
                    <div class="span1"></div>
                    <div class="span4">
                        <h5>Your Badges</h5>
                        <table class="table table-condensed">
                            <tr>
                                <td><?php htmlout($user_info['first_name']); echo ' '; htmlout($user_info['last_name']); ?></td>
                                <td><?php echo $primary_user_badge_status; ?></td>
                            </tr>
                            <?php foreach($kids_with_badge_status as $kid): ?>
                            <tr>
                                <td><?php htmlout($kid['first_name']); echo ' '; htmlout($kid['last_name']); ?></td>
                                <td><?php echo $kid['badge_status']; ?></td>
                            </tr>
                            <?php endforeach ?>
                            <tr><td><?php include '/home/simpleco/demo2/app/includes_html/htmlsnippets/add_kid_modal.html'; ?></td><td></td></tr>
                        </table>
                    </div>
                </div>
                <div class="span1"></div>
            </div>
        </div>
    </body>
</html>