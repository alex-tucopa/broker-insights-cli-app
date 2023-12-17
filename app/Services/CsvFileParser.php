<?php

namespace App\Services;

use Generator;
use App\Exceptions\DataFormatException;

class CsvFileParser extends FileParser
{
    public function parse(string $filename): Generator
    {
        try {
            $stream = fopen($filename, 'r');

            $headers = fgetcsv($stream);

            if ($headers !== array_keys($this->dataMap)) {
                throw new DataFormatException("CSV headers do not match format, filename: {$filename}");
            }

            while ($record = fgetcsv($stream)) {
                yield $this->parseRecord(array_combine($headers, $record), $this->dataMap);
            }
        } finally {
            fclose($stream);
        }
    }
}
