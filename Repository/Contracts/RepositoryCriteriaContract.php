<?php

namespace EvolutionCMS\Main\Repository\Contracts;

/**
 * Interface RepositoryCriteriaInterface
 * Определяет методы для управления критериями в репозитории.
 */
interface RepositoryCriteriaContract {

    /**
     * Добавляет критерий в репозиторий.
     *
     * @param CriteriaContract $criteria Критерий для добавления.
     * @return mixed
     */
    public function pushCriteria(CriteriaContract $criteria);

    /**
     * Удаляет критерий из репозитория.
     *
     * @param CriteriaContract $criteria Критерий для удаления.
     * @return mixed
     */
    public function popCriteria(CriteriaContract $criteria);

    /**
     * Получает коллекцию всех критериев.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCriteria();

    /**
     * Применяет критерий к репозиторию и возвращает результат.
     *
     * @param CriteriaContract $criteria Критерий для применения.
     * @return mixed
     */
    public function getByCriteria(CriteriaContract $criteria);

    /**
     * Пропускает применение критериев.
     *
     * @param bool $status Статус пропуска.
     * @return mixed
     */
    public function skipCriteria($status = true);

    /**
     * Сбрасывает все критерии в репозитории.
     *
     * @return mixed
     */
    public function resetCriteria();
}