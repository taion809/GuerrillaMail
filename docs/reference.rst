=========
Reference
=========

getEmailAddress
===============
Generates a new email address.

    .. code-block:: php
        $email = $client->getEmailAddress();


getEmailList
============
Fetch up to 20 new emails starting from the oldest email.  Should only be called once.

offset
    If $offset is set, skip to the offset value (0 - 19)
seq
    If $seq is set, return up to 20 new emails starting from $seq

    .. code-block:: php
        $list = $client->getEmailList($offset, $seq);

checkEmail
==========
Fetch up to 20 new emails starting from the oldest email.

seq
    If $seq is set, return up to 20 new emails starting from $seq

    .. code-block:: php
        $list = $client->checkEmail($seq);

fetchEmail
==========
Return email based on email id.

email_id
    mail_id of the requested email

    .. code-block:: php
        $email = $client->fetchEmail(123);

setEmailAddress
===============
Change users email address.

email_user
    Desired email address.

    .. code-block:: php
        $result = $client->setEmailAddress("awesomenaut");

forgetMe
========
Forget users email and sid_token

    .. code-block:: php
        $client->forgetMe();

delEmail
========
Delete list of emails

email_ids
    An array of mail_id's to be deleted.

    .. code-block:: php
        $client->delEmail([1,2,3]);
