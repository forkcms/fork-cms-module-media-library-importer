<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\Command;

use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;

class ExecuteMediaItemImport
{
    /** @var MediaItemImport */
    private $mediaItemImportEntity;

    /**
     * @param MediaItemImport $mediaItemImport
     */
    public function __construct(MediaItemImport $mediaItemImport)
    {
        $this->mediaItemImportEntity = $mediaItemImport;
    }

    /**
     * @return MediaItemImport
     */
    public function getMediaItemImportEntity(): MediaItemImport
    {
        return $this->mediaItemImportEntity;
    }
}
