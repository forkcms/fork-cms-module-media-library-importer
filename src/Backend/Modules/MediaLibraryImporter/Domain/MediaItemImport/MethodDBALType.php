<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class MethodDBALType extends StringType
{
    public const NAME = 'media_item_import_method';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Method
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Method
    {
        return Method::fromString($value);
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
