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

    public function getMediaItemImport(): MediaItemImport
    {
        return $this->mediaItemImport;
    }

    public function setMediaItemImport(MediaItemImport $mediaItemImport): void
    {
        $this->mediaItemImport = $mediaItemImport;
    }

    public function getMediaGroup(): MediaGroup
    {
        return $this->mediaGroup;
    }

    public function setMediaGroup(MediaGroup $mediaGroup): void
    {
        $this->mediaGroup = $mediaGroup;
    }
}
