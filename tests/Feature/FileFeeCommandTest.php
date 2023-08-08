<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class FileFeeCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_fees_from_input_file()
    {
        // Create a temporary test input file
        $inputFile = tempnam(sys_get_temp_dir(), 'input1001.txt');
        file_put_contents($inputFile, '{"bin": "45717360", "currency": "EUR", "amount": 100}');
        file_put_contents($inputFile, PHP_EOL . '{"bin": "45717360", "currency": "USD", "amount": 50}', FILE_APPEND);

        $this->artisan('app:filefee', [
            'file_name' => $inputFile,
        ])
            ->expectsOutput('1')
            ->expectsOutput('0.46');

        // Clean up the temporary test input file
        unlink($inputFile);
    }

    /** @test */
    public function it_displays_error_for_nonexistent_file()
    {
        $this->artisan('app:filefee', [
            'file_name' => 'nonexistent.txt',
        ])
            ->expectsOutput('File not found: nonexistent.txt');
    }
}
