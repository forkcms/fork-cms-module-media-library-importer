<?php

namespace Backend\Modules\MediaLibraryImporter\Component;

use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\Status;

class ImportResults
{
    /** @var int */
    protected $numberOfErrorImports = 0;

    /** @var int */
    protected $numberOfImportedItems = 0;

    /** @var int */
    protected $numberOfQueuedItems = 0;

    /** @var int */
    protected $numberOfSuccessfulImports = 0;

    /**
     * @param int $numberOfQueuedItems
     */
    public function __construct(int $numberOfQueuedItems)
    {
        $this->numberOfQueuedItems = $numberOfQueuedItems;
    }

    /**
     * @param MediaItemImport $mediaItemImport
     */
    public function bumpForMediaItemImport(MediaItemImport $mediaItemImport)
    {
        switch ($mediaItemImport->getStatus()->getStatus()) {
            case Status::IMPORTED:
                $this->bumpNumberOfImportedItems();
                $this->bumpNumberOfSuccessfulImports();
                break;
            case Status::EXISTING:
                $this->bumpNumberOfSuccessfulImports();
                break;
            case Status::ERROR:
                $this->bumpNumberOfErrorImports();
                break;
            default:
                // do nothing
                break;
        }
    }

    public function bumpNumberOfErrorImports()
    {
        $this->numberOfErrorImports ++;
    }

    public function bumpNumberOfImportedItems()
    {
        $this->numberOfImportedItems ++;
    }

    public function bumpNumberOfSuccessfulImports()
    {
        $this->numberOfSuccessfulImports ++;
    }

    /**
     * @return int
     */
    public function getNumberOfErrorImports(): int
    {
        return $this->numberOfErrorImports;
    }

    /**
     * @return int
     */
    public function getNumberOfImportedItems(): int
    {
        return $this->numberOfImportedItems;
    }

    /**
     * @return int
     */
    public function getNumberOfQueuedItems(): int
    {
        return $this->numberOfQueuedItems;
    }

    /**
     * @return int
     */
    public function getNumberOfSuccessfulImports(): int
    {
        return $this->numberOfSuccessfulImports;
    }
}
