<?php


namespace App\Command;

use App\Entity\Ressource;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Statement;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use League\Csv\Reader;




#[AsCommand(
    name: 'app:import-ressource',
    description: 'Importation de ressource',
    hidden: false

)]
class ImportRessource extends Command
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function execute(InputInterface $input,OutputInterface $output): int
    {

        $output->writeln([
            'Importation ressource'
        ]);

        $csv = Reader::createFromPath('documentation/basecarbonescale.csv', 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);


        foreach ($csv as $import ) {

                $ressource = new Ressource();
                $ressource->setIntitule($import['nom']);
                $ressource->setPoid($import['conso en Kg Co2e']);
                $ressource->setUnite($import['type de calcule']);
                $this->manager->persist($ressource);

        }
        $this->manager->flush();


        return Command::SUCCESS;
    }
}