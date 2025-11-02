<?php


namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, string $content, ?string $attachmentPath = null): void
    {
        $email = (new Email())
            ->from('noreply@example.com')
            ->to($to)
            ->subject($subject)
            ->html($content);

        if ($attachmentPath) {
            $email->attachFromPath($attachmentPath);
        }

        $this->mailer->send($email);
    }
}
