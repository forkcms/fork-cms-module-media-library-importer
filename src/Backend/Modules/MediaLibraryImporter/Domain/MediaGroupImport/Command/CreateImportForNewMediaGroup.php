<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\Type;
use Backend\Modules\MediaLibraryImporter\Domain\MediaGroupImport\Command\Base\CreateImportForMediaGroupDataTransferObject;

class CreateImportForNewMediaGroup extends CreateImportForMediaGroupDataTransferObject
{
    /**
     * @param Type $type
     */
    public function __construct(Type $type)
    {
        parent::__construct();
        $this->type = $type;
    }
}
