<?php

namespace Tests\Feature\GraphQL;

use App\Chat;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryTest extends TestCase
{
    use DatabaseTransactions;
    /* --------------------------------------------------------------------- */
    /* ------------------------------- Mutation ----------------------------- */
    /* --------------------------------------------------------------------- */

    /* --------------------------------------------------------------------- */
    /* ------------------------------- Query ----------------------------- */
    /* --------------------------------------------------------------------- */
    public function testcategoriesQuery()
    {
        $query = file_get_contents(__DIR__ . '/Category/Query/categoriesQuery.gql');

        //hot分类
        $variables = [
            'filter'=> "hot" ,
        ];

        $this->startGraphQL($query, $variables);

        //other分类 
        $variables = [
            'filter'=> "other" ,
        ];
        
        $this->startGraphQL($query, $variables);
    }
}
