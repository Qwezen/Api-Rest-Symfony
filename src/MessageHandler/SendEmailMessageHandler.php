<?php

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

use App\Service\EmailService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


#[AsMessageHandler]
// final class SendEmailMessageHandler
class SendEmailMessageHandler implements MessageHandlerInterface
{
    public function __construct(private EmailService $emailService) {}

    public function __invoke(SendEmailMessage $message): void
    {
        

        $this->emailService->sendEmail(
            $message->to,
            $message->subject,
            $message->content,
            $message->attachmentPath
        );
    }
}
