<?php

namespace Backend\Modules\MediaLibraryImporter\Component;

use PHPUnit\Framework\TestCase;

class ImportResultsTest extends TestCase
{
    public function testNumbers()
    {
        $importResults = new ImportResults(100);
        self::assertEquals(100, $importResults->getNumberOfQueuedItems());
        $importResults->bumpNumberOfErrorImports();
        self::assertEquals(1, $importResults->getNumberOfErrorImports());
        $importResults->bumpNumberOfSuccessfulImports();
        self::assertEquals(1, $importResults->getNumberOfSuccessfulImports());
        $importResults->bumpNumberOfErrorImports();
        $importResults->bumpNumberOfErrorImports();
        $importResults->bumpNumberOfSuccessfulImports();
        $importResults->bumpNumberOfSuccessfulImports();
        $importResults->bumpNumberOfImportedItems();
        self::assertEquals(1, $importResults->getNumberOfImportedItems());
        self::assertEquals(3, $importResults->getNumberOfErrorImports());
        self::assertEquals(3, $importResults->getNumberOfSuccessfulImports());
    }
}
