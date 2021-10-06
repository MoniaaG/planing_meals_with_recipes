<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:assign';

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
        $permissions = config('roles_permissions.permissions');
        foreach($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $assign = config('roles_permissions.assigns');
        foreach($assign as $role => $permissions) {
            $role = Role::findOrCreate($role);
            $role->syncPermissions([$permissions]);
        }

        $this->info('Done successfully!');
    }
}
