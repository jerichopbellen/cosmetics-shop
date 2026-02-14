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
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('brand', function($row) {
                return $row->brand ? $row->brand->name : 'N/A';
            })
            ->addColumn('category', function($row) {
                return $row->category ? $row->category->name : 'N/A';
            })
            // Display the count of gallery images
            ->addColumn('gallery_count', function($row) {
                $count = $row->images->count();
                return '<span class="badge bg-info">' . $count . ' Photos</span>';
            })
            // Display price range from shades
            ->addColumn('price_range', function($row) {
                $min = $row->shades->min('price');
                $max = $row->shades->max('price');
                if (!$min) return 'N/A';
                return $min == $max ? '$' . number_format($min, 2) : '$' . number_format($min, 2) . ' - $' . number_format($max, 2);
            })
            ->addColumn('action', function($row) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="'.route('products.edit', $row->id).'" class="btn btn-sm btn-warning me-2 text-white">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="'.route('products.destroy', $row->id).'" method="POST" onsubmit="return confirm(\'Delete this product and all associated shades/images?\')">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>';
            })
            ->setRowId('id')
            ->rawColumns(['gallery_count', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        // Eager load everything: brands, categories, shades, AND the new gallery images
        return $model->newQuery()->with(['brand', 'category', 'shades', 'images']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0) // Order by ID descending
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('Product Name'),
            Column::make('finish')->title('Finish'),
            Column::computed('brand')->title('Brand'),
            Column::computed('category')->title('Category'),
            Column::computed('gallery_count')->title('Gallery'), // New Column
            Column::computed('price_range')->title('Price Range'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(160)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}