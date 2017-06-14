<?php

namespace AppBundle\Service;

use Google\Cloud\Vision\Annotation;
use Google\Cloud\Vision\VisionClient;

class OcrService
{
    public function extractText($imagePath)
    {
        return $this->cachedLookup($imagePath);
    }

    /**
     * @param $image
     * @return object
     * @throws \Exception
     */
    private function cachedLookup($image)
    {
        $response = $this->callGoogleApi($image);
        return $response;
    }

    private function callGoogleApi($imagePath)
    {
        $vision = new VisionClient([
            'projectId' => 'pitr-157214',
            'keyFilePath' => '/Users/patrickkaufmann/Projects/private/Pitr/app/Resources/api/key.json'
        ]);

        $image = $vision->image(file_get_contents($imagePath), ['TEXT_DETECTION']);
        /** @var Annotation $annotation */
        $annotation = $vision->annotate($image);
        /** @var Annotation\Entity $text */
        $text = $annotation->text()[0];
        return $text->description();
    }
}