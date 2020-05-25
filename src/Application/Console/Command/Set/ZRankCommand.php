<?php


namespace App\Application\Console\Command\Set;

use App\Client\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ZRankCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:zrank';

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
        $this->addArgument('member', InputArgument::REQUIRED, 'The member of the set.');

        $this
            ->setDescription("Get member rank by key.")
            ->setHelp("This command allows you to read member rank of a set.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key    = $input->getArgument("key");
        $member = $input->getArgument("member");

        $response = $this->client->send(Connection::GET, "/zrank/{$key}/{$member}");

        $return = 0;

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);

            $return = $responseData["data"];
        }

        $output->writeln($return);

        return 0;
    }
}