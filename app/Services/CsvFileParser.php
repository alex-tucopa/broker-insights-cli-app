<?php

namespace App\Services;

use Generator;
use App\Exceptions\CsvFormatException;

class CsvFileParser extends CsvParser
{
    public function __construct(
        private array $dataMap = [],
    ){}

    public function parse(string $filename): Generator
    {
        try {
            $stream = fopen($filename, 'r');

            $headers = fgetcsv($stream);

            if ($headers !== array_keys($this->dataMap)) {
                throw new CsvFormatException("CSV headers do not match format, filename: {$filename}");
            }

            while ($record = fgetcsv($stream)) {
                yield $this->parseRecord($record, array_values($this->dataMap));
            }
        } finally {
            fclose($stream);
        }
    }
}
;