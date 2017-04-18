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

    /**
     * @param MediaGroup|null $mediaGroup
     */
    public function __construct(MediaGroup $mediaGroup = null)
    {
        $this->mediaGroupEntity = $mediaGroup;

        if (!$this->hasExistingMediaGroup()) {
            return;
        }

        $this->type = $mediaGroup->getType();
    }

    /**
     * @param MediaItemImportDataTransferObject $mediaItemImportDataTransferObject
     */
    public function add(MediaItemImportDataTransferObject $mediaItemImportDataTransferObject)
    {
        if ($this->hasExistingMediaGroup()) {
            $mediaItemImportDataTransferObject->setMediaGroup($this->mediaGroupEntity);
        }

        $this->mediaItemImportDataTransferObjects[] = $mediaItemImportDataTransferObject;
    }

    /**
     * @return MediaGroup
     */
    public function getMediaGroupEntity(): MediaGroup
    {
        return $this->mediaGroupEntity;
    }

    /**
     * @return bool
     */
    public function hasExistingMediaGroup(): bool
    {
        return $this->mediaGroupEntity instanceof MediaGroup;
    }

    /**
     * @param MediaGroup $mediaGroup
     */
    public function setMediaGroupEntity(MediaGroup $mediaGroup)
    {
        $this->mediaGroupEntity = $mediaGroup;
    }
}
