<?php


namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, string $content, ?string $attachmentPath = null, array $games): void
    {
        $html = $this->twig->render('emails/newsletter.html.twig', [
            'games' => $games,
        ]);

        $email = (new Email())
            ->from('noreply@example.com')
            ->to($to)
            ->subject('🎮 Les sorties jeux vidéo de la semaine')
            ->html($content);

        if ($attachmentPath) {
            $email->attachFromPath($attachmentPath);
        }

        $this->mailer->send($email);
    }
}
