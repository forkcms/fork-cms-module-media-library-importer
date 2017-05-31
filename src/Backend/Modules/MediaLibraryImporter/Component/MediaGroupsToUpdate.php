<?php

namespace Backend\Modules\MediaLibraryImporter\Component;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;

final class MediaGroupsToUpdate
{
    /** @var MediaGroupToUpdate[] */
    private $mediaGroups = [];

    public function addMediaItemImportToConnect(MediaItemImport $mediaItemImport)
    {
        /** @var MediaGroupToUpdate $mediaGroupToUpdate */
        $mediaGroupToUpdate = $this->get($mediaItemImport->getMediaGroup());
        $mediaGroupToUpdate->addMediaItemImport($mediaItemImport);
    }

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

    public function getAll(): array
    {
        return $this->mediaGroups;
    }
}
