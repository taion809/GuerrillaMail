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
```
