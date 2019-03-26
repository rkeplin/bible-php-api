<?php
namespace Test\Unit\Controller;

use Domain\Service\TranslationFactory;
use PHPUnit\Framework\TestCase;

/**
 * TranslationFactoryTest
 */
class TranslationFactoryTest extends TestCase
{
    public function testGetTranslationTable()
    {
        $this->assertEquals(
            't_asv',
            TranslationFactory::getTranslationTable('asv')
        );

        $this->assertEquals(
            't_bbe',
            TranslationFactory::getTranslationTable('bbe')
        );

        $this->assertEquals(
            't_dby',
            TranslationFactory::getTranslationTable('dby')
        );

        $this->assertEquals(
            't_kjv',
            TranslationFactory::getTranslationTable('kjv')
        );

        $this->assertEquals(
            't_wbt',
            TranslationFactory::getTranslationTable('wbt')
        );

        $this->assertEquals(
            't_web',
            TranslationFactory::getTranslationTable('web')
        );

        $this->assertEquals(
            't_ylt',
            TranslationFactory::getTranslationTable('ylt')
        );
    }
}

