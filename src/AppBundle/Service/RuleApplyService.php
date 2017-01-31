<?php

namespace AppBundle\Service;

use AppBundle\Entity\Document;
use AppBundle\Entity\Embeddable\ExpressionEmbeddable;
use AppBundle\Entity\Rule;

class RuleApplyService
{
    public function doesApply(Rule $rule, Document $document)
    {
        return $this->ruleApplies($rule, $document->getContent());
    }

    public function apply(Rule $rule, Document $document)
    {
        if (!$this->doesApply($rule, $document)) {
            return;
        }

        $content = $document->getContent();

        if (!$this->ruleApplies($rule, $content)) {
            return;
        }

        $document->addTag($rule->getTargetTag());
    }

    private function ruleApplies(Rule $rule, $content)
    {
        foreach ($rule->getExperssions() as $experssion) {
            if ($this->expressionApplies($experssion, $content)) {
                return true;
            }
        }
        return false;
    }

    private function expressionApplies(ExpressionEmbeddable $expression, $content)
    {
        if ($expression->isRegex()) {
            return $this->matchRegex($expression, $content);
        }

        return $this->matchNormal($expression, $content);
    }

    private function matchNormal(ExpressionEmbeddable $expression, $content)
    {
        return mb_strpos($content, $expression->getPattern()) !== false;
    }

    private function matchRegex(ExpressionEmbeddable $expression, $content)
    {
        return preg_match($expression->getPattern(), $content);
    }
}