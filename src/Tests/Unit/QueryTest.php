<?php

namespace GuedesOrm\Tests\Unit;

use GuedesOrm\Model;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    public function test_must_create_a_select_query_for_sql()
    {
        $orm = new Model('users');

        $query = $orm->toSql();

        $this->assertEquals('SELECT * FROM users', $query);
    }

    public function test_should_create_a_select_query_for_sql_with_specified_columns()
    {
        $orm = new Model('users');
        $orm->select(['id', 'name', 'email']);

        $query = $orm->toSql();

        $this->assertEquals('SELECT id, name, email FROM users', $query);
    }

    public function test_must_create_a_select_query_for_sql_with_condition()
    {
        $orm = new Model('users');
        $orm->where('id', '=', 2);

        $query = $orm->toSql();

        $this->assertEquals('SELECT * FROM users WHERE id = 2', $query);
    }

    public function test_should_create_a_select_query_for_sql_with_conditions_and()
    {
        $orm = new Model('users');
        $orm->where('id', '=', 2)
            ->where('name', 'like', 'Vitor');

        $query = $orm->toSql();

        $this->assertEquals('SELECT * FROM users WHERE id = 2 AND name LIKE Vitor', $query);
    }

    public function test_should_create_a_select_query_for_sql_with_conditions_or()
    {
        $orm = new Model('users');
        $orm->where('id', '=', 2)
            ->orWhere('id', '=', '22');

        $query = $orm->toSql();

        $this->assertEquals('SELECT * FROM users WHERE id = 2 OR id = 22', $query);
    }

    public function test_should_create_a_select_query_for_sql_with_conditions_and_with_or()
    {
        $orm = new Model('users');
        $orm->where('id', '=', 2)
            ->where('name', 'like', 'Vitor')
            ->where('email', '=', 'vitor@gmail.com')
            ->orWhere('id', '=', '22');

        $query = $orm->toSql();

        $this->assertEquals('SELECT * FROM users WHERE id = 2 AND name LIKE Vitor AND email = vitor@gmail.com OR id = 22', $query);
    }
}