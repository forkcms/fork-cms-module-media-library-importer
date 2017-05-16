<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroupRepository;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImportDataTransferObject;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImportRepository;

class CreateImportForNewMediaGroupHandler
{
    /** @var MediaGroupRepository */
    protected $mediaGroupRepository;

    /** @var MediaGroupRepository */
    protected $mediaItemImportRepository;

    public function __construct(
        MediaGroupRepository $mediaGroupRepository,
        MediaItemImportRepository $mediaItemImportRepository
    ) {
        $this->mediaGroupRepository = $mediaGroupRepository;
        $this->mediaItemImportRepository = $mediaItemImportRepository;
    }

    public function handle(CreateImportForNewMediaGroup $createMediaGroupImport): void
    {
        /** @var MediaGroup $mediaGroup */
        $mediaGroup = MediaGroup::create($createMediaGroupImport->type);
        $this->mediaGroupRepository->add($mediaGroup);

        $this->importMediaItems($mediaGroup, $createMediaGroupImport->mediaItemImportDataTransferObjects);

        // We redefine the MediaGroup, so we can use it in an action
        $createMediaGroupImport->setMediaGroupEntity($mediaGroup);
    }

    /**
     * @param MediaGroup $mediaGroup
     * @param MediaItemImportDataTransferObject[] $dataTransferObjects
     */
    private function importMediaItems(MediaGroup $mediaGroup, array $dataTransferObjects): void
    {
        foreach ($dataTransferObjects as $dataTransferObject) {
            // We must set the media group
            $dataTransferObject->setMediaGroup($mediaGroup);

            // Import mediaItemImport
            $this->mediaItemImportRepository->add(MediaItemImport::fromDataTransferObject($dataTransferObject));
        }
    }
}
