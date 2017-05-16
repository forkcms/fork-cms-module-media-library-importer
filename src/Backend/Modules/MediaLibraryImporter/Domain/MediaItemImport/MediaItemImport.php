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
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

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

    private function __construct(
        MediaGroup $mediaGroup,
        string $path,
        int $sequence,
        Method $method,
        string $title = null
    ) {
        $this->mediaGroup = $mediaGroup;
        $this->path = $path;
        $this->sequence = $sequence;
        $this->method = $method;
        $this->title = $title;
    }

    public static function fromDataTransferObject(
        MediaItemImportDataTransferObject $mediaItemDataTransferObject
    ): MediaItemImport {
        return new self(
            $mediaItemDataTransferObject->getMediaGroup(),
            $mediaItemDataTransferObject->path,
            $mediaItemDataTransferObject->sequence,
            $mediaItemDataTransferObject->method,
            $mediaItemDataTransferObject->title
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function changeStatusToError(string $errorMessage)
    {
        $this->errorMessage = $errorMessage;
        $this->status = Status::error();
    }

    public function changeStatusToExisting(MediaItem $mediaItem)
    {
        $this->errorMessage = null;
        $this->mediaItem = $mediaItem;
        $this->status = Status::existing();

        if ($this->title !== null && $this->title !== $mediaItem->getTitle()) {
            $mediaItem->setTitle($this->title);
        }
    }

    public function changeStatusToImported(
        string $path,
        MediaFolder $mediaFolder,
        int $userId
    ) {
        $this->mediaItem = MediaItem::createFromLocalStorageType(
            $path,
            $mediaFolder,
            $userId
        );

        $this->errorMessage = null;
        $this->status = Status::imported();
        $this->importedOn = new \Datetime();
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }

    public function getImportedOn(): \DateTime
    {
        return $this->importedOn;
    }

    public function getExecutedOn(): \DateTime
    {
        return $this->executedOn;
    }

    public function getMediaGroup(): MediaGroup
    {
        return $this->mediaGroup;
    }

    public function getMediaItem(): ?MediaItem
    {
        return $this->mediaItem;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

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
