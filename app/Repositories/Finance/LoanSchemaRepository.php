<?php

namespace App\Repositories\Finance;

use App\Models\Finance\LoanSchema;
use App\Repositories\Interface\Finance\LoanSchemaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Yajra\DataTables\DataTables;

class LoanSchemaRepository implements LoanSchemaRepositoryInterface
{
    public function __construct(
        private readonly LoanSchema   $model,
    ) {}
    public function getDataTable(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->model->newQuery();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('actions', function ($row) {
                    $editUrl   = route('microfinance.schema.edit', $row->id);
                    $deleteUrl = route('microfinance.schema.destroy', $row->id);
                    return '
                                <a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="' . $deleteUrl . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this group?\')">
                                    ' . csrf_field() . '
                                    ' . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            ';
                })
                ->rawColumns(columns: ['actions'])
                ->make(true);
        }

        return null;
    }

    public function add(array $data): string|object
    {
        return $this->model->create($data);
    }

    public function delete(array $params): bool
    {
        $this->model->where($params)->delete();
        return true;
    }

    public function getFirstWhere(array $params, array $relations = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model->where($params)->with($relations)->first();
    }

    public function getList(
        array $orderBy = [],
        array $relations = [],
        int|string $dataLimit = DEFAULT_DATA_LIMIT,
        int $offset = null
    ): Collection|LengthAwarePaginator {

        $query = $this->model->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });

        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(
        array $orderBy = [],
        string $searchValue = null,
        array $filters = [],
        array $relations = [],
        int|string $dataLimit = DEFAULT_DATA_LIMIT,
        int $offset = null
    ): Collection|LengthAwarePaginator {

        $query = $this->model->where($filters)->with($relations)
            ->when($searchValue, function ($query) use ($searchValue) {
                $query->where('title', 'like', "%$searchValue%");
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });
        $filters += ['searchValue' => $searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function update(string $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }
}
