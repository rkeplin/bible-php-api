<?php
namespace Test;

use Core\Application;
use PHPUnit\Framework\TestCase;
use PDOException;

/**
 * IntegrationTestCase
 *
 * @package \Test\Helper
 */
class IntegrationTestCase extends TestCase
{
    /**
     * @const int
     */
    const MAX_RETRIES = 30;

    /**
     * @const int
     */
    const SLEEP_INTERVAL = 5;

    protected function setUp(): void
    {
        $config = include APP_PATH . 'configs' . DS . 'default.php';
        $retries = 0;

        $app = new Application();

        while ($retries < self::MAX_RETRIES) {
            try {
                $app->setup($config);
                break;
            } catch (PDOException $e) {
                $retries++;

                echo "\n" . 'Waiting for DB connection';
                ob_flush();

                sleep(self::SLEEP_INTERVAL);
            }
        }
    }
}

