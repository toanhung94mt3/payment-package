<?php

namespace ToanHung94mt3\PaymentPackage\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ToanHung94mt3\PaymentPackage\Tests\TestCase;

class InstallPaymentPackageTest extends TestCase
{
    /**
     * @test
     */
    function theInstallCommandCopiesTheConfiguration()
    {
        // make sure we're starting from a clean state
        if (File::exists(config_path('paymentpackage.php'))) {
            unlink(config_path('paymentpackage.php'));
        }

        $this->assertFalse(File::exists(config_path('paymentpackage.php')));

        Artisan::call('paymentpackage:install');

        $this->assertTrue(File::exists(config_path('paymentpackage.php')));
    }

    /**
     * @test
     */
    public function whenAConfigFileIsPresentUsersCanChooseToNotOverwriteIt()
    {
        // Given we have already have an existing config file
        File::put(config_path('paymentpackage.php'), 'test contents');
        $this->assertTrue(File::exists(config_path('paymentpackage.php')));

        // When we run the install command
        $command = $this->artisan('paymentpackage:install');

        // We expect a warning that our configuration file exists
        $command->expectsQuestion(
            'Config file already exists. Do you want to overwrite it?',
            // When answered with "no"
            'no'
        );

        // We should see a message that our file was not overwritten
        $command->expectsOutput('Existing configuration was not overwritten');

        // Assert that the original contents of the config file remain
        $this->assertEquals(file_get_contents(config_path('paymentpackage.php')), 'test contents');

        // Clean up
        unlink(config_path('paymentpackage.php'));
    }

    /**
     * @test
     */
    public function whenAConfigFileIsPresentUsersCanChooseToDoOverwriteIt()
    {
        // Given we have already have an existing config file
        File::put(config_path('paymentpackage.php'), 'test contents');
        $this->assertTrue(File::exists(config_path('paymentpackage.php')));

        // When we run the install command
        $command = $this->artisan('paymentpackage:install');

        // We expect a warning that our configuration file exists
        $command->expectsQuestion(
            'Config file already exists. Do you want to overwrite it?',
            // When answered with "yes"
            'yes'
        );

        $command->expectsOutput('Overwriting configuration file...');

        // Assert that the original contents are overwritten
        $this->assertEquals(
            file_get_contents(config_path('paymentpackage.php')),
            file_get_contents(__DIR__ . '/../config/config.php')
        );

        // Clean up
        unlink(config_path('paymentpackage.php'));
    }
}
