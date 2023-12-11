<?php

use App\Services\DataTransforms;

describe('DataTransforms', function() {
    it('transforms date', function() {
        expect(DataTransforms::dateFormat('22/07/2001'))->toBe('2001-07-22');
    });

    it('transforms date with custom format', function() {
        expect(DataTransforms::dateFormat('07/22/2001', 'm/d/Y'))->toBe('2001-07-22');
    });

    it('returns data unchanged if does not match format', function() {
        expect(DataTransforms::dateFormat('22-07-2001', 'd/m/Y'))->toBe('22-07-2001');
    });
});
