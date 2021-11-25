<?php

namespace App\Console\Commands;

use App\Models\Calendar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MakeNotificationAboutEmptyNextDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'makeNotificationAboutEmptyNextDay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign permission to roles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        $tomorrow = Carbon::now()->addDay(1)->toDateString();
        foreach($users as $user){
            $calendar = Calendar::where('owner_id', $user->id)->first();
            if($calendar->recipes()->where('start_at', $tomorrow)->count() == 0){
                $user->notify(new \App\Notifications\RecipeEmptyNextDay);
            }
        }
        $this->info('Done successfully!');
    }
}
