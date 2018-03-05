# kronos/regroup
Utility classes to regroup elements in sets

For now, it can regroup identical elements based on a given comparison function or a hash function

##### Note regarding `regroupIdentialElements`

The order in which elements are given won't affect the result. 
Given three elements A, B and C where A and C match, B and C match but A and B do not, the result will always be a set containg A, B and C. 

See tests for examples.
