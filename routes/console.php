<?php

use App\Console\Commands\RemoveOrphanedImages;
use Illuminate\Support\Facades\Schedule;

Schedule::command(RemoveOrphanedImages::class)->daily();
