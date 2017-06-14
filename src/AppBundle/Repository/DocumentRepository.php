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
}