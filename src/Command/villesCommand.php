<?php


namespace App\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Ville;


class villesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:villes')
            ->setDescription('Importer les villes de france depuis un CSV')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $em EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();

        // yolo
        ini_set("memory_limit", "-1");

        // On vide les 3 tables
        $connection = $em->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('ville', true /* whether to cascade */));

        $csv = dirname($this->getContainer()->get('kernel')->getRootDir()) . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'villes.csv';
        $lines = explode("\n", file_get_contents($csv));
        $villes = [];

        foreach ($lines as $k => $line) {
            $line = explode(';', $line);
            if (count($line) > 10 && $k > 0) {

                // On sauvegarde la ville
                $ville = new Ville();
                $ville->setName($line[8]);
                $ville->setCodePostal($line[9]);
                $villes[] = $line[8];
                $em->persist($ville);
            }
        }
        $em->flush();
        $output->writeln(count($villes) . ' villes importées');
    }

}