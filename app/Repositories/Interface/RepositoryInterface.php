<?php

namespace App\Repositories\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    /**
     * @param array $data Data value must be in key and value pair structure, ex: params = ['name'=>'John Doe']
     * @return string|object
     */
    public function add(array $data): string|object;

    /**
     * @param array $params Params value must be in key and value pair structure, ex: params = ['name'=>'John Doe']
     * @param array $relations Relations value must be a relation name in array structure, ex: relations = ['product','category']
     * @return Model|null
     */
    public function getFirstWhere(array $params, array $relations = []): ?Model;

    /**
     * @param array $orderBy
     * @param array $relations
     * @param int|string $dataLimit If you need all data without pagination, you need to set dataLimit = 'all'
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;


    /**
     * @param array $orderBy
     * @param string|null $searchValue
     * @param array $filters Filters value must be in key and value pair structure, support one level nested array, ex: Filters = ['category'=>[1,2,5,8], 'email'=>['x@x.com','test@test.com']]
     * @param array $relations
     * @param int|string $dataLimit If you need all data without pagination, you need to set dataLimit = 'all'
     * @param int|null $offset
     * @return Collection|LengthAwarePaginator
     */
    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;


    /**
     * @param string $id
     * @param array $data Data value must be in key and value pair structure, ex: params = ['name'=>'John Doe']
     * @return bool
     */
    public function update(string $id, array $data): bool;

    /**
     * @param array $params
     * @return bool
     */
    public function delete(array $params): bool;

    public function duplicate(array $params): ?Model;
}
