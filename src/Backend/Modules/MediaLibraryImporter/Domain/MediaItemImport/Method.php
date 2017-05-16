<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport;

final class Method
{
    const COPY = 'copy';
    const DOWNLOAD = 'download';
    const MOVE = 'move';
    const POSSIBLE_VALUES = [
        self::COPY,
        self::DOWNLOAD,
        self::MOVE
    ];

    /** @var string */
    private $method;

    private function __construct(string $method)
    {
        if (!in_array($method, self::POSSIBLE_VALUES, true)) {
            throw new \InvalidArgumentException(
                'Invalid value for possible MediaItemImport method. Possible values; ' . implode(',', self::POSSIBLE_VALUES)
            );
        }

        $this->method = $method;
    }

    public static function fromString(string $method): Method
    {
        return new self($method);
    }

    public function __toString(): string
    {
        return $this->method;
    }

    public function equals(Method $method): bool
    {
        return $method->method === $this->method;
    }

    public static function copy(): Method
    {
        return new self(self::COPY);
    }

    public function isCopy(): bool
    {
        return $this->equals(self::copy());
    }

    public static function move(): Method
    {
        return new self(self::MOVE);
    }

    public function isMove(): bool
    {
        return $this->equals(self::move());
    }

    public static function download(): Method
    {
        return new self(self::DOWNLOAD);
    }

    public function isDownload(): bool
    {
        return $this->equals(self::download());
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
