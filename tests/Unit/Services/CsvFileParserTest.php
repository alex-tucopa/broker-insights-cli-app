<?php

use App\Exceptions\CsvFormatException;
use App\Services\CsvFileParser;
use App\Services\DataTransforms;

describe('CsvFileParser', function () {
    it('maps headers', function ($inputFormat, $expectedOutput) {
        $inputFileName = 'tests/data/test_1.csv';

        $parser = new CsvFileParser($inputFormat);

        $actualOutput = [];

        foreach ($parser->parse($inputFileName) as $record) {
            $actualOutput[] = $record;
        }

        $this->assertEquals($expectedOutput, $actualOutput);
    })->with(function () {
        $format1 = [
            'source_header_1' => 'target_header_1',
            'source_header_2' => 'target_header_2',
            'source_header_3' => 'target_header_3',
        ];

        $expected1 = [
            [
                'target_header_1' => 'value_1_1',
                'target_header_2' => 'value_1_2',
                'target_header_3' => '01/06/2001',
            ],
            [
                'target_header_1' => 'value_2_1',
                'target_header_2' => 'value_2_2',
                'target_header_3' => '01/06/2002',
            ],
            [
                'target_header_1' => 'value_3_1',
                'target_header_2' => 'value_3_2',
                'target_header_3' => '01/06/2003',
            ],
        ];


        return [
            [$format1, $expected1],
        ];
    });

    it('applies transforms', function ($inputFormat, $expectedOutput) {
        $inputFileName = 'tests/data/test_1.csv';

        $parser = new CsvFileParser($inputFormat);

        $actualOutput = [];

        foreach ($parser->parse($inputFileName) as $record) {
            $actualOutput[] = $record;
        }

        $this->assertEquals($expectedOutput, $actualOutput);
    })->with(function () {
        $formatWithTransforms = [
            'source_header_1' => 'target_header_1',
            'source_header_2' => [
                'name' => 'target_header_2',
                'transform' => fn($in) => str_replace('value', 'transformed', $in),
            ],
            'source_header_3' => [
                'name' => 'target_header_3',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
        ];

        $expectedWithTransforms = [
            [
                'target_header_1' => 'value_1_1',
                'target_header_2' => 'transformed_1_2',
                'target_header_3' => '2001-06-01',
            ],
            [
                'target_header_1' => 'value_2_1',
                'target_header_2' => 'transformed_2_2',
                'target_header_3' => '2002-06-01',
            ],
            [
                'target_header_1' => 'value_3_1',
                'target_header_2' => 'transformed_3_2',
                'target_header_3' => '2003-06-01',
            ],
        ];

        return [
            [$formatWithTransforms, $expectedWithTransforms],
        ];
    });

    it('throws exception if headers do not match format', function() {
        $inputFileName = 'tests/data/test_1.csv';

        $nonMatchingFormat = [
            'NOT_source_header_1' => 'target_header_1',
            'source_header_2' => 'target_header_2',
            'source_header_3' => 'target_header_3',
        ];

        $parser = new CsvFileParser($nonMatchingFormat);
        $generator = $parser->parse($inputFileName);
        $generator->next();
    })->throws(CsvFormatException::class);
});
