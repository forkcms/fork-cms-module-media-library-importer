<?php

namespace Backend\Modules\MediaLibraryImporter\Domain\MediaItemImport;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Doctrine\ORM\EntityRepository;

final class MediaItemImportRepository extends EntityRepository
{
    /**
     * @param MediaItemImport $mediaItemImport
     *
     * We don't flush here, see http://disq.us/p/okjc6b
     */
    public function add(MediaItemImport $mediaItemImport)
    {
        $this->getEntityManager()->persist($mediaItemImport);
    }

    /**
     * @return array
     */
    public function findAllForImport(): array
    {
        return $this->findBy(
            ['status' => Status::POSSIBLE_VALUES_FOR_IMPORT],
            ['createdOn' => 'ASC']
        );
    }

    /**
     * @param MediaGroup $mediaGroup
     * @param string $path
     * @return null|object
     */
    public function findExistingImported(MediaGroup $mediaGroup, string $path)
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

    /**
     * @return int
     */
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

    /**
     * @param MediaItemImport $mediaItemImport
     *
     * We don't flush here, see http://disq.us/p/okjc6b
     */
    public function remove(MediaItemImport $mediaItemImport)
    {
        $this->getEntityManager()->remove($mediaItemImport);
    }
}
