<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class CreateUserAdminCommand extends Command
{
    protected static $defaultName = 'app:create-user-admin';
    protected static $defaultDescription = 'Create a user admin';

    /**@var EntityManagerInterface */
    private $em;

    /**
     * UserAdminCreateCommand constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
        $this->em = $em;
        $this->hasher = $hasher;
    }
    protected function configure(): void
    {
        $this->addArgument('firstname', InputArgument::REQUIRED, 'The firstname of the user.');
        $this->addArgument('lastname', InputArgument::REQUIRED, 'The lastname of the user.');
        $this->addArgument('email', InputArgument::REQUIRED, 'The email of the user.');
        $this->addArgument('password', InputArgument::REQUIRED, 'The password of the user.');
        $this->setName('app:create-user-admin')
            ->setDescription('Create a user Admin');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $superAdmin = new User();
        $firstname = 'Martin';
        $lastname = 'Dhenu';
        $superAdmin->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail('martindhenu@yahoo.fr')
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setUuid(Uuid::v4())
            ->setIsActiv(1)
            ->setIsChoiceAllowed(0);
        $passwordHashed = $this->hasher->hashPassword($superAdmin, '<?NitrAM81>');
        $superAdmin->setPassword($passwordHashed);

        $this->em->persist($superAdmin);

        $this->em->flush();

        $io->success($firstname . ' ' . $lastname . ' has been successfully created');

        return Command::SUCCESS;
    }
}
