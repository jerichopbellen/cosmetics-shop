<?php

namespace App\DataTables;

use App\Models\Category;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function($row) {
                return '
                <div class="d-flex">
                    <a href="'.route('categories.edit', $row->id).'" class="btn btn-sm btn-warning me-2">Edit</a>
                    <form action="'.route('categories.destroy', $row->id).'" method="POST" onsubmit="return confirm(\'Are you sure?\')">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>';
            })
            ->rawColumns(['action']);
    }

    public function query(Category $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('category-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }
}