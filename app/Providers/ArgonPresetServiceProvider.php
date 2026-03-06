<?php
namespace LaravelFrontendPresets\ArgonPreset;
use Illuminate\Support\ServiceProvider;
use Laravel\Ui\UiCommand;
class ArgonPresetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        UiCommand::macro('argon', function ($command) {
            ArgonPreset::install();
            $command->info('Argon scaffolding installed successfully.');
        });
    }
    public function register()
    {
    }
}