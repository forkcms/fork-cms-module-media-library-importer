<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command;

use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImportRepository;

class CreateImportForExistingMediaGroupHandler
{
    /** @var MediaItemImportRepository */
    protected $mediaItemImportRepository;

    /**
     * @param MediaItemImportRepository $mediaItemImportRepository
     */
    public function __construct(MediaItemImportRepository $mediaItemImportRepository)
    {
        $this->mediaItemImportRepository = $mediaItemImportRepository;
    }

    /**
     * @param CreateImportForExistingMediaGroup $updateMediaGroupImport
     */
    public function handle(CreateImportForExistingMediaGroup $updateMediaGroupImport)
    {
        foreach ($updateMediaGroupImport->mediaItemImportDataTransferObjects as $dataTransferObject) {
            $this->mediaItemImportRepository->add(MediaItemImport::fromDataTransferObject($dataTransferObject));
        }
    }
}
