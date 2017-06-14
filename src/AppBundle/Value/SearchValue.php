<?php

namespace AppBundle\Value;

class SearchValue
{
    private $term;

    public function __construct($term = "")
    {
        $this->term = $term;
    }

    public function getTerm()
    {
        return $this->term;
    }

    public function setTerm($term)
    {
        $this->term = $term;
        return $this;
    }
}