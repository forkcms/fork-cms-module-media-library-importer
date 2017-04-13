<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command\Base\CreateImportForMediaGroupDataTransferObject;

class CreateImportForExistingMediaGroup extends CreateImportForMediaGroupDataTransferObject
{
    /**
     * @param MediaGroup $mediaGroup
     */
    public function __construct(MediaGroup $mediaGroup)
    {
        parent::__construct($mediaGroup);
    }
}
