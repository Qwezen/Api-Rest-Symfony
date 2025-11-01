<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Scheduler\Attribute\AsCronTask;

#[AsCommand(
    name: 'app:send-newsletter',
    description: 'Envoie un email à un utilisateur.',
)]
#[AsCronTask('30 8 * * 1')]
class SendNewsletterCommand extends Command
{
    public function __construct()
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
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}


//////////////// Chat Gpt Exemple :



// namespace App\Command;

// use App\Repository\UserRepository;
// use App\Repository\VideoGameRepository;
// use Symfony\Component\Console\Attribute\AsCommand;
// use Symfony\Component\Console\Command\Command;
// use Symfony\Component\Console\Input\InputInterface;
// use Symfony\Component\Console\Output\OutputInterface;
// use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Email;
// use Symfony\Bridge\Twig\Mime\TemplatedEmail;
// use Twig\Environment;

//use Symfony\Component\Scheduler\Attribute\AsCronTask;

// #[AsCommand(
//     name: 'app:send-newsletter',
//     description: 'Envoie un email à tous les utilisateurs abonnés à la newsletter avec les jeux à venir.',
// )]
// class SendNewsletterCommand extends Command
// {
//     public function __construct(
//         private UserRepository $userRepository,
//         private VideoGameRepository $videoGameRepository,
//         private MailerInterface $mailer,
//         private Environment $twig
//     ) {
//         parent::__construct();
//     }

//     protected function execute(InputInterface $input, OutputInterface $output): int
//     {
//         $output->writeln('🚀 Début de l’envoi de la newsletter...');

//         // 🗓️ Récupération des jeux qui sortent dans les 7 prochains jours
//         $today = new \DateTime();
//         $nextWeek = (clone $today)->modify('+7 days');
//         $videoGames = $this->videoGameRepository->createQueryBuilder('v')
//             ->where('v.releaseDate BETWEEN :today AND :nextWeek')
//             ->setParameters([
//                 'today' => $today,
//                 'nextWeek' => $nextWeek
//             ])
//             ->orderBy('v.releaseDate', 'ASC')
//             ->getQuery()
//             ->getResult();

//         if (empty($videoGames)) {
//             $output->writeln('❌ Aucun jeu à venir dans les 7 prochains jours.');
//             return Command::SUCCESS;
//         }

//         // 👥 Récupération des abonnés
//         $subscribers = $this->userRepository->findBy(['subscription_to_newsletter' => true]);

//         if (empty($subscribers)) {
//             $output->writeln('⚠️ Aucun utilisateur abonné à la newsletter.');
//             return Command::SUCCESS;
//         }

//         $output->writeln(sprintf("🧑‍💻 %d abonnés recevront la newsletter.", count($subscribers)));

//         // 📧 Envoi d’un email à chaque abonné
//         foreach ($subscribers as $user) {
//             $email = (new TemplatedEmail())
//                 ->from('newsletter@votresite.com')
//                 ->to($user->getEmail())
//                 ->subject('🎮 Les sorties jeux de la semaine !')
//                 ->htmlTemplate('emails/newsletter.html.twig')
//                 ->context([
//                     'user' => $user,
//                     'videoGames' => $videoGames,
//                 ]);

//             $this->mailer->send($email);
//             $output->writeln("✅ Newsletter envoyée à : " . $user->getEmail());
//         }

//         $output->writeln('✅ Tous les emails ont été envoyés avec succès.');
//         return Command::SUCCESS;
//     }
// }

