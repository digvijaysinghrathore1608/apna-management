<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->bindRepositories();
    }

    private function bindRepositories(): void
    {
        // Path to repositories
        $repositoriesPath = app_path('Repositories');
        $interfacesPath   = app_path('Repositories/Interface');

        if (!File::isDirectory($repositoriesPath)) {
            return;
        }

        // Scan all repository files recursively
        $repositoryFiles = File::allFiles($repositoriesPath);

        foreach ($repositoryFiles as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME); // Example: CustomerRepository
            $interfaceName = $filename . 'Interface';       // Example: CustomerRepositoryInterface

            // Figure out relative namespace (Finance/, HR/, etc.)
            $relativePath = str_replace($repositoriesPath . DIRECTORY_SEPARATOR, '', $file->getPath());
            $namespacePart = str_replace('/', '\\', $relativePath);

            $interfacePath = $interfacesPath . DIRECTORY_SEPARATOR . $relativePath . DIRECTORY_SEPARATOR . $interfaceName . '.php';

            if (File::exists($interfacePath)) {
                $interface = "App\\Repositories\\Interface" . ($namespacePart ? "\\" . $namespacePart : "") . "\\$interfaceName";
                $repository = "App\\Repositories" . ($namespacePart ? "\\" . $namespacePart : "") . "\\$filename";

                $this->app->bind($interface, $repository);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
