<?php

namespace AppBundle\Service;

use AppBundle\Entity\Document;
use AppBundle\Entity\Rule;
use Doctrine\ORM\EntityManagerInterface;

class DocumentMatcherService
{
    private $ruleApplyService;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, RuleApplyService $ruleApplyService)
    {
        $this->entityManager = $entityManager;
        $this->ruleApplyService = $ruleApplyService;
    }

    /**
     * @param Rule $rule
     * @return Document[]|array
     */
    public function findDocumentsMatching(Rule $rule)
    {
        $documents = $this->findAllDocuments();

        return array_filter($documents, function(Document $document) use ($rule) {
            return $this->ruleApplyService->doesApply($rule, $document);
        });
    }

    public function addTagToDocumentsMatching(Rule $rule)
    {
        foreach ($this->findAllDocuments() as $document) {
            $this->ruleApplyService->apply($rule, $document);
        }
    }

    /**
     * @return Document[]|array
     */
    private function findAllDocuments()
    {
        return $this->entityManager
            ->getRepository(Document::class)
            ->findAll();
    }
}