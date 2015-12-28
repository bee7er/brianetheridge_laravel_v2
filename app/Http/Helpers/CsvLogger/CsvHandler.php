<?php
/**
 * Created by PhpStorm.
 * User: szabadhegyim
 * Date: 30/06/15
 * Time: 17:12
 */
namespace App\Helpers\CsvLogger;

/**
 * Stores logs to files that are rotated every day and a limited number of files are kept.
 *
 * This rotation is only intended to be used as a workaround. Using logrotate to
 * handle the rotation is strongly encouraged when you can use it.
 *
 * @author Christophe Coevoet <stof@notk.org>
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class CsvHandler extends \Monolog\Handler\RotatingFileHandler
{

    public function handle(array $record)
    {
        $record = $this->processRecord($record);

        $record['formatted'] = $this->getFormatter()->format($record);

        $this->write($record);

        return false === $this->bubble;
    }
}
