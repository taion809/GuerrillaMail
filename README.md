GuerrillaMail
=============

A Simple Library for GuerrillaMail

Example Usage
=============

```php
$connection = new \GuerrillaMail\GuerrillaConnect\CurlConnection();

//The second parameter is the client IP.
//The third parameter is the client Browser Agent.
//The fourth parameter is the client's sid (optional)
$gm = new \GuerrillaMail\GuerrillaMail($connection, '127.0.0.1', 'GuerrillaMail_Library');

//Obtain an email address
$response = $gm->get_email_address();

//Fetch user's emails.
$emails = $gm->check_email($response['sid_token']);
```
