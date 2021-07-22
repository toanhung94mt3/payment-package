<?php

namespace ToanHung94mt3\PaymentPackage\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallPaymentPackage extends Command
{
    protected $hidden = true;
    
    protected $signature = 'paymentpackage:install';

    protected $description = 'Install the PaymentPackage';

    public function handle()
    {
        $this->info('Installing PaymentPackage...');

        $this->info('Publishing configuration...');

        if (!$this->configExists('paymentpackage.php')) {
            $this->publishConfiguration();
            $this->info('Published configuration');
        } else {
            if ($this->shouldOverwriteConfig()) {
                $this->info('Overwriting configuration file...');
                $this->publishConfiguration($force = true);
            } else {
                $this->info('Existing configuration was not overwritten');
            }
        }

        $this->info('Installed PaymentPackage');
    }

    private function configExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    private function shouldOverwriteConfig()
    {
        return $this->confirm(
            'Config file already exists. Do you want to overwrite it?',
            false
        );
    }

    private function publishConfiguration($forcePublish = false)
    {
        $params = [
            '--provider' => "ToanHung94mt3\PaymentPackage\PaymentProvider",
            '--tag' => "config",
        ];

        if ($forcePublish === true) {
            $params['--force'] = '';
        }

        $this->call('vendor:publish', $params);
    }
}
