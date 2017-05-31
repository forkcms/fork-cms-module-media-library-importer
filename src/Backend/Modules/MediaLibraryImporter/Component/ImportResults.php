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

    public function __construct(int $numberOfQueuedItems)
    {
        $this->numberOfQueuedItems = $numberOfQueuedItems;
    }

    public function bumpAfterMediaItemImport(MediaItemImport $mediaItemImport): void
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

    public function bumpNumberOfErrorImports(): void
    {
        $this->numberOfErrorImports ++;
    }

    public function bumpNumberOfImportedItems(): void
    {
        $this->numberOfImportedItems ++;
    }

    public function bumpNumberOfSuccessfulImports(): void
    {
        $this->numberOfSuccessfulImports ++;
    }

    public function getNumberOfErrorImports(): int
    {
        return $this->numberOfErrorImports;
    }

    public function getNumberOfImportedItems(): int
    {
        return $this->numberOfImportedItems;
    }

    public function getNumberOfQueuedItems(): int
    {
        return $this->numberOfQueuedItems;
    }

    public function getNumberOfSuccessfulImports(): int
    {
        return $this->numberOfSuccessfulImports;
    }
}
