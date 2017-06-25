<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Tag;
use AppBundle\Value\Folder;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class DocumentRepository extends EntityRepository
{
    public function findByTag(Tag $tag = null)
    {
        if (!$tag) {
            return $this->findAll();
        }

        return $this->createQueryBuilder('document')
            ->leftJoin('document.tags', 'tags')
            ->where('tags.id = :TAG')
            ->setParameter('TAG', $tag)
            ->getQuery()
            ->getResult();
    }

    public function matchWithFolder($queryString, $folder = null)
    {
        $queryBuilder = $this->createQueryBuilder('document');

        $this->addFolder($queryBuilder, $folder);
        $this->addQueryString($queryString, $queryBuilder);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function matchWithTag($queryString, Tag $tag = null)
    {
        $queryBuilder = $this->createQueryBuilder('document');

        $this->addTag($queryBuilder, $tag);
        $this->addQueryString($queryString, $queryBuilder);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    private function addFolder(QueryBuilder $queryBuilder, $folder)
    {
        if (!$folder) {
            return;
        }

        $queryBuilder
            ->andWhere('document.state IN (:STATE)')
            ->setParameter('STATE', Folder::FOLDER_MAP[$folder]);
    }

    /**
     * @param Tag $tag
     * @param $queryBuilder
     */
    private function addTag(QueryBuilder $queryBuilder, Tag $tag = null)
    {
        if (!$tag) {
            return;
        }

        $queryBuilder
            ->leftJoin('document.tags', 'tags')
            ->andWhere('tags.id = :TAG')
            ->setParameter('TAG', $tag);
    }

    /**
     * @param $queryString
     * @param $queryBuilder
     */
    private function addQueryString($queryString, QueryBuilder $queryBuilder)
    {
        $q = preg_replace("/\s+/", "+", trim($queryString));

        if (empty($q)) {
            return;
        }

        $queryBuilder
            ->andWhere('MATCH (document.content) AGAINST (:Q) > 0')
            ->setParameter('Q', '+' . $q);
    }
}