<?php

/**
 * Dit is een probeersel om een nieuwe bulk import helper te maken.
 */

// Contains all the vehicles
$vehicles = [/*...*/];

$bulkImport = $this->get('media_library_importer.helper.bulk_import')->createNewBulkImport();

foreach ($vehicles as $vehicle) {

    $bulkImport->addMediaGroup($vehicle->getMediaGroup());


    if ($vehicle->exists()) {

        // Create import for existing media group
        $mediaGroupImportCommand = new CreateImportForExistingMediaGroup($vehicle->getMediaGroup());
    } else {
        // Create import for new media group
        $mediaGroupImportCommand = new CreateImportForNewMediaGroup(Backend\Modules\MediaLibrary\Domain\MediaGroup\Type::image());
    }

    foreach ($vehicle->getImages() as $sequence => $image) {
        $mediaGroupImportCommand->add(
            new MediaItemImportDataTransferObject(
                $image->path,
                $sequence,
                Method::copy(), // Other possible values are; Method::move() or Method::download()
                $image->title // Optionally
            );
        );
    }

    // Handle the MediaGroupImport Update
    $this->get('command_bus')->handle($mediaGroupImportCommand);

}

// Handle the bulk import
$this->get('command_bus')->handle($bulkImport);

// Todo: loop bulk import to setMediaGroup to vehicle
// We can now set the $mediaGroup in our vehicle
$vehicle->setMediaGroup($mediaGroupImportCommand->getMediaGroup());