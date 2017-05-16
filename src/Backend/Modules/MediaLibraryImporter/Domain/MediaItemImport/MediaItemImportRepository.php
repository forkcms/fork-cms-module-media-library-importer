<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Doctrine\ORM\EntityRepository;

final class MediaItemImportRepository extends EntityRepository
{
    public function add(MediaItemImport $mediaItemImport): void
    {
        // We don't flush here, see http://disq.us/p/okjc6b
        $this->getEntityManager()->persist($mediaItemImport);
    }

    public function findAllForImport(): array
    {
        return $this->findBy(
            ['status' => Status::POSSIBLE_VALUES_FOR_IMPORT],
            ['createdOn' => 'ASC']
        );
    }

    public function findExistingImported(MediaGroup $mediaGroup, string $path): ?MediaItemImport
    {
        return $this->findOneBy(
            [
                'mediaGroup' => $mediaGroup,
                'status' => Status::POSSIBLE_VALUES_FOR_IMPORTED,
                'path' => $path,
            ],
            ['importedOn' => 'DESC']
        );
    }

    public function getNumberOfImports(): int
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select('count(i.id)')
            ->from(MediaItemImport::class, 'i')
            ->where('i.status IN(:status)')
            ->setParameter('status', Status::POSSIBLE_VALUES_FOR_IMPORT)
            ->orderBy('i.createdOn', 'ASC');

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function remove(MediaItemImport $mediaItemImport)
    {
        // We don't flush here, see http://disq.us/p/okjc6b
        $this->getEntityManager()->remove($mediaItemImport);
    }
}
