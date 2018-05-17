<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class InitializeSTCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:initialize-SN')

            // the short description shown while running "php bin/console list"
            ->setDescription('Initializes application with Tricks data.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to set several tricks and the category table...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            "Initialization of SnowTricks application  ..."
        ]);

        $app = $this->getApplication();

        # Supprimer la base de données existante
        $createdbCmd = $app->find('doctrine:database:drop');
        $createdbInput = new ArrayInput(['command' => 'doctrine:database:drop', '--force' => true, '--if-exists' => true]);
        $createdbCmd->run($createdbInput, $output);


        # Créer la base de données
        $createdbCmd = $app->find('doctrine:database:create');
        $createdbInput = new ArrayInput(['command' => 'doctrine:database:create']);
        $createdbCmd->run($createdbInput, $output);
        $output->writeln([
            "SnowTricks database has been created."
        ]);


        # Créer les tables
        $createtablesCmd = $app->find('doctrine:schema:update');
        $createtablesInput = new ArrayInput(['command' => 'doctrine:schema:update', '--force' => true]);
        $createtablesCmd->run($createtablesInput, $output);
        $output->writeln([
            "Database tables have been created."
        ]);


        # Charger les données Trick
        $loaddataCmd = $app->find('app:import-fixtures-data');
        $loaddataInput = new ArrayInput(['command' => 'app:import-fixtures-data']);
        $loaddataCmd->run($loaddataInput, $output);
        $output->writeln([
            "Trick Datas have been charged in the application."
        ]);

        # Charger les données User
        $loaduserCmd = $app->find('app:import-fixtures-user');
        $loaduserInput = new ArrayInput(['command' => 'app:import-fixtures-user']);
        $loaduserCmd->run($loaduserInput, $output);
        $output->writeln([
            "User Datas have been charged in the application."
        ]);



        $output->writeln([
            "SnowTricks has been initialized ! Enjoy !"
        ]);
    }
}
