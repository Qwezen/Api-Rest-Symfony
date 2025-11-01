<?php

namespace App\Scheduler;

use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Contracts\Cache\CacheInterface;

use App\Message\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Scheduler\Task\MessengerTask;

#[AsSchedule('email_newsletter')]
final class NewsletterSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(
                // @TODO - Create a Message to schedule
                // RecurringMessage::every('1 hour', new App\Message\Message()),
            )
            ->stateful($this->cache)
        ;
    }

    public function __invoke(Schedule $schedule, MessageBusInterface $bus): void
    {
        $schedule->add(MessengerTask::fromMessage(new SendEmailMessage(
            'user@example.com',
            'Bonjour !',
            '<p>Ceci est un email planifié.</p>'
        )))->cron('30 8 * * 1'); // tous les lundis à 8h30
    }
}
