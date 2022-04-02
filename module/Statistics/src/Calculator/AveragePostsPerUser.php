<?php

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

/**
 * Class LongestPostCalculator
 *
 * @package Statistics\Calculator
 */
class AveragePostsPerUser extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var float[]
     */
    private array $totalPostsByUser = [];

    /**
     * @param SocialPostTo $postTo
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $authorId = $postTo->getAuthorId();
        if (null === $authorId) {
            return;
        }

        if (!isset($this->totalPostsByUser[$authorId])) {
            $this->totalPostsByUser[$authorId] = 0.0;
        }
        ++$this->totalPostsByUser[$authorId];
    }

    /**
     * @return StatisticsTo
     */
    protected function doCalculate(): StatisticsTo
    {
        $averageValue = count($this->totalPostsByUser) ?
            array_sum($this->totalPostsByUser) / count($this->totalPostsByUser) :
            0;

        return (new StatisticsTo())->setValue($averageValue);
    }
}
