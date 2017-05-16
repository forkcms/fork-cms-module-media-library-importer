<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class StatusDBALType extends StringType
{
    public const NAME = 'media_gallery_status';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Status
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Status
    {
        return Status::fromString($value);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }
}
