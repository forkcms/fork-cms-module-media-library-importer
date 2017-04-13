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
    /**
     * Install the module
     */
    public function install()
    {
        $this->addModule('MediaLibraryImporter');
        $this->createEntityTables();
        $this->configureModuleRights();
    }

    /**
     * Configure module rights
     */
    protected function configureModuleRights()
    {
        $this->setModuleRights(1, $this->getModule());
    }

    /**
     * Create entity tables
     */
    private function createEntityTables()
    {
        Model::get('fork.entity.create_schema')->forEntityClasses(
            [
                MediaItemImport::class,
            ]
        );
    }
}
