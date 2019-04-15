<?php
namespace ProcessMaker\Query\Tests\Feature;

use ProcessMaker\Query\SyntaxError;
use ProcessMaker\Query\Tests\TestCase;
use ProcessMaker\Query\Tests\Models\TestRecord;

class TraitTest extends TestCase
{
    /**
     * Check to see if syntax errors are thrown
     */
    public function testSyntaxErrorIsThrown()
    {
        // Note the extra double quote at the end
        $this->expectException(SyntaxError::class);
        $results = TestRecord::pmql("data.first_name = \"Taylor\"\"")->get();
    }

    public function testSimpleExpression()
    {
        $builder = TestRecord::pmql('foo = "bar"');
        $this->assertEquals('select * from "test_records" where ("foo" = ?)', $builder->toSql());
        $this->assertEquals([
            'bar'
        ], $builder->getBindings());
    }

    public function testSimpleGroupedExpression()
    {
        $builder = TestRecord::pmql('foo = "bar" AND cat = "dog"');
        $this->assertEquals('select * from "test_records" where ("foo" = ? and "cat" = ?)', $builder->toSql());
        $this->assertEquals([
            'bar',
            'dog'
        ], $builder->getBindings());
    }

    public function testQuery()
    {
        $results = TestRecord::pmql('data.first_name = "Taylor"')->get();
        $this->assertCount(1, $results);
    }

    public function testNoMatchQuery()
    {
        $results = TestRecord::pmql('data.first_name = "Invalid"')->get();
        $this->assertCount(0, $results);
    }

    public function testMatchWithGrouping()
    {
        $results = TestRecord::pmql('data.first_name = "Taylor" OR data.first_name = "Alan"')->get();
        $this->assertCount(2, $results);
    }
}
