<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Customer
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $business_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property int|null $country
 * @property string|null $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUserId($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Plan
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $plan_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $User
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlanPrice> $price
 * @property-read int|null $price_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product_Plan> $product
 * @property-read int|null $product_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptionplan
 * @property-read int|null $subscriptionplan_count
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan wherePlanDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereUserId($value)
 */
	class Plan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PlanPrice
 *
 * @property int $id
 * @property int $plan_id
 * @property string $stripe_price_id
 * @property int $price
 * @property string $plan_type
 * @property int|null $discount
 * @property string|null $discount_limit
 * @property string|null $discount_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Plan $plan
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice whereDiscountLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice wherePlanType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice whereStripePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanPrice whereUpdatedAt($value)
 */
	class PlanPrice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property string|null $stripe_price_id
 * @property string $image
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product_Plan> $plan
 * @property-read int|null $plan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User_Product> $product_UserProduct
 * @property-read int|null $product__user_product_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStripePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUserId($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product_Plan
 *
 * @property int $id
 * @property int $product_id
 * @property int $plan_id
 * @property string $quantity Product Quantity For The Plan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Plan|null $plansproducts
 * @property-read \App\Models\Product|null $productsplans
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan whereUpdatedAt($value)
 */
	class Product_Plan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Subscription
 *
 * @property int $id
 * @property int $user_id
 * @property int $customer_id
 * @property string|null $subscription_id
 * @property string|null $stripe_price_id
 * @property string $status
 * @property int $plan_id
 * @property string $plan_type
 * @property string|null $current_period_start
 * @property string|null $current_period_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Plan $Plan
 * @property-read \App\Models\User $User
 * @property-read mixed $current_period_end_formatted
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription active()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription notActive()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCurrentPeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCurrentPeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription wherePlanType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereStripePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subscription whereUserId($value)
 */
	class Subscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $stripe_id
 * @property string|null $pm_type
 * @property string|null $pm_last_four
 * @property string|null $trial_ends_at
 * @property int|null $card_expiration_month
 * @property int|null $card_expiration_year
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Plan> $Plan
 * @property-read int|null $plan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Subscription|null $subscriptionplan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Cashier\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCardExpirationMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCardExpirationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePmLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePmType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User_Product
 *
 * @property-read \App\Models\Product $Product
 * @property-read \App\Models\User $User
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product query()
 */
	class User_Product extends \Eloquent {}
}

