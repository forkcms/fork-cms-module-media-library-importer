<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command\Base;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaGroup\Type;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImportDataTransferObject;

class CreateImportForMediaGroupDataTransferObject
{
    /** @var MediaGroup|null */
    private $mediaGroupEntity;

    /** @var array|MediaItemImportDataTransferObject[] */
    public $mediaItemImportDataTransferObjects = [];

    /** @var Type */
    public $type;

    public function __construct(MediaGroup $mediaGroup = null)
    {
        $this->mediaGroupEntity = $mediaGroup;

        if (!$this->hasExistingMediaGroup()) {
            return;
        }

        $this->type = $mediaGroup->getType();
    }

    public function add(MediaItemImportDataTransferObject $mediaItemImportDataTransferObject): void
    {
        if ($this->hasExistingMediaGroup()) {
            $mediaItemImportDataTransferObject->setMediaGroup($this->mediaGroupEntity);
        }

        $this->mediaItemImportDataTransferObjects[] = $mediaItemImportDataTransferObject;
    }

    public function getMediaGroupEntity(): MediaGroup
    {
        return $this->mediaGroupEntity;
    }

    public function hasExistingMediaGroup(): bool
    {
        return $this->mediaGroupEntity instanceof MediaGroup;
    }

    public function setMediaGroupEntity(MediaGroup $mediaGroup): void
    {
        $this->mediaGroupEntity = $mediaGroup;
    }
}
