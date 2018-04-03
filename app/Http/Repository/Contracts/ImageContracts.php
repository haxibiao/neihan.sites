<?php

namespace App\Http\Repository\Contracts;

interface ImageContracts
{
   // select query

   public function all();

   public function find();

   public function findOrFail();

   //update or create

   public function create(array $data);

   public function update(array $data);

   public function delete(array $data);
}