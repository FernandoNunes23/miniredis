<?php


namespace App\Application\Console\Command\Authentication;


use App\Client\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AuthCommand
 * @package App\Application\Console\Command\Authentication
 */
final class AuthCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:auth';

    protected function configure()
    {
        $this->addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
        $this->addArgument('password', InputArgument::REQUIRED, 'The password of the user.');

        $this
            ->setDescription("Set key to hold the string value.")
            ->setHelp("This command allows you to create a set.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Connection($input->getArgument('username'), $input->getArgument('password'));

        if ($client->accessToken) {
            $response = "OK";
        } else {
            $response = "ERROR";
        }

        $output->writeln($response);

        return 0;
    }
}