<?php


namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExportUsersCsvCommand extends Command
{
    private $connexion;

    /**
     * ExportUsersCsvCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->connexion = $entityManager->getConnection();
    }

    protected function configure()
    {
        $this
            ->setName('csv:export:users')
            ->setDescription('Export users csv file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Attempting to export the feed...');

        $sql = "SELECT JSON_EXTRACT(user.roles, '$.s') AS 'role'
                FROM user
                ORDER BY user.created_at DESC
                LIMIT 1
                INTO OUTFILE 'users.csv'
                FIELDS TERMINATED BY ','
                ENCLOSED BY '\"'
                LINES TERMINATED BY '\n';
               ";
        $stmt = $this->connexion->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();


        $io->success('Done...');

        return 1;
    }
}