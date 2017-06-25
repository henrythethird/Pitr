<?php

namespace AppBundle\Service;

use AppBundle\Entity\Document;

class DocumentCreatorService
{
    private $ocrService;
    private $documentMatcher;

    public function __construct(OcrService $ocrService, DocumentMatcherService $documentMatcher)
    {
        $this->ocrService = $ocrService;
        $this->documentMatcher = $documentMatcher;
    }

    public function fromFile($filePath)
    {
        $document = new Document();

        $text = $this->ocrService->extractText($filePath);

        $document->setContent($text);

        $this->documentMatcher->addTagsToDocument($document);

        return $document;
    }
}