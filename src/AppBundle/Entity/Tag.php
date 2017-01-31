<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Rule", mappedBy="targetTag", cascade={"persist", "remove"})
     */
    private $rules;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Rule[]|ArrayCollection
     */
    public function getRules()
    {
        return $this->rules;
    }

    public function addRule(Rule $rule)
    {
        $rule->setTargetTag($this);
        $this->rules->add($rule);
    }

    public function removeRule(Rule $rule)
    {
        $this->rules->removeElement($rule);
    }

    /**
     * @param Rule[]|ArrayCollection $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }
}