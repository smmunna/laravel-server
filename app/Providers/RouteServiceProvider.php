<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });


        // Role based RateLimiter
        RateLimiter::for('role:user', function (Request $request) {
            $ip = $request->ip();
            return Limit::perMinute(1)
                ->by(optional($request->user())->id ?: $ip)
                ->response(function () use ($ip) {
                    return response()->json([
                        'message' => 'You have exceeded the rate limit.',
                        'hint' => 'User can only make 1 request per minute.',
                        'status_code' => 429,
                        'retry_after' => 'Please wait a minute before trying again.',
                        'your_ip' => $ip
                    ], 429);
                });
        });

        RateLimiter::for('role:admin', function (Request $request) {
            return Limit::none(); // No limit for admin
        });
    }
}


/*

In Laravel's `RateLimiter`, the `Limit` class provides several methods to define and customize rate-limiting rules. Here's a list of the commonly used methods and their descriptions:

---

### Methods in the `Limit` Class

1. **`perMinute($maxAttempts)`**
   - Sets the maximum number of attempts allowed per minute.
   - **Example**:
     ```php
     return Limit::perMinute(10);
     ```

2. **`perHour($maxAttempts)`**
   - Sets the maximum number of attempts allowed per hour.
   - **Example**:
     ```php
     return Limit::perHour(100);
     ```

3. **`perDay($maxAttempts)`**
   - Sets the maximum number of attempts allowed per day.
   - **Example**:
     ```php
     return Limit::perDay(1000);
     ```

4. **`by($key)`**
   - Specifies the unique key for rate-limiting (e.g., user ID, IP address).
   - **Example**:
     ```php
     return Limit::perMinute(5)->by($request->ip());
     ```

5. **`response($callback)`**
   - Defines a custom response when the limit is exceeded.
   - **Example**:
     ```php
     return Limit::perMinute(5)->response(function () {
         return response('Too many requests!', 429);
     });
     ```

6. **`decayMinutes($minutes)`**
   - Sets the time in minutes after which the limit resets.
   - **Example**:
     ```php
     return Limit::perMinute(5)->decayMinutes(2);
     ```

7. **`decaySeconds($seconds)`** *(Available in Laravel 10)*
   - Sets the time in seconds after which the limit resets.
   - **Example**:
     ```php
     return Limit::perMinute(5)->decaySeconds(30);
     ```

8. **`name($name)`**
   - Assigns a name to the rate limit rule (used for debugging or logging purposes).
   - **Example**:
     ```php
     return Limit::perMinute(5)->name('api-rate-limit');
     ```

9. **`cooldown($seconds)`**
   - Introduced in Laravel 10, this method specifies a cooldown period (in seconds) after the maximum attempts are reached.
   - **Example**:
     ```php
     return Limit::perMinute(5)->cooldown(60);
     ```

10. **`redirectTo($url)`**
    - Specifies a URL to redirect users to when they hit the rate limit.
    - **Example**:
      ```php
      return Limit::perMinute(5)->redirectTo('/rate-limit-warning');
      ```

---

### Example: Combining Methods
```php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(5)
        ->by($request->user()?->id ?: $request->ip())
        ->decayMinutes(1)
        ->name('api-rate-limit')
        ->response(function () {
            return response()->json([
                'message' => 'Rate limit exceeded. Try again later.'
            ], 429);
        });
});
```

### Summary:
- **`perMinute`, `perHour`, `perDay`**: Define rate limits.
- **`by`**: Specify the key for rate limiting.
- **`response`**: Customize responses for limit violations.
- **`decayMinutes` / `decaySeconds`**: Set cooldown periods.
- **`name`**: Assign a label to the limit rule.
- **`cooldown`** *(Laravel 10)*: Add explicit cooldown after limits.

These methods allow you to tailor rate-limiting rules based on your application's needs.
*/ 