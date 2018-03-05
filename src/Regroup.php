<?php

namespace Kronos;

class Regroup
{
	public function regroupIdenticalElements($array, callable $comparisonFunction)
	{
		$sets = [];

		foreach($array as $element)
		{
			$matchedSet = false;
			foreach($sets as $setIndex => $set)
			{
				foreach($set as $setMember) {
					if($comparisonFunction($setMember, $element))
					{
						if($matchedSet === false)
						{
							$sets[$setIndex][] = $element;
							$matchedSet = $setIndex;
							break;
						}
						else
						{
							foreach($set as $mergedSetMember)
							{
								$sets[$matchedSet][] = $mergedSetMember;
							}
							unset($sets[$setIndex]);
							break;
						}
					}
				}
			}

			if($matchedSet === false)
			{
				$sets[] = [$element];
			}
		}

		return $sets;
	}

	public function regroupByHash($array, callable $hashFunction)
	{
		$sets = [];

		foreach($array as $element)
		{
			$hash = $hashFunction($element);

			if(isset($sets[$hash]))
			{
				$sets[$hash][] = $element;
			}
			else
			{
				$sets[$hash] = [$element];
			}
		}

		return array_values($sets);
	}
}