# Behat email testing suite

A Simple Library that utilises [GuerrillaMail](http://www.guerrillamail.com) and provides some basic behat tests, that 
allow you to test succesful form submissions that send emails. 

[GuerrillaMail](http://www.guerrillamail.com) is a free service and is therefore perfect for email testing. This project 
was forked from [taion809/GuerrillaMail](https://github.com/taion809/GuerrillaMail) and has been reduced marginally to 
just use CURL and to add some basic modeling to the [GuerrillaMail](http://www.guerrillamail.com) API responses.

It is only recommended to use this library for testing in behat, as we will be undertaking more work to improve 
underlying API response modeling, however we will try to ensure compatibility with tests in tagged releases.

## Installation
This library uses composer, you can install it using composer on the command like so,

```bash
composer require comicrelief/guerrillamail
```

## Behat Tests

The follwing behat tests will check for emails delivered into the guerillamail account for 2 minues and will then fail.

### Generate a new email address
The following will generate a new email inbox.

An email address is initially created when the package is instantiated, this therefore only needs to be run when running 
multiple tests on email fields.

```text
Then I generate a new test email address
```

### Fill a field with a test email address

The following will field a field with a test email address,

```text
Then I fill in the "edit-email" field with a test email address
```

### Check inbox for email with subject & body

The following will check the inbox to see if an email exists with 'test' in the body and 'Test'
in the subject.

```text
Then I should receive an email with "test" in the body and "Test" in the subject
```
### Check inbox for email with subject content

The following will check the inbox to see if there is an email with 'Test' in the email subject.

```text
Then I should receive an email with "Test" in the subject
```

### Check inbox for email with body content

The following will check the inbox to see if there is an email with 'test' in the email body.

```text
Then I should receive an email with "Test" in the body
```

## License

This project is licensed under the MIT License.
