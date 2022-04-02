<?php
declare(strict_types=1);

namespace Tests\unit\Statistics\Calculator;

use PHPUnit\Framework\TestCase;
use SocialPost\Dto\SocialPostTo;
use Statistics\Calculator\AveragePostsPerUser;
use Statistics\Dto\ParamsTo;
use Statistics\Enum\StatsEnum;

/**
 * Class ATestTest
 *
 * @package Tests\unit
 */
class AveragePostsPerUserTest extends TestCase
{
    /**
     * @param SocialPostTo[] $posts
     * @param ParamsTo $params
     * @param float $expectedAverage
     *
     * @dataProvider calculateProvider
     *
     * @return void
     */
    public function testCalculate(array $posts, float $expectedAverage)
    {
        $calculator = new AveragePostsPerUser();
        $paramsTo = (new ParamsTo())
            ->setStartDate(new \DateTime('first day of this month'))
            ->setEndDate(new \DateTime('last day of this month'))
            ->setStatName(StatsEnum::AVERAGE_POST_NUMBER_PER_USER);

        $calculator->setParameters($paramsTo);

        foreach ($posts as $post) {
            $calculator->accumulateData($post);
        }
        $stats = $calculator->calculate();
        $this->assertSame($expectedAverage, $stats->getValue());
    }

    public function calculateProvider(): array
    {
        return [
            'zero posts' => [
                [],
                0,
            ],
            'average value of one author posts' => [
                [
                    (new SocialPostTo())
                        ->setAuthorId('1')
                        ->setAuthorName('some author')
                        ->setDate(new \DateTime())
                        ->setId('1')
                        ->setText('some text')
                        ->setType('some type'),
                    (new SocialPostTo())
                        ->setAuthorId('1')
                        ->setAuthorName('some author')
                        ->setDate(new \DateTime())
                        ->setId('1')
                        ->setText('some text')
                        ->setType('some type'),
                ],
                2,
            ],
            'average value of different authors' => [
                [
                    (new SocialPostTo())
                        ->setAuthorId('1')
                        ->setAuthorName('some author')
                        ->setDate(new \DateTime())
                        ->setId('1')
                        ->setText('some text')
                        ->setType('some type'),
                    (new SocialPostTo())
                        ->setAuthorId('2')
                        ->setAuthorName('some other author')
                        ->setDate(new \DateTime())
                        ->setId('2')
                        ->setText('some text')
                        ->setType('some type'),
                    (new SocialPostTo())
                        ->setAuthorId('3')
                        ->setAuthorName('some indie author')
                        ->setDate(new \DateTime())
                        ->setId('3')
                        ->setText('some text')
                        ->setType('some type'),
                    (new SocialPostTo())
                        ->setAuthorId('4')
                        ->setAuthorName('some incredible author')
                        ->setDate(new \DateTime())
                        ->setId('4')
                        ->setText('some text')
                        ->setType('some type'),
                    (new SocialPostTo())
                        ->setAuthorId('5')
                        ->setAuthorName('some unknown author')
                        ->setDate(new \DateTime())
                        ->setId('5')
                        ->setText('some text')
                        ->setType('some type'),
                    (new SocialPostTo())
                        ->setAuthorId('6')
                        ->setAuthorName('some famous author')
                        ->setDate(new \DateTime())
                        ->setId('6')
                        ->setText('some text')
                        ->setType('some type'),
                ],
                1,
            ],
            'anonymous authors not count' => [
                [
                    (new SocialPostTo())
                        ->setAuthorId('1')
                        ->setAuthorName('some author')
                        ->setDate(new \DateTime())
                        ->setId('1')
                        ->setText('some text')
                        ->setType('some type'),
                    (new SocialPostTo())
                        ->setAuthorId('1')
                        ->setAuthorName('some author')
                        ->setDate(new \DateTime())
                        ->setId('2')
                        ->setText('some text')
                        ->setType('some type'),
                    (new SocialPostTo())
                        ->setAuthorId(null)
                        ->setAuthorName(null)
                        ->setDate(new \DateTime())
                        ->setId('1')
                        ->setText('some text')
                        ->setType('some type'),
                ],
                2,
            ],
        ];
    }
}
