<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


use App\Message\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:send-newsletter',
    description: 'Envoie un email à un utilisateur.',
)]
class SendNewsletterCommand extends Command
{
    public function __construct(private MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new SendEmailMessage(
            'user@example.com',
            'Email envoyé via commande',
            '<p>Test d’envoi manuel avec la commande Symfony.</p>'
        ));

        $output->writeln('Email envoyé avec succès.');
        return Command::SUCCESS;
    }
}


