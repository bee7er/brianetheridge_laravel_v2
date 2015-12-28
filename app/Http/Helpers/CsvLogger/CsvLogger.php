<?php

namespace App\Helpers\CsvLogger;

class CsvLogger extends \Monolog\Logger
{
    const DEBUG = 'debug';

    /**
     * How many days to keep the files
     *
     * @var int
     */
    public static $daysToKeep = 365;

    /**
     * Original monolog object
     *
     * @var object
     */
    protected static $monoLog;

    /**
     * Add a new line to the csv logging
     *
     * @param string $message    Message of the file
     * @param array  $data       Data
     * @param string $level      Level of the warning
     * @param string $filePrefix
     *
     * @return void
     */
    public static function csv(
        $message,
        Array $data = array(),
        $level = 'notice',
        $filePrefix = 'be'
    ) {
        self::initLog($filePrefix, $level);

        $levels = array_flip(self::$monoLog->getLevels());
        if (!isset($levels[strtoupper($level)])) {
            $level = 'info';
        } else {
            $level = $levels[$level];
        }

        return self::$monoLog->{strtolower($level)}(
            $message,
            $data
        );
    }

    /**
     * Init log object and keep it as a singleton
     *
     * @param string $filePrefix Prefix of the file
     *
     * @return CsvLogger
     */
    private static function initLog($filePrefix = '')
    {
        if (empty(self::$monoLog)
            || !is_a(self::$monoLog, 'Logger')
        ) {
            $handler = new \App\Helpers\CsvLogger\CsvHandler(
                storage_path().'/logs/'.$filePrefix.'.csv',
                self::$daysToKeep
            );
            $handler->setFormatter(new \App\Helpers\CsvLogger\CsvFormatter());
            $monoLog = new CsvLogger('BEE', [$handler]);
            self::$monoLog = $monoLog;
        }

        return self::$monoLog;
    }
}
