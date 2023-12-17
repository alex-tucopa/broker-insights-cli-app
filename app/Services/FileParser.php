<?php

namespace App\Services;

use Generator;
use App\Exceptions\UnknownDataFormatException;

abstract class FileParser
{
    private function __construct(
        protected array $dataMap = [],
    ){}

    public static function makeParser(array $formatConfig): FileParser
    {
        if ($formatConfig['format'] === 'csv') {
            return new CsvFileParser($formatConfig['map']);
        } else if($formatConfig['format'] === 'json') {
            return new JsonFileParser($formatConfig['map']);
        }

        throw new UnknownDataFormatException();
    }

    abstract public function parse(string $filename): Generator;

    protected function parseRecord(array $record, array $dataMap): array
    {
        $output = [];

        foreach($record as $key => $value) {
            $mapping = $dataMap[$key];

            if (is_array($mapping)) {
                $output[$mapping['name']] = call_user_func($mapping['transform'], ($value));
            } else {
                $output[$mapping] = $value;
            }
        }

        return $output;
    }
}
