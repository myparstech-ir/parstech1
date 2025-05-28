<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\TenantManager;
use Stancl\Tenancy\Database\Models\Tenant as TenantModel;
use Illuminate\Support\Str;

class MakeTenant extends Command
{
    protected $signature = 'make:tenant {id?}';
    protected $description = 'Create a new tenant (Multi-Tenancy)';

    public function handle()
    {
        $id = $this->argument('id') ?? $this->ask('Enter tenant id (or leave empty for random)', Str::random(8));
        // اگر tenant با این id وجود دارد، پیام بده
        if (TenantModel::find($id)) {
            $this->error("Tenant with id \"$id\" already exists.");
            return 1;
        }

        $tenant = TenantModel::create([
            'id' => $id,
            'data' => [],
        ]);

        $tenant->createDatabase();
        $tenant->runMigrations();

        $this->info("Tenant $id created and migrations executed!");
        return 0;
    }
}
