<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class InsertNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:insert 
                            {code} 
                            {title} 
                            {description} 
                            {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insère une notification dans la table notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notification = Notification::create([
            'code' => $this->argument('code'),
            'title' => $this->argument('title'),
            'description' => $this->argument('description'),
            'type' => $this->argument('type'),
        ]);

        $this->info('Notification insérée avec succès ! ID : ' . $notification->id);
    }
}