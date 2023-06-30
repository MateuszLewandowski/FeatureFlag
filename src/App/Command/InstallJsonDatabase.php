<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'app:install-json-database',
    description: 'Create feature-flags.json nad test.feature-flags.json files'
)]
final class InstallJsonDatabase extends Command
{
    private const FILENAME = __DIR__ . '/../../feature-flags.json';
    private const TEST_FILENAME = __DIR__ . '/../../test.feature-flags.json';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $formatter = $output->getFormatter();
        $formatter->setStyle('error', new OutputFormatterStyle('red', '', ['blink', 'bold']));
        $formatter->setStyle('success', new OutputFormatterStyle('green', '', ['blink', 'bold']));

        if (file_exists(self::FILENAME)) {
            $output->writeln('<error>feature-flags.json file already exists</>');

            return Command::FAILURE;
        }

        if (file_exists(self::TEST_FILENAME)) {
            $output->writeln('<error>test.feature-flags.json file already exists</>');

            return Command::FAILURE;
        }

        try {
            file_put_contents(self::FILENAME, '');
            file_put_contents(self::TEST_FILENAME, '');
        } catch (Throwable $e) {
            $output->writeln(
                sprintf('<error>an error occurred while trying to create files, reason: %s</>', $e->getMessage())
            );

            return Command::FAILURE;
        }

        $output->writeln('<success>json database has been installed successfully</>');

        return Command::SUCCESS;
    }
}
