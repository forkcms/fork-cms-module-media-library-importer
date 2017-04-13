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

    /**
     * @param string $method
     */
    private function __construct(string $method)
    {
        if (!in_array($method, self::POSSIBLE_VALUES, true)) {
            throw new \InvalidArgumentException(
                'Invalid value for possible MediaItemImport method. Possible values; ' . implode(',', self::POSSIBLE_VALUES)
            );
        }

        $this->method = $method;
    }

    /**
     * @param string $method
     * @return Method
     */
    public static function fromString(string $method): Method
    {
        return new self($method);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->method;
    }

    /**
     * @param Method $method
     * @return bool
     */
    public function equals(Method $method): bool
    {
        return $method->method === $this->method;
    }

    /**
     * @return Method
     */
    public static function copy(): Method
    {
        return new self(self::COPY);
    }

    /**
     * @return bool
     */
    public function isCopy(): bool
    {
        return $this->equals(self::copy());
    }

    /**
     * @return Method
     */
    public static function move(): Method
    {
        return new self(self::MOVE);
    }

    /**
     * @return bool
     */
    public function isMove(): bool
    {
        return $this->equals(self::move());
    }

    /**
     * @return Method
     */
    public static function download(): Method
    {
        return new self(self::DOWNLOAD);
    }

    /**
     * @return bool
     */
    public function isDownload(): bool
    {
        return $this->equals(self::download());
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
}
