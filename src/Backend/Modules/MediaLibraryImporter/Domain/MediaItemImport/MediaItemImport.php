<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport;

use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolder;
use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Doctrine\ORM\Mapping as ORM;

/**
 * MediaItemImport
 *
 * @ORM\Entity(repositoryClass="Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImportRepository")
 * @ORM\HasLifecycleCallbacks
 */
class MediaItemImport
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    protected $id;

    /**
     * @var Status
     *
     * @ORM\Column(type="media_item_import_status")
     */
    protected $status;

    /**
     * @var Method
     *
     * @ORM\Column(type="media_item_import_method")
     */
    protected $method;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $sequence;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $path;

    /**
     * @var MediaGroup
     *
     * @ORM\ManyToOne(
     *      targetEntity="Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup",
     *      cascade="persist",
     *      fetch="EAGER"
     * )
     * @ORM\JoinColumn(
     *      name="mediaGroupId",
     *      referencedColumnName="id",
     *      onDelete="cascade",
     *      nullable=false
     * )
     */
    protected $mediaGroup;

    /**
     * @var MediaItem|null
     *
     * @ORM\ManyToOne(
     *      targetEntity="Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem",
     *      cascade="persist"
     * )
     * @ORM\JoinColumn(
     *      name="mediaItemId",
     *      referencedColumnName="id",
     *      onDelete="cascade"
     * )
     */
    protected $mediaItem;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $executedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $importedOn;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $errorMessage;

    /**
     * @param MediaGroup $mediaGroup
     * @param string $path
     * @param int $sequence
     * @param Method $method
     */
    private function __construct(
        MediaGroup $mediaGroup,
        string $path,
        int $sequence,
        Method $method
    ) {
        $this->mediaGroup = $mediaGroup;
        $this->path = $path;
        $this->sequence = $sequence;
        $this->method = $method;
    }

    /**
     * @param MediaItemImportDataTransferObject $mediaItemDataTransferObject
     * @return MediaItemImport
     */
    public static function fromDataTransferObject(
        MediaItemImportDataTransferObject $mediaItemDataTransferObject
    ): MediaItemImport {
        return new self(
            $mediaItemDataTransferObject->mediaGroup,
            $mediaItemDataTransferObject->path,
            $mediaItemDataTransferObject->sequence,
            $mediaItemDataTransferObject->method
        );
    }

    /**
     * Gets the value of id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    public function changeStatusToError(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
        $this->status = Status::error();
    }

    /**
     * @param MediaItem $mediaItem
     */
    public function changeStatusToExisting(MediaItem $mediaItem)
    {
        $this->errorMessage = null;
        $this->mediaItem = $mediaItem;
        $this->status = Status::existing();
    }

    /**
     * @param string $path
     * @param MediaFolder $mediaFolder
     * @param int $userId
     */
    public function changeStatusToImported(string $path, MediaFolder $mediaFolder, int $userId)
    {
        $this->mediaItem = MediaItem::createFromLocalStorageType(
            $path,
            $mediaFolder,
            $userId
        );

        $this->errorMessage = null;
        $this->status = Status::imported();
        $this->importedOn = new \Datetime();
    }

    /**
     * @return Method
     */
    public function getMethod(): Method
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * Gets the value of createdOn.
     *
     * @return \DateTime
     */
    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }

    /**
     * @return \DateTime
     */
    public function getImportedOn(): \DateTime
    {
        return $this->importedOn;
    }

    /**
     * @return \DateTime
     */
    public function getExecutedOn(): \DateTime
    {
        return $this->executedOn;
    }

    /**
     * @return MediaGroup
     */
    public function getMediaGroup(): MediaGroup
    {
        return $this->mediaGroup;
    }

    /**
     * @return MediaItem|null
     */
    public function getMediaItem()
    {
        return $this->mediaItem;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdOn = new \Datetime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->executedOn = new \Datetime();
    }
}
