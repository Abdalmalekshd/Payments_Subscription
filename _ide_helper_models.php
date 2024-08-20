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
 * App\Models\Plan
 *
 * @property int $id
 * @property string $name
 * @property string|null $monthly_price_id
 * @property string|null $yearly_price_id
 * @property int|null $monthly_price
 * @property int|null $yearly_price
 * @property array $features
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product_Plan> $product
 * @property-read int|null $product_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptionplan
 * @property-read int|null $subscriptionplan_count
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereMonthlyPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereMonthlyPriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereYearlyPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereYearlyPriceId($value)
 */
	class Plan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property string $image
 * @property string $stripe_one_time_price_id
 * @property string $stripe_weekly_price_id
 * @property string $stripe_yearly_price_id
 * @property string $stripe_monthly_price_id
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
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStripeMonthlyPriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStripeOneTimePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStripeWeeklyPriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStripeYearlyPriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product_Plan
 *
 * @property-read \App\Models\Plan|null $plansproducts
 * @property-read \App\Models\Product|null $productsplans
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_Plan query()
 */
	class Product_Plan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Subscription
 *
 * @property int $id
 * @property int $user_id
 * @property string $subscription_id
 * @property string $stripe_price_id
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User_Product> $User_UserProduct
 * @property-read int|null $user__user_product_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Subscription|null $subscriptionplan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Cashier\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
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
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property string $status
 * @property string|null $purchase_type
 * @property string|null $subscription_start_date
 * @property string|null $subscription_end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $subscription_id
 * @property-read \App\Models\Product $Product
 * @property-read \App\Models\User $User
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product wherePurchaseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product whereSubscriptionEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product whereSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product whereSubscriptionStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User_Product whereUserId($value)
 */
	class User_Product extends \Eloquent {}
}

