<?php


namespace App\Application\Console\Command\Set;

use App\Client\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ZAddCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:zadd';

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
        $this->addArgument('members', InputArgument::IS_ARRAY, 'The members to add to the set.');


        $this
            ->setDescription("Add member to the set.")
            ->setHelp("This command allows you to add members to a set.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument("key");
        $data = $input->getArgument("members");

        $data = $this->formatMembers($data);

        $response = $this->client->send(Connection::POST, "/zadd/{$key}", $data);

        $return = 0;

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);

            $return = $responseData["data"];
        }

        $output->writeln($return);

        return 0;
    }

    /**
     * @param $data
     *
     * @return array[]
     */
    private function formatMembers($data)
    {
        if (count($data) % 2 != 0) {
            throw new \InvalidArgumentException("Wrong number of parameters.");
        }

        $formattedMembers = ["members" => []];

        $i = 0;

        foreach ($data as $key => $param) {
            if ($key == 0 || $key % 2 == 0) {
                if (!is_numeric($param)) {
                    throw new \InvalidArgumentException("Score MUST BE numeric.");
                }

                $formattedMembers["members"][$i]["score"] = $param;
                continue;
            }

            if (empty($param)) {
                throw new \InvalidArgumentException("Data MUST NOT be empty.");
            }

            $formattedMembers["members"][$i]["data"] = $param;
            $i++;
        }

        return $formattedMembers;
    }
}