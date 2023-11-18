<?php


namespace App\Command;

use App\Entity\Categorie;
use App\Entity\Ressource;
use App\Repository\CategorieRepository;
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
    private CategorieRepository $categorieRepository;

    public function __construct(EntityManagerInterface $manager, CategorieRepository $categorieRepository)
    {
        $this->manager = $manager;
        $this->categorieRepository = $categorieRepository;
        parent::__construct();
    }

    protected function execute(InputInterface $input,OutputInterface $output): int
    {


        $csv = Reader::createFromPath('documentation/basecarbonescale.csv', 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);




        $output->writeln([
            'Importation ressource'
        ]);



        foreach ($csv as $import ) {

                $ressource = new Ressource();
                $ressource->setIntitule($import['nom']);
                $poid = floatval($import['conso en Kg CO2e']);
                $ressource->setPoid($poid);
                $ressource->setUnite($import['type de calcule']);

                $ressource->setCategorie($this->categorieRepository->findOneBy(['intitule' => $import['Type']]));
                $this->manager->persist($ressource);

        }
        $this->manager->flush();


        return Command::SUCCESS;
    }
}