<?php

namespace Kronos\Tests;

use Kronos\Regroup;

class RegroupTest extends \PHPUnit_Framework_TestCase
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

    public function setUp()
    {
        $this->regroup = new Regroup();
    }

    public function test_UnmatchedElements_regroupIdenticalElements_ShouldReturnASetForEachElements()
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

    public function test_TwoMatchingElements_regroupIdenticalElements_ShouldReturnArrayContainingBothElemenetsInASet()
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
    )
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

    public function test_ElementMatchingTwoSets_regroupIdenticalElements_ShouldReturnSetRegroupingMatchingSets()
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

    public function test_NonMatchingElements_regroupByHash_ShouldReturnASetForEachElement()
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

    public function test_MatchingElements_regroupByHash_ShouldReturnSetWithMathingElements()
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