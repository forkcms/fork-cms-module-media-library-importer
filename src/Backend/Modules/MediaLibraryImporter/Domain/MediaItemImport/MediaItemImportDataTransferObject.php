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

    /** @var string|null */
    public $title;

    /**
     * @param string $path
     * @param int $sequence
     * @param Method $method
     * @param string|null $title
     */
    public function __construct(
        string $path,
        int $sequence,
        Method $method,
        string $title = null
    ) {
        $this->path = $path;
        $this->sequence = $sequence;
        $this->method = $method;
        $this->title = $title;
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
