# Microservice Lumen

## Endpoints

User Authentication using JWTAuth, must include headers for every GET/POST request except for `login`:
```
Authorization: Bearer {{token}}
```

Example (get current authenticated user):
```
GET /api/v1/auth/authenticated HTTP/1.1
   Host: user.lm.local
   Accept: application/json
   Authorization: Bearer {{token}}
```

API JSON Responses.
```
   # User Login
   POST /api/v1/auth/login
   formData : {
        'email' => 'test@example.org',
        'password' => '123456',
    }
   
   # User Register
   POST /api/v1/auth/register
   formData : {
        'email' => 'test@example.org',
        'password' => '123456',
        'name' => 'Firstname Lastname',
        'username' => 'username'
    }

   # Get Current authenticated User
   GET /api/v1/auth/authenticated
     
   # Invalidate User Token
   GET /api/v1/auth/invalidate
   HEADERS : {
    Authorization: 'Bearer {{token}}'
   }
   
   # Refresh JWT Token
   GET /api/v1/auth/refresh
   HEADERS : {
       Authorization: 'Bearer {{token}}'
   }
      
   # Returns spesific user by id
   # or returns 404 'User not found'
   # if user does not exist
   GET /api/v1/users/{id}
   
   # Update user
   PUT /users/{id}
   
   # Update user password
   PUT /users/{id}/password
```

## Features 

**JWT** for the authentication of routes usable with the implemented service;

**Services** implemented to facilitate work:

    -Api helpers function
    -Response method to manage error/success/exceptions responses
    -Auth for manage user and jwt token
    -ACL method for manipulate user roles and permissions
    -Log method to manage file log
    -Cache implements methods to manage File and Redis cache with serialization
    
**Roles and Permissions** to assign them to users and manage routes with greater security;

**Repository pattern** implemented to manage the models in an abstract way and to allow the scalability of the business logic (used to guarantee also the code cleaning)

**Transformer** classes to manipulate data and better manage the recovery of related information (are transformed through functions implemented in ApiService)
  
**Artisan commands** to create Repository, ApiController, Provider and Transoformers (Other commands to create example file view documentation)

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
