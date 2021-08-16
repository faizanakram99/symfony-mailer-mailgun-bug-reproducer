<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Webmozart\Assert\Assert;

require __DIR__.'/vendor/autoload.php';

$_SERVER += $_ENV;

try {
    Assert::keyExists($argv, 1, 'From address not given');
    Assert::keyExists($argv, 2, 'To address not given');
    Assert::keyExists($_SERVER, 'MAILER_DSN', 'Mailer dsn not given');

    Assert::startsWith($_SERVER['MAILER_DSN'], 'mailgun+api', 'Please specify mailgun dsn url');
} catch (\InvalidArgumentException $exception) {
    echo $exception->getMessage();
    exit(1);
}

$from = $argv[1];
$to = $argv[2];

$email = new Email();

$attachment = static fn (string $contents) => fopen('data://text/plain;base64,'.base64_encode($contents), 'rb');

$email
    ->from($from)
    ->to($to)
    ->subject('bug reproducer')
    ->text('Attachments ?')
;

$email
    ->attach(
        $attachment('This will fail.'),
        'Confirmación cliente',
        'text/plain',
    )
    ->attach(
        $attachment('This will pass.'),
        'Confirmacion cliente', // removed ó
        'text/plain',
    )
;

$transport = Transport::fromDsn($_SERVER['MAILER_DSN']);
$mailer = new Mailer($transport);

$mailer->send($email);
