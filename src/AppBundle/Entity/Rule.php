<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Embeddable\ExpressionEmbeddable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Rule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tag", inversedBy="rules")
     */
    private $targetTag;

    /**
     * @ORM\Embedded(class="AppBundle\Entity\Embeddable\ExpressionEmbeddable")
     */
    private $includesExpression;

    /**
     * @ORM\Embedded(class="AppBundle\Entity\Embeddable\ExpressionEmbeddable")
     */
    private $excludesExpression;

    public function __construct()
    {
        $this->includesExpression = new ExpressionEmbeddable();
        $this->excludesExpression = new ExpressionEmbeddable();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Tag
     */
    public function getTargetTag()
    {
        return $this->targetTag;
    }

    /**
     * @param Tag $targetTag
     */
    public function setTargetTag($targetTag)
    {
        $this->targetTag = $targetTag;
    }

    /**
     * @return ExpressionEmbeddable
     */
    public function getIncludesExpression()
    {
        return $this->includesExpression;
    }

    /**
     * @param ExpressionEmbeddable $includesExpression
     */
    public function setIncludesExpression($includesExpression)
    {
        $this->includesExpression = $includesExpression;
    }

    /**
     * @return ExpressionEmbeddable
     */
    public function getExcludesExpression()
    {
        return $this->excludesExpression;
    }

    /**
     * @param ExpressionEmbeddable $excludesExpression
     */
    public function setExcludesExpression($excludesExpression)
    {
        $this->excludesExpression = $excludesExpression;
    }

    /**
     * @return ExpressionEmbeddable[]|array
     */
    public function getExperssions()
    {
        return [
            'includes' => $this->getIncludesExpression(),
            'excludes' => $this->getExcludesExpression()
        ];
    }
}