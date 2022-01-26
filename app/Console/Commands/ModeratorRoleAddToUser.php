<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Console\Command;

class ModeratorRoleAddToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moderator:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add moderator role to user';

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
        foreach($users as $user) {
            if($user->hasRole('user')){
                $user_recipes = Recipe::where('user_id', $user->id)->get()->count();
                if($user_recipes >= 100) {
                    $user->assignRole('moderator');
                }
            }
        }
        $this->info('Done successfully!');
    }
}
