<?php

namespace MakinaCorpus\Lucene\Tests;

use DateTime;
use MakinaCorpus\Lucene\Query;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass  \MakinaCorpus\Lucene\Query
 */
class BasicQueryTest extends TestCase
{
    public function testSomeQuery()
    {
        $query = new Query();

        $now = new DateTime("@1467205820");

        $query
            ->setOperator(Query::OP_AND)
            ->createTermCollection(Query::OP_OR)
            ->add("foo")
            ->add("bar");

        $query
            ->createDateRange('my_date_field')
            ->setInclusive()
            ->setRange('1983-03-22', $now);

        $query
            ->matchTerm('some_field', 'some value', null, 0.8)
            ->matchTerm('other_field', 'some_other_value', 1.3);

        // @codingStandardsIgnoreStart
        $this->assertSame(
            '((foo OR bar) AND my_date_field:["1983\-03\-22T00\:00\:00\+0000" TO "2016\-06\-29T13\:10\:20\+0000"] AND some_field:"some value"~0.8 AND other_field:some_other_value^1.3)',
            trim((string)$query)
        );
        // @codingStandardsIgnoreEnd
    }
}
