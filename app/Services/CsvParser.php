<?php

namespace App\Services;

abstract class CsvParser
{
    protected function parseRecord(array $record, array $dataMap): array
    {
        $output = [];

        foreach ($dataMap as $columnIndex => $mapping) {
            if (is_array($mapping)) {
                $output[$mapping['name']] = call_user_func($mapping['transform'], ($record[$columnIndex]));
            } else {
                $output[$mapping] = $record[$columnIndex];
            }
        }

        return $output;
    }
}
