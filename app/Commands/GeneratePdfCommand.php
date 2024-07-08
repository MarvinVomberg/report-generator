<?php

namespace App\Commands;

use Carbon\Carbon;
use LaravelZero\Framework\Commands\Command;
use mikehaertl\pdftk\Pdf;

class GeneratePdfCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Pdf files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = \OpenAI::factory()->withApiKey(config('openai.key'))->make();

        $fromDate = $this->output->ask('Start date of the report (DD.MM.YYYY):');
        $toDate = $this->output->ask('End date of the report (DD.MM.YYYY):');
        $startFrom = Carbon::createFromFormat('d.m.Y', $fromDate);
        $startTo = Carbon::createFromFormat('d.m.Y', $toDate);

        $startNumber = $this->output->ask('Start number of the report:');

        $x = 0;
        do {
            $from = $startFrom->addWeeks($x);
            $fromFormatted = $from->startOfWeek()->format('d.m.Y');
            $to = $from->endOfWeek();
            $toFormatted = $to->endOfWeek()->format('d.m.Y');

            $topic = $this->ask('Which topic to use for ' . $fromFormatted . ' - ' . $toFormatted . '?');

            $progress = $this->output->createProgressBar(1);
            $progress->display();
            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => config('metadata.initial_prompt')],
                    ['role' => 'user', 'content' => config('metadata.prompt_prefix'). $topic],
                ]
            ]);
            $progress->finish();
            $this->output->newLine();

            $schoolTopics = [];
            do {
                $schoolTopic = $this->ask('What are the school topics for ' . $fromFormatted . ' - ' . $toFormatted . '?');
                if($schoolTopic) {
                    $schoolTopics[] = $schoolTopic;
                }
            } while($schoolTopic);

            $pdf = new Pdf(storage_path('template.pdf'));
            $pdf->fillForm([
                config('pdf.company') => config('metadata.company'),
                config('pdf.year') => config('metadata.year'),
                config('pdf.from') => $fromFormatted,
                config('pdf.to') => $toFormatted,
                config('pdf.sequential_number') => $startNumber,
                config('pdf.name') => config('metadata.name'),
                config('pdf.company_work') => $response->choices[0]->message->content,
                config('pdf.school_work') => implode("\n", $schoolTopics),
            ])->needAppearances()->saveAs(storage_path(str_replace('.', '-', "filled/{$fromFormatted}_{$toFormatted}") . '.pdf'));

            $this->output->info("Pdf for topic: $topic generated successfully.");
            $x++;
            $startNumber++;
        } while ($to->lte($startTo));
        $this->output->info('Pdf files generated successfully.');

    }
}
