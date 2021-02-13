<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCategoryCommand extends Command
{
    protected function configure()
    {
        $this
            //the name of the command (the port after "bin/console")
            ->setName('app:create-category')

            //the short description shown while running "php bin/console list"
            ->setDescription('Creates a new category')

            //the full command description shown when running the command with the --help option
            ->setHelp('This command allows you to add new category in db ...');
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln(['Category Creator','=============','']);

        //outputs a message followed by a "\n"
        $output->writeln('Whoa!');

        //outputs a message without adding a "\n" at the end of the line
        $output->write('you are about to');
        $output->write('create a category');
    }
}