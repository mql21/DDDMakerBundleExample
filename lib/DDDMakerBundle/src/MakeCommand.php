<?php

namespace Mql21\DDDMakerBundle;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class MakeCommand extends Command
{
    protected static $defaultName = 'ddd:cqrs:make:command';

    protected function configure()
    {
        $this
            ->addArgument(
                'boundedContext',
                InputArgument::REQUIRED,
                'The name of the bounded context where Command will be saved into.'
            )
            ->addArgument(
                'module',
                InputArgument::REQUIRED,
                'The name of the module inside the bounded context where Command will be saved into.'
            );;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boundedContextName = $input->getArgument('boundedContext');
        $moduleName = $input->getArgument('module');
        $basePath = "src/";
        $boundedContextPath = "{$basePath}{$boundedContextName}/";
        $modulePath = "{$boundedContextPath}{$moduleName}/";

        $boundedContextExists = file_exists($boundedContextPath);
        $moduleExists = file_exists($modulePath);

        if (!$boundedContextExists) {
            //TODO: extract to method
            $elementsInSrcDirectory = scandir($basePath);

            $availableBoundedContexts = array_filter(
                $elementsInSrcDirectory,
                function ($element) use ($basePath) {
                    $elementFullPath = $basePath . $element;

                    return is_dir($elementFullPath) && $element !== "." && $element !== "..";
                }
            );

            $output->writeln(
                [
                    "<error>Bounded context {$boundedContextName} does not exist. Available bounded contexts: </error>"
                ] + $availableBoundedContexts
            );

            return Command::FAILURE;
        }

        if (!$moduleExists) {
            //TODO: extract to method
            $elementsInBoundedContextDirectory = scandir($boundedContextPath);

            $availableModules = array_filter(
                $elementsInBoundedContextDirectory,
                function ($element) use ($boundedContextPath) {
                    $elementFullPath = $boundedContextPath . $element;

                    return is_dir($elementFullPath) && $element !== "." && $element !== "..";
                }
            );

            $output->writeln(
                [
                    "<error>Module {$moduleName} does not exist in bounded context {$boundedContextName}. Available modules: </error>"
                ] + $availableModules
            );

            return Command::FAILURE;
        }

        $commandNameQuestion = new Question("<info>What should the command be called?</info>\n");

        $questionHelper = $this->getHelper('question');
        $commandName = $questionHelper->ask($input, $output, $commandNameQuestion);
        $commandSuffix = "Command";
        $commandFileName = "{$commandName}{$commandSuffix}.php";
        $commandFullPath = "{$modulePath}/Application/{$commandFileName}";

        if (file_exists($commandFullPath)) {
            $output->writeln("<error>Command {$commandFileName} already exists. </error>");

            return Command::FAILURE;
        }

        file_put_contents($commandFullPath, "");
        $output->writeln("<info>Command {$commandFileName} has been successfully created! </info>\n\n");

        $commandHandlerSuffix = "CommandHandler";
        $commandHandlerFileName = "{$commandName}{$commandHandlerSuffix}.php";
        $commandHandlerFullPath = "{$modulePath}/Application/{$commandHandlerFileName}";

        if (file_exists($commandHandlerFullPath)) {
            return Command::SUCCESS;
        }

        $createCommandHandlerQuestion = new ConfirmationQuestion(
            "<info>Do you wish to create command handler now (y/n)? (You can create it later with ddd:cqrs:make:command-handler)</info>\n",
            false,
            '/^(y|s)/i'
        );
        $createCommandHandler = $questionHelper->ask($input, $output, $createCommandHandlerQuestion);

        if ($createCommandHandler) {
            file_put_contents($commandHandlerFullPath, "");
        }

        $output->writeln("<info>Command handler {$commandHandlerFileName} has been successfully created! </info>\n\n");

        return Command::SUCCESS;
    }
}