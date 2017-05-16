<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;

class UpdateMediaGroupAfterImport
{
    /** @var MediaGroup */
    private $mediaGroupEntity;

    /** @var array|MediaItem[] */
    private $mediaItemsToConnect;

    public function __construct(MediaGroup $mediaGroup, array $mediaItemsToConnect)
    {
        $this->mediaGroupEntity = $mediaGroup;
        $this->mediaItemsToConnect = $mediaItemsToConnect;
    }

    public function getMediaGroupEntity(): MediaGroup
    {
        return $this->mediaGroupEntity;
    }

    public function getMediaItemsToConnect(): array
    {
        return $this->mediaItemsToConnect;
    }
}
