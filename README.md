# Master Category
A tiny utility for ExpressionEngine that filters posts against a master category.

This utility takes the results of a channel:entries query (or extended query) and compares each result against a master category. The utility removes all results that are not classified under the specified master category.

This provides the developer the flexibility to gather a collection of entries from multiple categories and then eliminate entries that are not matches to the master. Counts and paging stay accurate, unlike nested category-matching "if" statements.

Usage:

{exp:channel:entries channel="awesome_stuff" master="8" category="1|3"}

{exp:store:products master="8" category="1|3|11"}

The above examples remove all results that are not in category 8, while collecting entries from multiple other categories.


