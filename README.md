## Laravel Server 2024
**You can use it for `server` as well as `Frontend+Backend` both.
**laravel/framework": "^10.10"**
**"php": "^8.1"**
### Frontend Purpose -Follow this way
1. After cloning the starter template , you need to run those commands for setup the authentication for laravel app when you will use it for frontend purposes.
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
3. Now Run the server
```javascript
php artisan server
```

### Backend Purpose(Rest API)

1. Goto this route show the response of this server
```javascript
http://localhost:8000
```
2. If you don't have any data into the database then insert one user to the database
```javascript
POST http://localhost:8000/api/register
```
Goto `form data` 
![image](https://github.com/user-attachments/assets/b467fa47-34b6-4938-aabe-1e8464751a04)
 Then you will get the response like this, show in the right side
3. Get users list from the database
   1. You have to hit the login api first 
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
   For getting the users list you have to goto `Headers`
   ```javascript
   Accept     application/json
   ```
   Then goto `Auth` and give Bearer `token` <br>
   Now Hit  this url
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
