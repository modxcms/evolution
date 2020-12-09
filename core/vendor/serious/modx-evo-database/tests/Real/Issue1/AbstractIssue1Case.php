<?php namespace AgelxNash\Modx\Evo\Database\Tests\Real\Issue1;

use AgelxNash\Modx\Evo\Database\Tests\RealQueryCase;

abstract class AbstractIssue1Case extends RealQueryCase
{
    public function testNullValue()
    {
        $this->checkIssueValue('mysql driver issue 1 check null', null);
    }

    public function testEmptyValue()
    {
        $this->checkIssueValue('mysql driver issue 1 check empty', '');
    }

    public function testNotEmptyValue()
    {
        $value = 'not empty';
        $this->checkIssueValue('mysql driver issue 1 check not empty', $value);
    }

    protected function checkIssueValue($desc, $value)
    {
        $id = $this->instance->insert(
            ['pagetitle' => $desc, 'issue_1' => $value],
            $this->table
        );

        $query = $this->instance->query("SELECT `issue_1` FROM " . $this->table . " WHERE `id` = " . $id);

        $found = $this->instance->getValue($query);

        $this->assertSame($value, $found, $desc);
    }
}
