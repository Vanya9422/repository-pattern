<?php

namespace EvolutionCMS\Main\Repository\Ships;

use EvolutionCMS\Main\Repository\BaseRepository;
use EvolutionCMS\Main\Repository\Contracts\Abilities\PaginateRepositoryContract;
use EvolutionCMS\Main\Traits\PaginateAbleTrait;

class ShipsScheduleRepository extends BaseRepository implements ShipsScheduleRepositoryContract,
    PaginateRepositoryContract
{
    use PaginateAbleTrait;

    /**
     * Получает имя класса модели.
     *
     * @return string
     */
    protected function getModelClass() {
        return \ShipsModule\models\ShipsSchedules::class;
    }
}