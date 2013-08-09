GuerrillaMail
=============

A Simple Library for GuerrillaMail

Example Usage
=============

```php

use Johnsn\GuerrillaMail\GuerrillaConnect\CurlConnection;
use Johnsn\GuerrillaMail\GuerrillaMail;

//The first parameter is the client's IP.
//The second parameter is the client's Browser Agent.
//There is an optional third parameter to set the api endpoint
$connection = new CurlConnection("127.0.0.1", "GuerrillaMail_Library");

//The second parameter is the client's sid (optional)
$gm = new GuerrillaMail($connection);

//Obtain an email address
$response = $gm->get_email_address();

//Fetch user's latest emails.
$emails = $gm->check_email();
```

