# https://www.youtube.com/watch?v=GGTQwdQSl9M&list=PLDc9bt_00KcJaSZXhpCqT6J05HP2qt5JQ&index=17

# Good source on github

> https://github.com/HadiNiazi/Create-Subscription-Using-Laravel-Cashier/blob/master/resources/views/stripe/plans.blade.php

# Blog source

> https://medium.com/fabcoding/laravel-7-create-a-subscription-system-using-cashier-stripe-77cdf5c8ea5d

### Checkout

> After checkout to stripe, The user gets a Stripe_ID

### Getting the user from Subscription Model

> vendor/laravel/cashir/src/Subscription.php

## Cancelling subscription

> https://laravel.com/docs/9.x/billing#cancelling-subscriptions
> web.php -> Route::get('subscriptions/cancel',...)
> /views/stripe/subscriptions/index.blade.php
> SubscritionController -> public function cancelSubscriptions

## Spit out Cachier Migrations file

> php artisan vendor:publish
> 12
