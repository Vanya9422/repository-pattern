<?php

namespace EvolutionCMS\Main\Repository;

use EvolutionCMS\Main\Exceptions\RepositoryException;
use EvolutionCMS\Main\Repository\Contracts\CriteriaContract;
use EvolutionCMS\Main\Repository\Contracts\RepositoryCriteria;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Базовый класс репозитория, который определяет основные операции,
 * которые должны быть реализованы в наследуемых репозиториях.
 */
abstract class BaseRepository implements RepositoryCriteria {

    /**
     * @var Application Контейнер приложения, используется для резолва моделей и других зависимостей.
     */
    protected $app;

    /**
     * @var Model|null Экземпляр модели, с которой работает репозиторий.
     */
    protected $model = null;

    /**
     * @var Collection Коллекция объектов критериев, которые могут быть применены к запросу.
     */
    protected $criteria;

    /**
     * @var bool Флаг, указывающий, следует ли пропускать применение критериев к запросу.
     */
    protected $skipCriteria = false;

    /**
     * @param Application $app
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Application $app) {
        $this->app = $app;
        $this->criteria = new Collection();
        $this->makeModel();
        $this->boot();
    }

    /**
     * Метод для инициализации дополнительной логики после создания репозитория.
     * Может использоваться для добавления начальных критериев фильтрации,
     * которые должны применяться ко всем запросам репозитория.
     */
    public function boot() {
        // Добавьте здесь вашу логику инициализации.
        // Например:
        // $this->pushCriteria(new YourInitialCriteria());
    }

    /**
     * Returns the current Model instance
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Сбрасывает экземпляр модели, создавая новый экземпляр.
     * @return void
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function resetModel() {
        $this->makeModel();
    }

    /**
     * Возвращает имя класса модели.
     * Этот метод должен быть реализован в каждом конкретном репозитории.
     *
     * @return string Название класса модели.
     */
    abstract protected function getModelClass();

    /**
     * Создает и возвращает экземпляр модели.
     * @return Model
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeModel() {
        $model = $this->app->make($this->getModelClass());

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->getModelClass()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }

    /**
     * Начинает новый запрос к базе данных, возвращая построитель запросов для модели.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    protected function startQuery() {
        return $this->getModel()->newQuery();
    }

    /**
     * Добавляет критерий в коллекцию.
     *
     * @param CriteriaContract $criteria Критерий, который нужно добавить.
     * @return $this
     */
    public function pushCriteria(CriteriaContract $criteria) {
        $this->criteria->push($criteria);
        return $this;
    }

    /**
     * Удаляет критерий из коллекции.
     *
     * @param CriteriaContract $criteria Критерий, который нужно удалить.
     * @return $this
     */
    public function popCriteria(CriteriaContract $criteria)
    {
        $this->criteria = $this->criteria->reject(function ($item) use ($criteria) {
            return get_class($item) === get_class($criteria);
        });

        return $this;
    }

    /**
     * Возвращает коллекцию критериев.
     *
     * @return Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Применяет критерий к модели и возвращает результат.
     *
     * @param CriteriaContract $criteria Критерий, который нужно применить.
     * @return $this
     */
    public function getByCriteria(CriteriaContract $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        return $this;
    }

    /**
     * Устанавливает статус пропуска критериев.
     *
     * @param bool $status Если true, критерии применены не будут.
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;
        return $this;
    }

    /**
     * Сбрасывает все критерии в коллекции.
     *
     * @return $this
     */
    public function resetCriteria()
    {
        $this->criteria = new Collection();
        return $this;
    }

    /**
     * Применяет все критерии к текущему запросу.
     *
     * @return $this
     */
    protected function applyCriteria()
    {
        if ($this->skipCriteria === true) return $this;

        foreach ($this->criteria as $criteria)
            if ($criteria instanceof CriteriaContract)
                $this->model = $criteria->apply($this->model, $this);

        return $this;
    }
}