<?php


namespace App\Application\Console\Command\Set;


use App\Client\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DeleteCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:del';

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
        $this->addArgument('keys', InputArgument::IS_ARRAY, 'The keys of the set you want to delete.');

        $this
            ->setDescription("Delete set by key.")
            ->setHelp("This command allows you to delete a set.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $keys = $input->getArgument("keys");

        $data = [
            "keys" => implode(",", $keys)
        ];

        $response = $this->client->send(Connection::DELETE, "/delete", $data);

        $return = 0;

        if ($response->getStatusCode() == 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);

            $return = $responseData["data"];
        }

        if ($response->getStatusCode() == 401) {
            $return = "Need to authenticate.";
        }

        $output->writeln($return);

        return 0;
    }
}