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
use App\Repository\UserRepository;
use App\Repository\VideoGameRepository;
use App\Service\EmailService;


#[AsCommand(
    name: 'app:send-newsletter',
    description: 'Envoie un email à un utilisateur.',
)]
class SendNewsletterCommand extends Command
{
    public function __construct(        
        private UserRepository $userRepository,
        private VideoGameRepository $videogameRepository,
        private EmailService $emailService
    ){
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
        $games = $this->gameRepository->findUpcomingGames();
        $users = $this->userRepository->findBy(['subscribedToNewsletter' => true]);

        if (empty($games)) {
            $output->writeln('<comment>Aucun jeu à venir cette semaine.</comment>');
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            $this->emailService->sendNewsletter($user->getEmail(), $games);
            $output->writeln(' Email envoyé à : ' . $user->getEmail());
        }

        $output->writeln('<info>Newsletter envoyée avec succès à tous les abonnés.</info>');
        return Command::SUCCESS;
    }
}


