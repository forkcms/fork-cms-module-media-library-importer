<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport;

final class Status
{
    protected const QUEUED = 'queued';
    protected const IMPORTED = 'imported';
    protected const EXISTING = 'existing';
    protected const ERROR = 'error';
    public const POSSIBLE_VALUES = [
        self::QUEUED,
        self::IMPORTED,
        self::EXISTING,
        self::ERROR,
    ];
    public const POSSIBLE_VALUES_FOR_IMPORT = [
        self::QUEUED,
        self::ERROR,
    ];
    public const POSSIBLE_VALUES_FOR_IMPORTED = [
        self::IMPORTED,
        self::EXISTING,
    ];

    /** @var string */
    private $status;

    private function __construct(string $status)
    {
        if (!in_array($status, self::POSSIBLE_VALUES, true)) {
            throw new \InvalidArgumentException(
                'Invalid value for the MediaItemImport status. Possible values; ' . implode(',', self::POSSIBLE_VALUES)
            );
        }

        $this->status = $status;
    }

    public static function fromString(string $status): Status
    {
        return new self($status);
    }

    public function __toString(): string
    {
        return $this->status;
    }

    public function equals(Status $status): bool
    {
        if (!$status instanceof $this) {
            return false;
        }

        return $status == $this;
    }

    public static function queued(): Status
    {
        return new self(self::QUEUED);
    }

    public function isQueued(): bool
    {
        return $this->equals(self::queued());
    }

    public static function imported(): Status
    {
        return new self(self::IMPORTED);
    }

    public function isImported(): bool
    {
        return $this->equals(self::imported());
    }

    public static function existing(): Status
    {
        return new self(self::EXISTING);
    }

    public function isExisting(): bool
    {
        return $this->equals(self::existing());
    }

    public static function error(): Status
    {
        return new self(self::ERROR);
    }

    public function isError(): bool
    {
        return $this->equals(self::error());
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
