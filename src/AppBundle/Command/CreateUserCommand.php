<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Entity\User;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
			->setName('app:create:user')
			->setDescription('Create app user.')
			->setHelp("This command allows you to create app user")
			->addArgument('username', InputArgument::REQUIRED, 'The username\'s user.')
			->addArgument('email', InputArgument::REQUIRED, 'The email\'s user.')
			->addArgument('password', InputArgument::REQUIRED, 'The password\'s user.')
		;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        $user = new User();
        $user->setUsername($username)
            ->setEmail($email)
            ->setPlainPassword($password);
        $password = $this->getContainer()
            ->get('security.password_encoder')
            ->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password)
            ->eraseCredentials();

        $em->persist($user);
        $em->flush();
        $em->clear();

        $output->writeln('<info>User '.$email.' created with success.');
    }
}