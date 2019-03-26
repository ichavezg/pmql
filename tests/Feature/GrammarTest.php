<?php
namespace ProcessMaker\Query\Tests\Feature;

use ProcessMaker\Query\Query;
use ProcessMaker\Query\SyntaxError;
use ProcessMaker\Query\Tests\TestCase;


class GrammarTest extends TestCase
{
    /**
     * Test to see if our parser supports the simple arithmetic of 1+1
     */
    public function testSimpleGrammar()
    {
        $query = app()->make(Query::class);
        $this->assertEquals($query->parse('1+1'), 2);
    }

    public function testAdvancedGrammar()
    {
        $query = app()->make(Query::class);
        $this->assertEquals($query->parse('2*(1+5)'), 12);
    }


    /**
     * Ensure we throw a grammar error. 
     */
    public function testGrammarError()
    {
        $this->expectException(SyntaxError::class);
        $query = app()->make(Query::class);
   }

    /** @test */
    public function it_runs_the_migrations()
    {
        $record = \DB::table('test_records')->where('id', '=', 1)->first();
        $this->assertEquals(['test' => 'value'], $record->data);
        $columns = \Schema::getColumnListing('test_records');
        $this->assertEquals([
            'id',
            'created_at',
            'updated_at',
            'data',
        ], $columns);
    }
}