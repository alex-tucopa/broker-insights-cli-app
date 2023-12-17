<?php

namespace App\Services;

use Generator;
use App\Exceptions\DataFormatException;

class JsonFileParser extends FileParser
{
    public function parse(string $filename): Generator
    {
        $json = file_get_contents($filename);

        $data = json_decode($json, true);

        foreach ($data as $record) {
            $recordFieldNames = array_keys($record);
            $dataMapFieldNames = array_keys($this->dataMap);

            if (array_diff($recordFieldNames, $dataMapFieldNames)) {
                throw new DataFormatException("JSON data structure does not match format, filename {$filename}");
            }
            yield $this->parseRecord($record, $this->dataMap);
        }
    }
}
