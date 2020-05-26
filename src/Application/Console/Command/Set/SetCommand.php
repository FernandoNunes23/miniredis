<?php


namespace App\Application\Console\Command\Set;


use App\Client\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetCommand
 *
 * @package App\Application\Console\Command\Set
 */
final class SetCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:set';

    /** @var Connection */
    private $client;

    /**
     * SetCommand constructor.
     * @param Connection $client
     */
    public function __construct(Connection $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    protected function configure()
    {
        $this->addArgument('key', InputArgument::REQUIRED, 'The key of the set.');
        $this->addArgument('member', InputArgument::REQUIRED, 'The data of the set.');
        $this->addArgument('EX', InputArgument::OPTIONAL, 'The expiration time of the set.');

        $this
            ->setDescription("Set key to hold the string value.")
            ->setHelp("This command allows you to create a set.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = [
            "key"    => $input->getArgument("key"),
            "member" => $input->getArgument("member")
        ];

        if ($input->getArgument("EX")) {
            $data["expiration_time"] = $input->getArgument("EX");
        }

        $response = $this->client->send(Connection::POST, "/set", $data);

        $return = "";

        if ($response->getStatusCode() == 200) {
            $return = "OK";
        }

        if ($response->getStatusCode() == 401) {
            $return = "Need to authenticate.";
        }

        $output->writeln($return);

        return 0;
    }
}