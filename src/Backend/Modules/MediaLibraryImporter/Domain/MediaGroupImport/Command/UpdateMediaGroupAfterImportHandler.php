<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaGroupMediaItem\MediaGroupMediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaGroupMediaItem\MediaGroupMediaItemRepository;

class UpdateMediaGroupAfterImportHandler
{
    /** @var MediaGroupMediaItemRepository */
    protected $mediaGroupMediaItemRepository;

    /**
     * @param MediaGroupMediaItemRepository $mediaGroupMediaItemRepository
     */
    public function __construct(MediaGroupMediaItemRepository $mediaGroupMediaItemRepository)
    {
        $this->mediaGroupMediaItemRepository = $mediaGroupMediaItemRepository;
    }

    /**
     * @param UpdateMediaGroupAfterImport $updateMediaGroupAfterImport
     */
    public function handle(UpdateMediaGroupAfterImport $updateMediaGroupAfterImport)
    {
        /** @var MediaGroup $mediaGroup */
        $mediaGroup = $updateMediaGroupAfterImport->getMediaGroupEntity();

        // Clear all previous connected items
        $mediaGroup->getConnectedItems()->clear();

        /**
         * @var int $sequence
         * @var string $mediaItemId
         */
        foreach ($updateMediaGroupAfterImport->getMediaItemsToConnect() as $sequence => $mediaItem) {
            // Add connected item
            $mediaGroup->addConnectedItem(
                MediaGroupMediaItem::create(
                    $mediaGroup,
                    $mediaItem,
                    $sequence
                )
            );
        }
    }
}
