## Laravel Server 2024
**You can use it for `server` as well as `Frontend+Backend` both.
**laravel/framework": "^10.10"**
**"php": "^8.1"**
### Frontend Purpose -Follow this way
1. After cloning the Server , you need to run those commands for setup.
   1. Clone repository first
      ```javascript
      https://github.com/smmunna/laravel-server.git
      ```
      Remove the authorization of this repository
      ```javascript
         git remote rm origin
      ```
   2. goto `.env` file at the root of your project and write your own database name
   3. goto `create_users` migration file to add anyother columns whatever your need.
   4. Then run these commands
   ```javascript
    php artisan migrate
   ```
2. Delete the `uploads` folder from at the root `public/uploads` and run this command for managing the file management
```javascript
php artisan storage:link
```
1. Now Run the server
```javascript
php artisan server
```

### Backend Purpose(Rest API)

1. Goto `Postman` and goto `new request` and give this route to show the response of this server
```javascript
http://localhost:8000
```
2. If you don't have any data into the database then insert one user to the database
```javascript
POST http://localhost:8000/api/register
```
Goto `form data` to add user info. Then you will get the response like this, show in the right side.
![image](https://github.com/user-attachments/assets/5d853eda-ef46-4751-802d-82de1f911c2e)

3. Get users list from the database
   1. You have to hit the login api first because your user list api inside a authentication middleware. Ohter wise can not see the users list. 
   ```javascript
   POST http://localhost:8000/api/login
   ```
   Give email and password what you have inserted into the database
   ```javascript
      {
         "email":"munna@gmail.com",
         "password":"1234"
      }
   ```
   Then you will get the user and `token` from the response
   ```javascript
   {
   "user": {
      "id": 1,
      "name": "Sm Munna",
      "email": "munna@gmail.com",
      "email_verified_at": null,
      "phone": "01935991255",
      "photo": "uploads/photos/user_20241018_152131_67127cfb6e66e.png",
      "address": "Sarishabari, Jamalpur",
      "role": "user",
      "created_at": "2024-10-18T15:21:31.000000Z",
      "updated_at": "2024-10-18T15:21:31.000000Z"
   },
   "token": "3|OJ63d8AVvUVmCBG1FZeaC6qoYvtLkmjZIG0wHsglc6ddf2c0"
   }
   ```
   - Goto your `postman` and configure `Authorization` like this:
   ![image](https://github.com/user-attachments/assets/54232467-96b6-477f-92c6-c9c6f77b2a0a)
   - Then goto `Headers` configure like this way:
   ![image](https://github.com/user-attachments/assets/21509838-991b-4b3a-a0c8-e5e7c48514d5)
   - Now Hit the url and you will get response like this:
   ![image](https://github.com/user-attachments/assets/22c66add-cadc-4731-9e7e-752a6ced122b)

   - Here is the configuration for `Thunder Client` Software follow this:
   - For getting the users list you have to goto `Headers`
   ```javascript
   Accept     application/json
   ```
   - Then goto `Auth` and give Bearer `token` <br>
   - Now Hit  this url
```javascript
GET http://localhost:8000/api/users
```
Now you will get the response like this:
```javascript
[
  {
    "id": 1,
    "name": "Sm Munna",
    "email": "munna@gmail.com",
    "email_verified_at": null,
    "password": "$2y$12$.cd/djg/9LI.9NYcgGRRHussZpjZQEzUlbod9Wyu0A75zikJKilIq",
    "phone": "01935991255",
    "photo": "uploads/photos/user_20241018_152131_67127cfb6e66e.png",
    "address": "Sarishabari, Jamalpur",
    "role": "user",
    "remember_token": null,
    "created_at": "2024-10-18 15:21:31",
    "updated_at": "2024-10-18 15:21:31"
  }
]
```

### Authentication Methods
Sanctum Authentication added

### Built in API Lists, routes/api.php
1. Home
   - GET  http://localhost:8000
2. Login, Registration and Logout
   - POST http://localhost:8000/api/register 
   - POST http://localhost:8000/api/login 
3. Users List:
   - GET http://localhost:8000/api/users

### API Rate Limiting
1. Goto `app/Providers/RouteServiceProvider.php`
```php
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
```
2. Route will be like this way:
```php
 Route::group(['middleware' => 'throttle:role:admin', 'prefix' => 'admin'], function () {
        // add routes here
        Route::get('/users', [UserController::class, 'usersList']);
    });

```
3. Rate Limiting Methods
In Laravel's `RateLimiter`, the `Limit` class provides several methods to define and customize rate-limiting rules. Here's a list of the commonly used methods and their descriptions:

---

4. Methods in the `Limit` Class

5. **`perMinute($maxAttempts)`**
   - Sets the maximum number of attempts allowed per minute.
   - **Example**:
     ```php
     return Limit::perMinute(10);
     ```

6. **`perHour($maxAttempts)`**
   - Sets the maximum number of attempts allowed per hour.
   - **Example**:
     ```php
     return Limit::perHour(100);
     ```

7. **`perDay($maxAttempts)`**
   - Sets the maximum number of attempts allowed per day.
   - **Example**:
     ```php
     return Limit::perDay(1000);
     ```

8. **`by($key)`**
   - Specifies the unique key for rate-limiting (e.g., user ID, IP address).
   - **Example**:
     ```php
     return Limit::perMinute(5)->by($request->ip());
     ```

9. **`response($callback)`**
   - Defines a custom response when the limit is exceeded.
   - **Example**:
     ```php
     return Limit::perMinute(5)->response(function () {
         return response('Too many requests!', 429);
     });
     ```

10. **`decayMinutes($minutes)`**
   - Sets the time in minutes after which the limit resets.
   - **Example**:
     ```php
     return Limit::perMinute(5)->decayMinutes(2);
     ```

11. **`decaySeconds($seconds)`** *(Available in Laravel 10)*
   - Sets the time in seconds after which the limit resets.
   - **Example**:
     ```php
     return Limit::perMinute(5)->decaySeconds(30);
     ```

12. **`name($name)`**
   - Assigns a name to the rate limit rule (used for debugging or logging purposes).
   - **Example**:
     ```php
     return Limit::perMinute(5)->name('api-rate-limit');
     ```

13. **`cooldown($seconds)`**
   - Introduced in Laravel 10, this method specifies a cooldown period (in seconds) after the maximum attempts are reached.
   - **Example**:
     ```php
     return Limit::perMinute(5)->cooldown(60);
     ```

14. **`redirectTo($url)`**
    - Specifies a URL to redirect users to when they hit the rate limit.
    - **Example**:
      ```php
      return Limit::perMinute(5)->redirectTo('/rate-limit-warning');
      ```

---

Example: Combining Methods
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
- **`perMinute`, `perHour`, `perDay`**: Define rate limits.
- **`by`**: Specify the key for rate limiting.
- **`response`**: Customize responses for limit violations.
- **`decayMinutes` / `decaySeconds`**: Set cooldown periods.
- **`name`**: Assign a label to the limit rule.
- **`cooldown`** *(Laravel 10)*: Add explicit cooldown after limits.

These methods allow you to tailor rate-limiting rules based on your application's needs.