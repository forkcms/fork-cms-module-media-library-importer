<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\Command;

use Backend\Modules\MediaLibrary\Component\StorageProvider\LocalStorageProviderInterface;
use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolderRepository;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItemRepository;
use Backend\Modules\MediaLibrary\Manager\FileManager;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\Exception\MediaImportFailed;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImport;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\MediaItemImportRepository;
use Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport\Method;

class ExecuteMediaItemImportHandler
{
    /** @var FileManager */
    protected $fileManager;

    /** @var LocalStorageProviderInterface */
    protected $localStorageProvider;

    /** @var MediaFolderRepository */
    protected $mediaFolderRepository;

    /** @var MediaItemRepository */
    protected $mediaItemRepository;

    /** @var MediaItemImportRepository */
    protected $mediaItemImportRepository;

    /**
     * ExecuteMediaItemImportHandler constructor.
     *
     * @param FileManager $fileManager
     * @param LocalStorageProviderInterface $localStorageProvider
     * @param MediaFolderRepository $mediaFolderRepository
     * @param MediaItemImportRepository $mediaItemImportRepository
     * @param MediaItemRepository $mediaItemRepository
     */
    public function __construct(
        FileManager $fileManager,
        LocalStorageProviderInterface $localStorageProvider,
        MediaFolderRepository $mediaFolderRepository,
        MediaItemImportRepository $mediaItemImportRepository,
        MediaItemRepository $mediaItemRepository
    ) {
        $this->fileManager = $fileManager;
        $this->localStorageProvider = $localStorageProvider;
        $this->mediaFolderRepository = $mediaFolderRepository;
        $this->mediaItemImportRepository = $mediaItemImportRepository;
        $this->mediaItemRepository = $mediaItemRepository;
    }

    /**
     * @param ExecuteMediaItemImport $executeMediaItemImport
     */
    public function handle(ExecuteMediaItemImport $executeMediaItemImport)
    {
        /** @var MediaItemImport $mediaItemImport */
        $mediaItemImport = $executeMediaItemImport->getMediaItemImportEntity();

        if ($this->linkExistingMediaItem($mediaItemImport)) {
            return;
        }

        $this->linkNewMediaItem($mediaItemImport);
    }

    /**
     * @param MediaItemImport $mediaItemImport
     * @return MediaItem|null
     */
    private function findExistingMediaItem(MediaItemImport $mediaItemImport)
    {
        /** @var MediaItemImport|null $existingMediaItemImport */
        $existingMediaItemImport = $this->mediaItemImportRepository->findExistingImported(
            $mediaItemImport->getMediaGroup(),
            $mediaItemImport->getPath()
        );

        if (!$existingMediaItemImport instanceof MediaItemImport) {
            return null;
        }

        return $existingMediaItemImport->getMediaItem();
    }

    /**
     * @param MediaItemImport $mediaItemImport
     * @return string
     */
    private function getDestinationPath(MediaItemImport $mediaItemImport): string
    {
        // Define upload dir
        $uploadDir = $this->localStorageProvider->getUploadRootDir() . '/' . $this->fileManager->getNextShardingFolder();

        // Generate folder if not exists
        $this->fileManager->createFolder($uploadDir);

        // Generate filename which doesn't exist yet in our media library
        $newName = $this->fileManager->getUniqueFileName(
            $uploadDir,
            basename($mediaItemImport->getPath())
        );

        return $uploadDir . '/' . $newName;
    }

    /**
     * @param $path
     * @return integer
     * @throws MediaImportFailed
     */
    private function getFileSize($path)
    {
        try {
            return filesize($path);
        } catch (\Exception $e) {
            throw MediaImportFailed::forPath($path);
        }
    }

    /**
     * @param string $path
     * @return string
     * @throws MediaImportFailed
     */
    private function getMd5($path)
    {
        try {
            return md5_file($path);
        } catch (\Exception $e) {
            throw MediaImportFailed::forPath($path);
        }
    }

    /**
     * @param MediaItemImport $mediaItemImport
     * @param string $destinationPath
     * @throws MediaImportFailed
     */
    private function importMedia(MediaItemImport $mediaItemImport, string $destinationPath)
    {
        switch ($mediaItemImport->getMethod()->getMethod()) {
            case Method::COPY:
                $this->fileManager->getFilesystem()->copy($mediaItemImport->getPath(), $destinationPath);
                break;
            case Method::MOVE:
                $this->fileManager->rename($mediaItemImport->getPath(), $destinationPath);
                break;
            case Method::DOWNLOAD:
                $tryDownloading = true;
                $downloadCounter = 0;
                while ($tryDownloading) {
                    $downloadCounter += 1;

                    // ToDo: add try/catch here, so instead of trying once and throwing exception
                    // we should keep in the while loop, until we find the trying is enough
                    if (file_put_contents($destinationPath, fopen($mediaItemImport->getPath(), 'r'))) {
                        $tryDownloading = false;
                        continue;
                    }

                    if ($downloadCounter === 5) {
                        throw MediaImportFailed::forPath($mediaItemImport->getPath());
                    }
                }
                break;
        }
    }

    /**
     * @param MediaItemImport $mediaItemImport
     * @param MediaItem $mediaItem
     * @return bool
     * @throws MediaImportFailed
     */
    private function isMatchingAlreadyExistingMediaItem(MediaItemImport $mediaItemImport, MediaItem $mediaItem): bool
    {
        $oldPath = $mediaItem->getAbsoluteWebPath();
        $newPath = $mediaItemImport->getPath();

        // We check if our existing MediaItem file matches the new one we received
        if ($this->getFileSize($oldPath) === $this->getFileSize($newPath)
            && $this->getMd5($oldPath) === $this->getMd5($newPath)
        ) {
            return true;
        }

        return false;
    }

    /**
     * We find an eventually existing media item and if it matches exactly, we are going to use it.
     *
     * @param MediaItemImport $mediaItemImport
     * @return bool
     */
    private function linkExistingMediaItem(MediaItemImport $mediaItemImport): bool
    {
        /** @var MediaItem|null $existingMediaItem */
        $existingMediaItem = $this->findExistingMediaItem($mediaItemImport);

        // No MediaItem found
        if (!$existingMediaItem instanceof MediaItem) {
            return false;
        }

        // No matching MediaItem found
        if (!$this->isMatchingAlreadyExistingMediaItem($mediaItemImport, $existingMediaItem)) {
            return false;
        }

        // Change status because we found existing MediaItem
        $mediaItemImport->changeStatusToExisting($existingMediaItem);

        return true;
    }

    /**
     * We got a completely new media item, so we move it to the right place and create a MediaItem for it.
     *
     * @param MediaItemImport $mediaItemImport
     */
    private function linkNewMediaItem(MediaItemImport $mediaItemImport)
    {
        // Create new MediaItem
        try {
            /** @var string $destinationPath */
            $destinationPath = $this->getDestinationPath($mediaItemImport);

            // We place the media-item in the right path
            $this->importMedia($mediaItemImport, $destinationPath);

            // We now create a MediaItem and change status of MediaItemImport
            $mediaItemImport->changeStatusToImported(
                $destinationPath,
                $this->mediaFolderRepository->findDefault(),
                1
            );

            // We add the new item
            $this->mediaItemRepository->add($mediaItemImport->getMediaItem());
        } catch (\Exception $e) {
            $mediaItemImport->changeStatusToError($e->getMessage());
        }
    }
}
