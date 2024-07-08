<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use mikehaertl\pdftk\Pdf;

class PdfFieldsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all the fields in the pdf.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pdf = new Pdf(storage_path('template.pdf'));
        $fields = collect($pdf->getDataFields());
        $this->output->table(['Fieldname'], $fields->map(function($field) {
            return ['Fieldname' => $field['FieldName']];
        })->toArray());
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
