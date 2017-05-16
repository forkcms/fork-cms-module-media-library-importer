<?php

namespace Backend\Modules\MediaLibraryImporter\Console;

use Backend\Modules\MediaLibraryImporter\Component\ImportResults;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Import media library items
 * Example: "app/console media_library:import"
 */
class ImportCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('media_library:import')
            ->setDescription('Import all mediaItemImport items which can be imported.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('<info>Starting the MediaLibrary import.</info>');
        $output->writeln($this->getOutputMessageForImportResults(
            $this->getContainer()->get('media_library.helper.import')->execute()
        ));
    }

    private function getOutputMessageForImportResults(ImportResults $importResults): string
    {
        $message = '<info>[OK] Import of "' . $importResults->getNumberOfQueuedItems() . '" MediaItems finished.';
        $message .= "\n\nMediaItemImport changes:\n";
        $message .= '- Successful: ' . $importResults->getNumberOfSuccessfulImports();
        $message .= "\n";
        $message .= '- Errors: ' . $importResults->getNumberOfErrorImports();
        $message .= "\nDatabase changes:\n";
        $message .= '- Imported: ' . $importResults->getNumberOfImportedItems();
        $message .= '</info>';

        return $message;
    }
}
