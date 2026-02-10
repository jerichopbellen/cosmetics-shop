<?php

namespace App\DataTables;

use App\Models\Brand;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BrandDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function($row) {
                return '
                    <div class="d-flex border-0">
                        <a href="'.route('brands.edit', $row->id).'" class="btn btn-sm btn-warning me-2">Edit</a>
                        
                        <form action="'.route('brands.destroy', $row->id).'" method="POST" onsubmit="return confirm(\'Are you sure?\')">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>';
            })
            ->setRowId('id');
    }

    public function query(Brand $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('brands-table')
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