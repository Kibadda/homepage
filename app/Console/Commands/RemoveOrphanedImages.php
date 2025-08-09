<?php

namespace App\Console\Commands;

use App\Models\Memory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RemoveOrphanedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-orphaned-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (Storage::disk('public')->allFiles('memories') as $filename) {
            $this->line("Searching for {$filename} in memories.");

            $memory = Memory::where('image', $filename)->first();

            if (!$memory) {
                $this->line('Not found in memories. Removing file.');

                Storage::disk('public')->delete($filename);
            }

            $this->newLine();
        }
    }
}
