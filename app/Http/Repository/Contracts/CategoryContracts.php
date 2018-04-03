<?php

namespace App\Http\Repository\Contracts;

interface CategoryContracts
{
    //select query

    public function all();

    public function find($id);

    public function findOrFail($id);

    //update or create

    public function create(array $data);

    public function update(array $data);

    public function delete(array $data);
}
