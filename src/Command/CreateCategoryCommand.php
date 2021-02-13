<?php

namespace App\Command;

use App\Service\CategoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateCategoryCommand extends Command
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            //the name of the command (the port after "bin/console")
            ->setName('app:create-category')

            //the short description shown while running "php bin/console list"
            ->setDescription('Creates a new category')

            //the full command description shown when running the command with the --help option
            ->setHelp('This command allows you to add new category in db ...')

            // configure an argument
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the category.');
    }

    /** 
     * This method is executed after initialize() and before execute(). Its purpose is to check if some of the options/arguments are missing and interactively ask the user for those values. This is the last place where you can ask for missing options/arguments. After this command, missing options/arguments will result in an error.
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if(!$input->getArgument('name')){
            /*
            Symfony has some predefined tags: <info>, <comment>, <question> and <error>. Wrap our question in proper style:
            */
            $question = new Question('<question>Please choose a name:</question>');
            $question->setValidator(function ($name) {
                if(empty($name)){
                    throw new \Exception('Name can not be empty');
                }

                return $name;
            });

            $answer = $this->getHelper('question')->ask($input,$output,$question);
            $input->setArgument('name',$answer);
        }
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    /**
     * This method is executed after interact() and initialize(). It contains the logic you want the command to execute.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln(['Category Creator','=============','']);

        $output->writeln(sprintf('Name: %s',$input->getArgument('name')));

        $this->categoryService->create($input->getArgument('name'));

        $output->writeln('<fg=red;bg=yellow;options=bold>category successfully created</>');
    }
}