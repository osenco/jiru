<?php

namespace Osen\Updater;

use Osen\Updater\Commands\CheckForUpdate;
use Osen\Updater\Contracts\SourceRepositoryTypeContract;
use Osen\Updater\Models\Release;
use Osen\Updater\Models\UpdateExecutor;
use Osen\Updater\Notifications\EventHandler;
use Osen\Updater\SourceRepositoryTypes\GithubRepositoryType;
use Osen\Updater\SourceRepositoryTypes\GithubRepositoryTypes\GithubBranchType;
use Osen\Updater\SourceRepositoryTypes\GithubRepositoryTypes\GithubTagType;
use Osen\Updater\SourceRepositoryTypes\HttpRepositoryType;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

/**
 * UpdaterServiceProvider.php.
 *
 * @author Osen Concepts < hi@osen.co.ke >
 * @copyright See LICENSE file that was distributed with this source code.
 */
class UpdaterServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/updater.php' => config_path('updater.php'),
        ], 'config');

        $this->loadViews();
    }

    /**
     * Set up views.
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'updater');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/updater'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/updater.php', 'updater');

        $this->app['events']->subscribe(EventHandler::class);

        $this->registerCommands();
        $this->registerManager();
    }

    /**
     * Register the package its commands.
     */
    protected function registerCommands()
    {
        $this->commands([
            CheckForUpdate::class,
        ]);

        // Register custom commands from config
        collect(config('updater.artisan_commands.pre_update'))->each(function ($command) {
            $this->commands([$command['class']]);
        });
        collect(config('updater.artisan_commands.post_update'))->each(function ($command) {
            $this->commands([$command['class']]);
        });
    }

    /**
     * Register the manager class.
     */
    protected function registerManager()
    {
        $this->app->singleton('updater', function () {
            return new UpdaterManager(app());
        });

        $this->app->bind(Release::class, function (): Release {
            return new Release(new Filesystem());
        });

        $this->app->bind(UpdateExecutor::class, function () {
            return new UpdateExecutor();
        });

        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(Client::class, function () {
            return new Client(['base_uri' => GithubRepositoryType::GITHUB_API_URL]);
        });

        $this->app->bind(GithubRepositoryType::class, function (): GithubRepositoryType {
            return new GithubRepositoryType(
                config('updater.repository_types.github'),
                $this->app->make(UpdateExecutor::class)
            );
        });

        $this->app->bind(GithubBranchType::class, function (): SourceRepositoryTypeContract {
            return new GithubBranchType(
                config('updater.repository_types.github'),
                $this->app->make(ClientInterface::class),
                $this->app->make(UpdateExecutor::class)
            );
        });

        $this->app->bind(GithubTagType::class, function (): SourceRepositoryTypeContract {
            return new GithubTagType(
                config('updater.repository_types.github'),
                $this->app->make(ClientInterface::class),
                $this->app->make(UpdateExecutor::class)
            );
        });

        $this->app->bind(HttpRepositoryType::class, function () {
            return new HttpRepositoryType(
                config('updater.repository_types.http'),
                $this->app->make(ClientInterface::class),
                $this->app->make(UpdateExecutor::class)
            );
        });

        $this->app->alias('updater', UpdaterManager::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'updater',
        ];
    }
}
