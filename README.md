# symfony-mailer-mailgun-bug-reproducer

- Clone repository
- Run `composer install`
- `MAILGUN_DSN=mailgun+api://... php bug.php from@address to@address`
- Out of two attachments, only one will be attached (the one without any non-ascii character)
