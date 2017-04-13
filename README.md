# fork-cms-module-media-library-importer
[WIP] This MediaLibraryImporter Module is created to help you import mass MediaItem items.

> Sometimes, you have to import images and files from external sources like f.e.; API's, .xml files, .csv files, ... With this module you can easily write some code and everything will be automatically updated.

## WARNING

Use at your own risk, this module isn't finished yet and still in development.

## Usage

## Installation

* Copy/paste the `src/Backend/Modules/MediaLibraryImporter` folder to your Fork CMS 5.
* Install the "MediaLibraryImporter" module in the backend (Note: "MediaLibrary" should be installed also).

### Example

**Example situation:**

Imagine we are making a connection with a vehicles API, which returns all current vehicles data + URL's to their images.
But now you want to create a "lightbox" or "slider" in your website using all those images and creating your own thumbnails... This can get kinda difficult right here.

**What can the "MediaLibraryImporter" module do for me?**

Every vehicle can have unlimited images and this can change every minute of the day.
We also want to load the images/files into our website, so we have full control over them.
We can now use this amazing module to automatically import the images to a $mediaGroup. It can also automatically update things in the next load.

**Example code**

```php
// Contains all the vehicles
$vehicles = [/*...*/];

if ($vehicle->exists()) {
    // Create import for existing media group
    $mediaGroupImportCommand = new CreateImportForExistingMediaGroup($vehicle->getMediaGroup());
} else {
    // Create import for new media group
    $mediaGroupImportCommand = new CreateImportForNewMediaGroup(Type::image());
}

foreach ($vehicles as $vehicle) {
    foreach ($vehicle->getImages() as $sequence => $imagePath) {
        $mediaGroupImportCommand->add(
            new MediaItemImportDataTransferObject(
                $imagePath,
                $sequence,
                Method::copy() // Other possible values are; Method::move() or Method::download()
            );
        );
    }
}

// Handle the MediaGroup create
$this->get('command_bus')->handle($mediaGroupImportCommand);

// We can now set the $mediaGroup in our vehicle
$vehicle->setMediaGroup($mediaGroupImportCommand->getMediaGroup());
```
> The code above will fill our "MediaItemImport" table. But we still need to execute the following code to "copy", "move" or "download" the image/file to our system and make it create a MediaItem for it if needed.

After all the vehicles import/sychronisation code is done.
Then we recommend executing the script which will import new MediaItem, or finds existing MediaItem items...
```php
$this->get('media_library.import')->execute();
```
> We recommend using the Console command `app/console media_library:import` instead.
