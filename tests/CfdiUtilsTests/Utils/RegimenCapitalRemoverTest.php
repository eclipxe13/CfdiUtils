<?php

namespace CfdiUtilsTests\Utils;

use CfdiUtils\Utils\RegimenCapitalRemover;
use CfdiUtilsTests\TestCase;

final class RegimenCapitalRemoverTest extends TestCase
{
    /** @return array<string, array{string, string}> */
    public function providerMostCommonCases(): array
    {
        return [
            'SA' => ['EMPRESA PATITO', 'SA'],
            'SA with extra space' => ['EMPRESA PATITO ', 'SA'],
            'SA DE CV' => ['EMPRESA PATITO', 'SA DE CV'],
            'SAB' => ['EMPRESA PATITO', 'SAB'],
            'S DE RL' => ['EMPRESA PATITO', 'SAB'],
        ];
    }

    /** @dataProvider providerMostCommonCases */
    public function testMostCommonCases(string $expectedName, string $suffix): void
    {
        $fullname = sprintf('%s %s', $expectedName, $suffix);
        $expectedName = trim($expectedName);
        $remover = RegimenCapitalRemover::createDefault();
        $removedName = $remover->remove($fullname);

        $this->assertSame($expectedName, $removedName);
    }

    public function testRemoveOnlyOneEntry(): void
    {
        // SA & SAB, both are suffixes.
        $fullname = 'AUDITORES SAB SA';
        $expected = 'AUDITORES SAB';
        $remover = RegimenCapitalRemover::createDefault();
        $this->assertSame($expected, $remover->remove($fullname));
    }
}
