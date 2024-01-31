<?php

namespace App\Providers;

use EvolutionCMS\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider {

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        /**
         * Регистрирует привязки в сервис-контейнере.
         */
        $this->bindProviders();
    }

    /**
     * Регистрирует привязки в сервис-контейнере.
     *
     * @return void
     */
    public function bindProviders()
    {
        // Получаем массив привязок из нашего файла конфигурации 'core/custom/packages/main/config/repository.php'.
        // Этот массив содержит соответствия интерфейсов их конкретным реализациям.
        $repositories = config('repository.bindings');

        // Проходимся по массиву привязок. Ключ массива - это интерфейс,
        // а значение массива - это класс, который реализует этот интерфейс.
        foreach ($repositories as $interface => $implementation) {
            // Регистрируем привязку в сервис-контейнере Laravel.
            // Это позволяет Laravel внедрять соответствующие реализации
            // когда типизированный интерфейс запрашивается (например, в конструкторе).
            $this->app->bind($interface, $implementation);
        }
    }
}
