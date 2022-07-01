<?php

namespace CfdiUtilsTests\Internals;

use CfdiUtils\Internals\CommandTemplate;
use CfdiUtilsTests\TestCase;

final class CommandTemplateTest extends TestCase
{
    public function providerTemplateCommandToArrayArguments(): array
    {
        return [
            'first argument' => ['? fire', ['command'], ['command', 'fire']],
            'no argument' => ['foo bar baz', [], ['foo', 'bar', 'baz']],
            'replace with 2 replacements' => ['foo ? => ?', ['bar', 'baz'], ['foo', 'bar', '=>', 'baz']],
            'replace spaces' => ['foo   ?   => ? x', ['bar', 'baz'], ['foo', 'bar', '=>', 'baz', 'x']],
            'more ? than arguments' => ['foo   ?   => ? x', [], ['foo', '', '=>', '', 'x']],
            'less ? than arguments' => ['foo ? => ?', ['bar'], ['foo', 'bar', '=>', '']],
        ];
    }

    /**
     * @param string $template
     * @param array $arguments
     * @param array $expected
     * @dataProvider providerTemplateCommandToArrayArguments
     */
    public function testCreateCommandFromTemplate(string $template, array $arguments, array $expected)
    {
        $builder = new CommandTemplate();

        $command = $builder->create($template, $arguments);
        $this->assertSame($expected, $command);
    }
}
