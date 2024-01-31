<?php

namespace EvolutionCMS\Main\Repository\Contracts;

interface CriteriaContract {

    /**
     * Применяет критерий к запросу Eloquent.
     *
     * @param \Illuminate\Database\Eloquent\Model $model Базовый запрос, к которому применяется критерий.
     * @param RepositoryCriteria $repository Репозиторий, в контексте которого используется критерий.
     * @return \Illuminate\Database\Eloquent\Builder Возвращает модифицированный запрос.
     */
    public function apply($model, RepositoryCriteria $repository);
}