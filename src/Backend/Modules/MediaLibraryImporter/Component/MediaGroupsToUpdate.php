<?php

namespace Backend\Modules\MediaLibraryImporter\Component;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;

final class MediaGroupsToUpdate
{
    /** @var MediaGroupToUpdate[] */
    private $mediaGroups = [];

    /**
     * @param MediaItemImport $mediaItemImport
     */
    public function add(MediaItemImport $mediaItemImport)
    {
        /** @var MediaGroupToUpdate $mediaGroupToUpdate */
        $mediaGroupToUpdate = $this->get($mediaItemImport->getMediaGroup());

        // Stop here, because no MediaItem found
        if ($mediaItemImport->getMediaItem() === null) {
            return;
        }

        $mediaGroupToUpdate->addMediaItemImport($mediaItemImport);
    }

    /**
     * @param MediaGroup $mediaGroup
     * @return MediaGroupToUpdate
     */
    private function get(MediaGroup $mediaGroup): MediaGroupToUpdate
    {
        /** @var string $mediaGroupId */
        $mediaGroupId = (string) $mediaGroup->getId();

        // If not yet set, set MediaGroupToUpdate
        if (!isset($this->mediaGroups[$mediaGroupId])) {
            $this->mediaGroups[$mediaGroupId] = new MediaGroupToUpdate($mediaGroup);
        }

        return $this->mediaGroups[$mediaGroupId];
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->mediaGroups;
    }
}
