<?php

namespace Backend\Modules\MediaLibraryImporter\Helper;

use Backend\Modules\Locale\Actions\Import;
use Backend\Modules\MediaLibraryImporter\Component\ImportResults;
use Backend\Modules\MediaLibraryImporter\Component\MediaGroupsToUpdate;
use Backend\Modules\MediaLibraryImporter\Component\MediaGroupToUpdate;
use Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command\UpdateMediaGroupAfterImport;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\Command\ExecuteMediaItemImport;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImportRepository;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

class ImportHelper
{
    /** @var MessageBusSupportingMiddleware */
    private $commandBus;

    /** @var MediaItemImportRepository */
    private $mediaItemImportRepository;

    public function __construct(
        MessageBusSupportingMiddleware $commandBus,
        MediaItemImportRepository $mediaItemImportRepository
    ) {
        $this->commandBus = $commandBus;
        $this->mediaItemImportRepository = $mediaItemImportRepository;
    }

    public function execute(): ImportResults
    {
        $importResults = new ImportResults($this->mediaItemImportRepository->getNumberOfImports());
        $mediaGroupsToUpdate = new MediaGroupsToUpdate();

        $this->executeMediaItemImports($mediaGroupsToUpdate, $importResults);
        $this->executeMediaGroupsToUpdate($mediaGroupsToUpdate);

        return $importResults;
    }

    private function executeMediaItemImport(MediaItemImport $mediaItemImport): ExecuteMediaItemImport
    {
        $executeMediaItemImport = new ExecuteMediaItemImport($mediaItemImport);

        // Handle the MediaItemImport execute
        $this->commandBus->handle($executeMediaItemImport);

        return $executeMediaItemImport;
    }

    private function executeMediaItemImports(
        MediaGroupsToUpdate $mediaGroupsToUpdate,
        ImportResults $importResults
    ): void {
        /** @var array $mediaItemImports */
        $mediaItemImports = $this->mediaItemImportRepository->findAllForImport();

        /** @var MediaItemImport $mediaItemImport */
        foreach ($mediaItemImports as $mediaItemImport) {
            $executeMediaItemImport = $this->executeMediaItemImport($mediaItemImport);
            $importResults->bumpAfterMediaItemImport($executeMediaItemImport->getMediaItemImportEntity());
            $mediaGroupsToUpdate->addMediaItemImportToConnect($executeMediaItemImport->getMediaItemImportEntity());
        }
    }

    private function executeMediaGroupUpdate(MediaGroupToUpdate $mediaGroupToUpdate): void
    {
        if (!$mediaGroupToUpdate->hasChanges()) {
            return;
        }

        $updateMediaGroup = new UpdateMediaGroupAfterImport(
            $mediaGroupToUpdate->getMediaGroup(),
            $mediaGroupToUpdate->getConnectedItems()
        );

        // Handle the UpdateMediaGroupAfterImport
        $this->commandBus->handle($updateMediaGroup);
    }

    private function executeMediaGroupsToUpdate(MediaGroupsToUpdate $mediaGroupsToUpdate): void
    {
        /** @var MediaGroupToUpdate $mediaGroupToUpdate */
        foreach ($mediaGroupsToUpdate->getAll() as $mediaGroupToUpdate) {
            $this->executeMediaGroupUpdate($mediaGroupToUpdate);
        }
    }
}
