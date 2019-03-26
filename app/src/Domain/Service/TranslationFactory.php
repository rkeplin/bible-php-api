<?php
namespace Domain\Service;

/**
 * Class TranslationFactory
 *
 * @package Domain\Service
 */
class TranslationFactory
{
    /**
     * @var string
     */
    protected static $_defaultTable = 't_kjv';

    /**
     * @param $id
     * @return string
     */
    public static function getTranslationTable($id = null)
    {
        $table = self::$_defaultTable;

        $id = strtolower($id);

        switch ($id) {
            case 1:
            case 't_asv':
            case 'asv':
                $table = 't_asv';
                break;
            case 2:
            case 't_bbe':
            case 'bbe':
                $table = 't_bbe';
                break;
            case 3:
            case 't_dby':
            case 'dby':
                $table = 't_dby';
                break;
            case 4:
            case 't_kjv':
            case 'kjv':
                $table = 't_kjv';
                break;
            case 5:
            case 't_wbt':
            case 'wbt':
                $table = 't_wbt';
                break;
            case 6:
            case 't_web':
            case 'web':
                $table = 't_web';
                break;
            case 7:
            case 't_ylt':
            case 'ylt':
                $table = 't_ylt';
                break;

        }

        return $table;
    }
}
