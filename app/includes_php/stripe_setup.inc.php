<?php

$stripe = array(
  secret_key      => getenv('sk_test_y4j5rHbNnOXWIgtsMbQUvWFh'),
  publishable_key => getenv('pk_test_BLVGeqEk7kmWWr1tfWaFj6o4')
);
Stripe::setApiKey($stripe['sk_test_y4j5rHbNnOXWIgtsMbQUvWFh']);
