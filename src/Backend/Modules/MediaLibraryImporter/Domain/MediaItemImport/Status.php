<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport;

final class Status
{
    const QUEUED = 'queued';
    const IMPORTED = 'imported';
    const EXISTING = 'existing';
    const ERROR = 'error';
    const POSSIBLE_VALUES = [
        self::QUEUED,
        self::IMPORTED,
        self::EXISTING,
        self::ERROR,
    ];
    const POSSIBLE_VALUES_FOR_IMPORT = [
        self::QUEUED,
        self::ERROR,
    ];
    const POSSIBLE_VALUES_FOR_IMPORTED = [
        self::IMPORTED,
        self::EXISTING,
    ];

    /** @var string */
    private $status;

    /**
     * @param string $status
     */
    private function __construct(string $status)
    {
        if (!in_array($status, self::POSSIBLE_VALUES, true)) {
            throw new \InvalidArgumentException(
                'Invalid value for the MediaItemImport status. Possible values; ' . implode(',', self::POSSIBLE_VALUES)
            );
        }

        $this->status = $status;
    }

    /**
     * @param string $status
     * @return Status
     */
    public static function fromString(string $status): Status
    {
        return new self($status);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return bool
     */
    public function equals(Status $status): bool
    {
        if (!$status instanceof $this) {
            return false;
        }

        return $status == $this;
    }

    /**
     * @return Status
     */
    public static function queued(): Status
    {
        return new self(self::QUEUED);
    }

    /**
     * @return bool
     */
    public function isQueued(): bool
    {
        return $this->equals(self::queued());
    }

    /**
     * @return Status
     */
    public static function imported(): Status
    {
        return new self(self::IMPORTED);
    }

    /**
     * @return bool
     */
    public function isImported(): bool
    {
        return $this->equals(self::imported());
    }

    /**
     * @return Status
     */
    public static function existing(): Status
    {
        return new self(self::EXISTING);
    }

    /**
     * @return bool
     */
    public function isExisting(): bool
    {
        return $this->equals(self::existing());
    }

    /**
     * @return Status
     */
    public static function error(): Status
    {
        return new self(self::ERROR);
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->equals(self::error());
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
