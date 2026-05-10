<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\ProyectoRepositoryInterface;
use App\Repositories\Contracts\BloqueRepositoryInterface;
use App\Repositories\Contracts\PiezaRepositoryInterface;
use App\Repositories\Contracts\RegistroFabricacionRepositoryInterface;
use App\Repositories\Eloquent\EloquentProyectoRepository;
use App\Repositories\Eloquent\EloquentBloqueRepository;
use App\Repositories\Eloquent\EloquentPiezaRepository;
use App\Repositories\Eloquent\EloquentRegistroFabricacionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProyectoRepositoryInterface::class, EloquentProyectoRepository::class);
        $this->app->bind(BloqueRepositoryInterface::class, EloquentBloqueRepository::class);
        $this->app->bind(PiezaRepositoryInterface::class, EloquentPiezaRepository::class);
        $this->app->bind(RegistroFabricacionRepositoryInterface::class, EloquentRegistroFabricacionRepository::class);
    }
}
