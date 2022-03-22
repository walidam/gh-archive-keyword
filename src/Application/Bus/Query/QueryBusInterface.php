<?php

namespace App\Application\Bus\Query;

interface QueryBusInterface
{
    /**
     * @return mixed
     */
    public function ask(QueryInterface $query);
}
