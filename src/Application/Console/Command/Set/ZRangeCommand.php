<?php


namespace App\Application\Console\Command\Set;

use App\Client\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ZRangeCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:zrange';

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
        $this->addArgument('start', InputArgument::REQUIRED, 'The start of the range.');
        $this->addArgument('stop', InputArgument::REQUIRED, 'The end of the range.');

        $this
            ->setDescription("Get a range of members by key.")
            ->setHelp("This command allows you to read range of members of a set.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument("key");

        $data = [
            "start" => $input->getArgument("start"),
            "stop"  => $input->getArgument("stop")
        ];

        $response = $this->client->send(Connection::GET, "/zrange/{$key}", $data);

        $return = "";

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);

            $return = $responseData["data"];
        }

        if ($response->getStatusCode() == 401) {
            $return = "Need to authenticate.";
        }

        if (is_array($return)) {
            foreach ($return as $member) {
                $output->writeln($member["data"]);
            }
        } else {
            $output->writeln($return);
        }


        return 0;
    }
}