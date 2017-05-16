<?php

namespace Backend\Modules\MediaLibraryImporter\Installer;

use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;
use Backend\Core\Engine\Model;
use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the MediaLibrary module
 */
class Installer extends ModuleInstaller
{
    public function install(): void
    {
        $this->addModule('MediaLibraryImporter');
        $this->createEntityTables();
        $this->configureModuleRights();
    }

    protected function configureModuleRights(): void
    {
        $this->setModuleRights(1, $this->getModule());
    }

    private function createEntityTables(): void
    {
        Model::get('fork.entity.create_schema')->forEntityClasses(
            [
                MediaItemImport::class,
            ]
        );
    }
}
