<?php

namespace App\Http\GraphQL\Mutations;


use Illuminate\Support\Arr;

class UserMutators
{
    public function update($root, array $args, $context){
        $user = getUser();
        $user->fill(Arr::except($args,'directive'));
        $user->save();
        return $user;
    }
}