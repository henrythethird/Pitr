<?php

namespace AppBundle\Entity\Embeddable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class ExpressionEmbeddable
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $regex = false;

    /**
     * @ORM\Column(type="string")
     */
    private $pattern;

    /**
     * @return boolean
     */
    public function isRegex()
    {
        return $this->regex;
    }

    /**
     * @param boolean $regex
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }
}