<?php

namespace Backend\Modules\MediaLibraryImporter\Helper;

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

    /**
     * @param MessageBusSupportingMiddleware $commandBus
     * @param MediaItemImportRepository $mediaItemImportRepository
     */
    public function __construct(
        MessageBusSupportingMiddleware $commandBus,
        MediaItemImportRepository $mediaItemImportRepository
    ) {
        $this->commandBus = $commandBus;
        $this->mediaItemImportRepository = $mediaItemImportRepository;
    }

    /**
     * @return ImportResults
     */
    public function execute(): ImportResults
    {
        /** @var ImportResults $importResults */
        $importResults = new ImportResults($this->mediaItemImportRepository->getNumberOfImports());

        /** @var MediaGroupsToUpdate $mediaGroupsToUpdate */
        $mediaGroupsToUpdate = new MediaGroupsToUpdate();

        /** @var array $mediaItemImports */
        $mediaItemImports = $this->mediaItemImportRepository->findAllForImport();

        /** @var MediaItemImport $mediaItemImport */
        foreach ($mediaItemImports as $mediaItemImport) {
            $executeMediaItemImport = $this->executeMediaItemImport($mediaItemImport);
            $importResults->bumpForMediaItemImport($executeMediaItemImport->getMediaItemImportEntity());
            $mediaGroupsToUpdate->add($executeMediaItemImport->getMediaItemImportEntity());
        }

        /** @var MediaGroupToUpdate $mediaGroupToUpdate */
        foreach ($mediaGroupsToUpdate->getAll() as $mediaGroupToUpdate) {
            $this->executeMediaGroupUpdate($mediaGroupToUpdate);
        }

        return $importResults;
    }

    /**
     * @param MediaItemImport $mediaItemImport
     * @return ExecuteMediaItemImport
     */
    private function executeMediaItemImport(MediaItemImport $mediaItemImport): ExecuteMediaItemImport
    {
        /** @var ExecuteMediaItemImport $executeMediaItemImport */
        $executeMediaItemImport = new ExecuteMediaItemImport($mediaItemImport);

        // Handle the MediaItemImport execute
        $this->commandBus->handle($executeMediaItemImport);

        return $executeMediaItemImport;
    }

    /**
     * @param MediaGroupToUpdate $mediaGroupToUpdate
     */
    private function executeMediaGroupUpdate(MediaGroupToUpdate $mediaGroupToUpdate)
    {
        if (!$mediaGroupToUpdate->hasChanges()) {
            return;
        }

        /** @var UpdateMediaGroupAfterImport $updateMediaGroup */
        $updateMediaGroup = new UpdateMediaGroupAfterImport(
            $mediaGroupToUpdate->getMediaGroup(),
            $mediaGroupToUpdate->getConnectedItems()
        );

        // Handle the UpdateMediaGroupAfterImport
        $this->commandBus->handle($updateMediaGroup);
    }
}
