<?php

namespace Kronos\Tests;

use Kronos\Regroup;
use PHPUnit\Framework\TestCase;

class RegroupTest extends TestCase
{
    const FIRST_ELEMENT = 'firstElement';
    const SECOND_ELEMENT = 'secondElement';
    const UNIQUE_ELEMENT = 'uniqueElement';
    const FIRST_MATCH = 'firstMatch';
    const SECOND_MATCH = 'secondMatch';

    /**
     * @var Regroup
     */
    private $regroup;

    public function setUp(): void
    {
        $this->regroup = new Regroup();
    }

    public function test_UnmatchedElements_regroupIdenticalElements_ShouldReturnASetForEachElements(): void
    {
        $elements = [
            self::FIRST_ELEMENT,
            self::SECOND_ELEMENT
        ];

        $comparisonFunction = function ($elmt1, $elmt2) {
            return false;
        };

        $expectedSets = [
            [
                self::FIRST_ELEMENT
            ],
            [
                self::SECOND_ELEMENT
            ]
        ];

        $regroupedElements = $this->regroup->regroupIdenticalElements($elements, $comparisonFunction);

        $this->assertEquals($expectedSets, $regroupedElements);
    }

    public function test_TwoMatchingElements_regroupIdenticalElements_ShouldReturnArrayContainingBothElemenetsInASet(): void
    {
        $elements = [
            self::FIRST_ELEMENT,
            self::SECOND_ELEMENT
        ];

        $comparisonFunction = function ($elmt1, $elmt2) {
            return true;
        };

        $regroupedElements = $this->regroup->regroupIdenticalElements($elements, $comparisonFunction);

        $this->assertEquals([$elements], $regroupedElements);
    }

    public function test_MatchingElementsInArray_regroupIdenticalElements_ShouldReturnArrayContainingMatchingElementsInSets(
    ): void
    {
        $elements = [
            self::FIRST_MATCH,
            self::SECOND_MATCH,
            self::UNIQUE_ELEMENT,
            self::FIRST_MATCH,
            self::SECOND_MATCH
        ];

        $comparisonFunction = function ($elmt1, $elmt2) {
            return $elmt1 == $elmt2;
        };

        $expectedSets = [
            [
                self::FIRST_MATCH,
                self::FIRST_MATCH
            ],
            [
                self::SECOND_MATCH,
                self::SECOND_MATCH
            ],
            [
                self::UNIQUE_ELEMENT
            ]
        ];

        $regroupedElements = $this->regroup->regroupIdenticalElements($elements, $comparisonFunction);

        $this->assertEquals($expectedSets, $regroupedElements);
    }

    public function test_ElementMatchingTwoSets_regroupIdenticalElements_ShouldReturnSetRegroupingMatchingSets(): void
    {
        $elements = [];

        $firstElement = new \stdClass();
        $firstElement->firstProperty = self::FIRST_MATCH;
        $firstElement->secondProperty = 'unique second property';
        $elements[] = $firstElement;

        $secondElement = new \stdClass();
        $secondElement->firstProperty = 'unique first property';
        $secondElement->secondProperty = self::SECOND_MATCH;
        $elements[] = $secondElement;

        $thirdElement = new \stdClass();
        $thirdElement->firstProperty = self::FIRST_MATCH;
        $thirdElement->secondProperty = self::SECOND_MATCH;
        $elements[] = $thirdElement;

        $comparisonFunction = function ($elmt1, $elmt2) {
            return $elmt1->firstProperty == $elmt2->firstProperty || $elmt1->secondProperty == $elmt2->secondProperty;
        };

        $expectedSets = [
            [
                $firstElement,
                $thirdElement,
                $secondElement
            ]
        ];

        $regroupedElements = $this->regroup->regroupIdenticalElements($elements, $comparisonFunction);

        $this->assertEquals($expectedSets, $regroupedElements);
    }

    public function test_NonMatchingElements_regroupByHash_ShouldReturnASetForEachElement(): void
    {
        $elements = [
            self::FIRST_ELEMENT,
            self::SECOND_ELEMENT
        ];

        $hashFonction = function ($element) {
            return md5($element);
        };

        $expectedSets = [
            [
                self::FIRST_ELEMENT
            ],
            [
                self::SECOND_ELEMENT
            ]
        ];

        $regroupedElements = $this->regroup->regroupByHash($elements, $hashFonction);

        $this->assertEquals($expectedSets, $regroupedElements);
    }

    public function test_MatchingElements_regroupByHash_ShouldReturnSetWithMathingElements(): void
    {
        $elements = [
            self::FIRST_MATCH,
            self::SECOND_MATCH,
            self::UNIQUE_ELEMENT,
            self::FIRST_MATCH,
            self::SECOND_MATCH
        ];

        $hashFonction = function ($element) {
            return md5($element);
        };

        $expectedSets = [
            [
                self::FIRST_MATCH,
                self::FIRST_MATCH
            ],
            [
                self::SECOND_MATCH,
                self::SECOND_MATCH
            ],
            [
                self::UNIQUE_ELEMENT
            ]
        ];

        $regroupedElements = $this->regroup->regroupByHash($elements, $hashFonction);

        $this->assertEquals($expectedSets, $regroupedElements);
    }
}
