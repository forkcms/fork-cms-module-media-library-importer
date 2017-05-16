<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\Exception;

class MediaImportFailed extends \Exception
{
    public static function forPath(string $path): self
    {
        return new self('Can\'t download file from path: "' . $path . '".');
    }
}
