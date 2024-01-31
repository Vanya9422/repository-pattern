<?php

trait PaginateAbleTrait
{
    /**
     * Получить список записей с пагинацией.
     *
     * Этот метод применяет все добавленные критерии к запросу,
     * выполняет пагинацию результатов и возвращает их.
     *
     * @param int|null $limit Количество записей на страницу. Если не указано, используется стандартное значение.
     * @param array $columns Колонки, которые нужно получить. По умолчанию выбираются все колонки ('*').
     * @param string $method Метод пагинации (paginate или simplePaginate).
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->getModel()->{$method}($limit, $columns);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $results;
    }
}