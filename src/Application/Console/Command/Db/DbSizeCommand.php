<?php


namespace App\Application\Console\Command\Db;


use App\Client\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DbSizeCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:dbsize';

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
        $this
            ->setDescription("Get dbsize.")
            ->setHelp("This command allows you to get the number of sets in the db.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response = $this->client->send(Connection::GET, "/dbsize");

        $return = 0;

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);

            $return = $responseData["data"];
        }

        $output->writeln($return);

        return 0;
    }
}