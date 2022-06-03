<?php

namespace Kronos;

class Regroup
{
    /**
     * @return array[]
     *
     * @psalm-return array<non-empty-list<mixed>>
     */
    public function regroupIdenticalElements(array $array, callable $comparisonFunction): array
    {
        $sets = [];

        foreach ($array as $element) {
            $matchedSet = false;
            foreach ($sets as $setIndex => $set) {
                foreach ($set as $setMember) {
                    if ($comparisonFunction($setMember, $element)) {
                        if ($matchedSet === false) {
                            $sets[$setIndex][] = $element;
                            $matchedSet = $setIndex;
                            break;
                        } else {
                            foreach ($set as $mergedSetMember) {
                                $sets[$matchedSet][] = $mergedSetMember;
                            }
                            unset($sets[$setIndex]);
                            break;
                        }
                    }
                }
            }

            if ($matchedSet === false) {
                $sets[] = [$element];
            }
        }

        return $sets;
    }

    /**
     * @return array[]
     *
     * @psalm-return list<list<mixed>>
     */
    public function regroupByHash(array $array, callable $hashFunction): array
    {
        $sets = [];

        foreach ($array as $element) {
            $hash = $hashFunction($element);

            if (isset($sets[$hash])) {
                $sets[$hash][] = $element;
            } else {
                $sets[$hash] = [$element];
            }
        }

        return array_values($sets);
    }
}