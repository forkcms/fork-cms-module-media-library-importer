<?php

namespace Backend\Modules\MediaLibraryImporter\Component;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaGroupMediaItem\MediaGroupMediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;
use Doctrine\Common\Collections\Collection;

final class MediaGroupToUpdate
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
     * @param MediaItemImport $mediaItemImport
     */
    public function addMediaItemImport(MediaItemImport $mediaItemImport)
    {
        $this->addConnectedItem($mediaItemImport->getSequence(), $mediaItemImport->getMediaItem());
        $this->checkForChanges($mediaItemImport);
    }

    /**
     * @param int $sequence
     * @param MediaItem $mediaItem
     */
    private function addConnectedItem(int $sequence, MediaItem $mediaItem)
    {
        $this->connectedItems[$sequence] = $mediaItem;
    }

    /**
     * @param MediaItemImport $mediaItemImport
     */
    private function checkForChanges(MediaItemImport $mediaItemImport)
    {
        if ($mediaItemImport->getMediaItem() === null) {
            return;
        }

        // When imported, we do have changes
        if ($mediaItemImport->getStatus()->isImported()) {
            $this->hasChanges = true;

            return;
        }

        // When status == "existing", we must check other other variables
        if ($mediaItemImport->getStatus()->isExisting()
            && $this->hasChangedConnectedItems($this->mediaGroup->getConnectedItems(), $mediaItemImport)
        ) {
            $this->hasChanges = true;
        }
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
     * @return MediaGroup
     */
    public function getMediaGroup(): MediaGroup
    {
        return $this->mediaGroup;
    }

    /**
     * @return bool
     */
    public function hasChanges(): bool
    {
        return $this->hasChanges;
    }

    /**
     * Has changed connected items check for possible changes
     *
     * @param Collection $connectedItems
     * @param MediaItemImport $mediaItemImport
     * @return bool
     */
    private function hasChangedConnectedItems(
        Collection $connectedItems,
        MediaItemImport $mediaItemImport
    ): bool {
        $arrayWithChanges = $connectedItems->filter(function (MediaGroupMediaItem $connectedItem) use ($mediaItemImport) {
            // Stop here, because ID is not equal
            if ($connectedItem->getItem()->getId() !== $mediaItemImport->getMediaItem()->getId()) {
                return false;
            }

            // Stop here, because sequence equals
            if ($connectedItem->getSequence() === $mediaItemImport->getSequence()) {
                return false;
            }

            return true;
        });

        return !empty($arrayWithChanges);
    }
}
