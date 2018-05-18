<?php

namespace App\Http\Repository\Contracts;

interface ArticleContracts
{
    //select query

    public function find($id);

    public function findOrFail($id);

    public function all();

    //update or create

    public function create(array $data);

    public function update(array $data);

    public function delete(array $data);
}
