<?php

namespace App\Application\UI\Http\Bus\Query;

interface QueryBusInterface
{
    /**
     * @return mixed
     */
    public function ask(QueryInterface $query);
}