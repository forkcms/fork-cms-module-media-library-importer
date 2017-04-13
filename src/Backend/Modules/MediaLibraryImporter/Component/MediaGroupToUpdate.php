<?php

namespace Backend\Modules\MediaLibraryImporter\Component;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaGroupMediaItem\MediaGroupMediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;

class MediaGroupToUpdate
{
    /** @var MediaGroup */
    protected $mediaGroup;

    /** @var array */
    protected $connectedItems = [];

    /** @var bool */
    protected $hasChanges = false;

    /**
     * @param MediaGroup $mediaGroup
     */
    public function __construct(MediaGroup $mediaGroup)
    {
        $this->mediaGroup = $mediaGroup;
    }

    /**
     * @return MediaGroup
     */
    public function getMediaGroup(): MediaGroup
    {
        return $this->mediaGroup;
    }

    /**
     * @param int $sequence
     * @param MediaItem|null $mediaItem
     */
    public function addConnectedItem(int $sequence, MediaItem $mediaItem = null)
    {
        if ($mediaItem === null) {
            return;
        }

        $this->connectedItems[$sequence] = $mediaItem;
    }

    /**
     * @return array
     */
    public function getConnectedItems(): array
    {
        $connectedItems = $this->connectedItems;

        // We sort the keys, so the sequence is correct
        ksort($connectedItems);

        // We return a new array, so we have a sequence with no gaps in between
        return array_values($connectedItems);
    }

    /**
     * @param MediaItemImport $mediaItemImport
     */
    public function checkForChanges(MediaItemImport $mediaItemImport)
    {
        if ($mediaItemImport->getMediaItem() === null) {
            return;
        }

        // When imported, we do have changes
        if ($mediaItemImport->getStatus()->isImported()) {
            $this->hasChanges = true;

            return;
        }

        // When status == "existing", but sequence of MediaGroupMediaItem has changed
        if ($mediaItemImport->getStatus()->isExisting()) {
            $arrayWithChanges = $this->mediaGroup->getConnectedItems()->filter(function (MediaGroupMediaItem $connectedItem) use ($mediaItemImport) {
                if ($connectedItem->getItem()->getId() !== $mediaItemImport->getMediaItem()->getId()) {
                    return false;
                }

                if ($connectedItem->getSequence() !== $mediaItemImport->getSequence()) {
                    return true;
                }

                return false;
            });

            if (!empty($arrayWithChanges)) {
                $this->hasChanges = true;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasChanges(): bool
    {
        return $this->hasChanges;
    }
}
