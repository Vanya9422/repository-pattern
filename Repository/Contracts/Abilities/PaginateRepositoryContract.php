<?php

namespace EvolutionCMS\Main\Repository\Contracts\Abilities;

interface PaginateRepositoryContract
{
    /**
     * Получить записи с пагинацией.
     * Этот метод применяет критерии, если они были добавлены к репозиторию, выполняет пагинацию результатов и возвращает их.
     * Пагинация позволяет разбить набор данных на "страницы", облегчая управление большим количеством записей.
     *
     * @param int $limit Количество записей на страницу.
     * @param array $columns Колонки, которые нужно получить.
     *
     */
    public function paginate($limit = null, $columns = ['*']);
}