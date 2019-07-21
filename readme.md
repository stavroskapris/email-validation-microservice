# Email validation  microservice
##Functionality

This microservice takes as parameters one email or domain and one provider and returns whether 
the corresponding email address is disposable. The microservice provides only one route. 

Parameters
* `email` - the email address to validate (required)
* `provider` - the validation provider to be used (optional)

In case no provider param is posted, the service uses the one which is set as default.
Any extra parameters will be ignored.

The microservice caches the returned value for a given time and returns 
Success-Error response in json format.

# Routes


### Request
  `Post /api/validate`
 
    Accept: application/x-www-form-urlencoded
        
### Params
    |-------------|--------------------------------|------------------|
    | Name        | Value                          | Required/Optional|
    |-------------|--------------------------------|------------------|      
    | email       | the email address to validate  | required         |
    |-------------|--------------------------------|------------------|
    | provider    | string, the email validation   | optional         |
    |             | provider to use                |                  |
    |-------------|--------------------------------|------------------|
    
### Providers
     Currently the service supports three providers
     * kickbox(default)
     * debounce
  
### Success Response
    
     Response body:
    {
       "response":{
          "status":1,
          "message":"success",
          "shortUrl":"your shortened url"
       }
    }
    
    "status":1 and "message":"success" indicates success
### Error Response
    Response body: 
    {
      "response":{
         "status":0,
         "message":"Error message",
         "error_code": Error code
      }
    }
    
    "status":0 indicates failure, refer to error message and code for further information

## Error Codes and Messages
    * 1000  The url param is required
    * 1001  Service Unavailable
    * 1002  Url is not valid

        
## Run the app
    Inside projects directory command line run:
    php artisan serve

## Run the tests
    Inside projects directory command line run:
    phpunit
