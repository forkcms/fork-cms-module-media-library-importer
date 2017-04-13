<?php

namespace Backend\Modules\MediaLibraryImporter\Component;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;

class MediaGroupsToUpdate
{
    /** @var array */
    protected $mediaGroups = [];

    /**
     * @param MediaItemImport $mediaItemImport
     */
    public function add(MediaItemImport $mediaItemImport)
    {
        /** @var MediaGroup $mediaGroup */
        $mediaGroup = $mediaItemImport->getMediaGroup();
        $mediaGroupId = (string) $mediaItemImport->getMediaGroup()->getId();

        if (!isset($this->mediaGroups[$mediaGroupId])) {
            $this->mediaGroups[$mediaGroupId] = new MediaGroupToUpdate($mediaGroup);
        }

        // Add connected item
        $this->mediaGroups[$mediaGroupId]->addConnectedItem(
            $mediaItemImport->getSequence(),
            $mediaItemImport->getMediaItem()
        );

        // Check for changes
        $this->mediaGroups[$mediaGroupId]->checkForChanges($mediaItemImport);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->mediaGroups;
    }
}
