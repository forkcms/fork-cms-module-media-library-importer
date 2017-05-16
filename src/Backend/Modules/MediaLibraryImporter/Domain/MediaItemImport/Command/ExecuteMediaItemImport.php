<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\Command;

use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;

class ExecuteMediaItemImport
{
    /** @var MediaItemImport */
    private $mediaItemImportEntity;

    public function __construct(MediaItemImport $mediaItemImport)
    {
        $this->mediaItemImportEntity = $mediaItemImport;
    }

    public function getMediaItemImportEntity(): MediaItemImport
    {
        return $this->mediaItemImportEntity;
    }
}
