<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityRepository;

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

    public function matchDocuments($queryString, Tag $tag = null)
    {
        $q = preg_replace("/\s+/", "+", trim($queryString));

        $queryBuilder = $this->createQueryBuilder('document');

        if ($tag) {
            $queryBuilder
                ->leftJoin('document.tags', 'tags')
                ->andWhere('tags.id = :TAG')
                ->setParameter('TAG', $tag);
        }

        if (!empty($q)) {
            $queryBuilder
                ->andWhere('MATCH (document.content) AGAINST (:Q) > 0')
                ->setParameter('Q', '+' . $q);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}