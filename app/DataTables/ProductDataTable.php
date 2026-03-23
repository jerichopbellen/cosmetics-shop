<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('brand', function($row) {
                return $row->brand ? $row->brand->name : 'N/A';
            })
            ->addColumn('category', function($row) {
                return $row->category ? $row->category->name : 'N/A';
            })
            ->addColumn('price_range', function($row) {
                $min = $row->shades->min('price');
                $max = $row->shades->max('price');
                if (!$min) return 'N/A';
                return $min == $max ? '₱' . number_format($min, 2) : '₱' . number_format($min, 2) . ' - ₱' . number_format($max, 2);
            })
            ->addColumn('status', function($row) {
                if ($row->trashed()) {
                    return '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 rounded-pill">Archived</span>';
                }
                return '<span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">Active</span>';
            })
            ->addColumn('action', function($row) {
                if ($row->trashed()) {
                    return '
                    <div class="d-flex justify-content-center">
                        <form action="'.route('products.restore', $row->id).'" method="POST">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-trash-restore me-1"></i> Restore
                            </button>
                        </form>
                    </div>';
                }

                return '
                <div class="d-flex justify-content-center gap-2">
                    <a href="'.route('products.edit', $row->id).'" class="btn btn-sm btn-outline-secondary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="'.route('products.destroy', $row->id).'" method="POST" onsubmit="return confirm(\'Archive this product? It will be hidden from the shop.\')">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Archive">
                            <i class="fas fa-archive"></i>
                        </button>
                    </form>
                </div>';
            })     
            ->setRowId('id')
            ->rawColumns(['action', 'status']);
    }

   public function query(Product $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['brand', 'category', 'shades']);
        
        if ($this->only_trashed) {
            return $query->onlyTrashed();
        }

        return $query; 
}

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reload')
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('Product Name'),
            Column::computed('brand')->title('Brand'),
            Column::computed('category')->title('Category'),
            Column::computed('price_range')->title('Price Range'),
            Column::computed('status')->title('Status')->addClass('text-center'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(160)
                  ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}