<?php

namespace AppBundle\Service;

use AppBundle\Entity\Document;

class DocumentCreatorService
{
    private $ocrService;

    public function __construct(OcrService $ocrService)
    {
        $this->ocrService = $ocrService;
    }

    public function fromFile($filePath)
    {
        $document = new Document();

        $text = $this->ocrService->extractText($filePath);

        $document->setContent($text);

        return $document;
    }
}