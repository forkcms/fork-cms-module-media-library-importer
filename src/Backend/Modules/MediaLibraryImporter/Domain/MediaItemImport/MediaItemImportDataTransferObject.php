<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;

class MediaItemImportDataTransferObject
{
    /** @var MediaItemImport|null */
    private $mediaItemImport;

    /** @var MediaGroup */
    private $mediaGroup;

    /** @var string */
    public $path;

    /** @var int */
    public $sequence;

    /** @var Method */
    public $method;

    /**
     * @param string $path
     * @param int $sequence
     * @param Method $method
     */
    public function __construct(
        string $path,
        int $sequence,
        Method $method
    ) {
        $this->path = $path;
        $this->sequence = $sequence;
        $this->method = $method;
    }

    /**
     * @return MediaItemImport
     */
    public function getMediaItemImport(): MediaItemImport
    {
        return $this->mediaItemImport;
    }

    /**
     * @param MediaItemImport $mediaItemImport
     */
    public function setMediaItemImport(MediaItemImport $mediaItemImport)
    {
        $this->mediaItemImport = $mediaItemImport;
    }

    /**
     * @return MediaGroup
     */
    public function getMediaGroup(): MediaGroup
    {
        return $this->mediaGroup;
    }

    /**
     * @param MediaGroup $mediaGroup
     */
    public function setMediaGroup(MediaGroup $mediaGroup)
    {
        $this->mediaGroup = $mediaGroup;
    }
}
