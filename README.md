# Comicrelief/GuerrillaMail

A Simple Library that utilises [GuerrillaMail](http://www.guerrillamail.com) and provides some basic behat tests, that 
allow you to test email form submissions.

## Installation
This library uses composer, you can install it using composer on the command like so,

```bash
composer require comicrelief/guerrillamail
```

## Example Behat Tests

### Generate a new email address
The following will generate a new email inbox.

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
Then I should receive an email with "Test" in the subject
```

## License

This project is licensed under the MIT License.
