<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Helpers\CsvLogger;

/**
 * Formats incoming records into a one-line csv string
 *
 * This is especially useful for logging to files
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Christophe Coevoet <stof@notk.org>
 */
class CsvFormatter extends \Monolog\Formatter\LineFormatter
{
    const SIMPLE_FORMAT = "%time%,%level_name%,%message%,%csv%\n";
    public $delimiter = ',';

    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        $str = parent::format($record);
        $str = str_replace('%time%', $record['datetime']->format('H:i:s'), $str);
        $str = str_replace('%csv%', $this->csvify($record['context']), $str);

        return rtrim($str, $this->delimiter);
    }

    /**
     * Convert array to csv like format.
     *
     * @param  $context Context
     * @return string
     */
    public function csvify($context)
    {
        $str = '';
        foreach ($context as $index => $value) {
            // NB Here we are outputting both a column name and the corresponding value.
            // We are writing this out as a name/value pair as 'colName,colValue'.
            // The intention is that we will therefore have enough detail to write informative queries
            // over the log files later.
            if (is_array($value)) {
                foreach ($value as $idx => $val) {
                    $str .= ($this->checkColumnName($idx) . $val . $this->delimiter);
                }
            } else {
                $str .= ($this->checkColumnName($index) . $value . $this->delimiter);
            }
        }
        return rtrim($str, $this->delimiter);
    }

    /**
     * We intend using the data included with a log entry as CSV data, perhaps
     * displaying it in a spreadsheet.  This method checks to make sure we have
     * something that looks like a column name, either 'col_<number>' or '<column_name>'
     *
     * @param  $columnName
     * @return string
     */
    private function checkColumnName($columnName)
    {
        if (is_numeric($columnName)) {
            return '';
        }
        return $columnName . $this->delimiter;
    }
}
