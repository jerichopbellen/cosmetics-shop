<?php

namespace App\DataTables;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReviewDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('customer', function($row) {
                return $row->user ? $row->user->name : 'Guest';
            })
            ->addColumn('product_name', function($row) {
                $product = $row->product ? $row->product->name : 'N/A';
                $shade = $row->shade ? ' <span class="badge bg-light text-muted border fw-normal" style="font-size: 0.7rem;">'.$row->shade->shade_name.'</span>' : '';
                return '<div class="fw-bold text-dark">'.$product.'</div>' . $shade;
            })
            ->editColumn('rating', function($row) {
                $stars = '<div class="text-warning text-nowrap" style="font-size: 0.8rem;">';
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= '<i class="' . ($i <= $row->rating ? 'fa-solid' : 'fa-regular') . ' fa-star"></i>';
                }
                return $stars . '</div>';
            })
            ->addColumn('action', function($row) {
                return '
                <div class="d-flex justify-content-center gap-2">
                    <a href="'.route('reviews.show', $row->id).'" class="btn btn-sm btn-outline-secondary" title="View Review">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>';
            })
            ->setRowId('id')
            ->rawColumns(['product_name', 'rating', 'action']);
    }

    public function query(Review $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'product', 'shade']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('reviews-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0, 'desc')
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
            Column::make('id')->width(50),
            Column::computed('customer')->title('Customer'),
            Column::computed('product_name')->title('Product'),
            Column::make('rating')->title('Rating')->addClass('text-center'),
            Column::make('comment')->title('Review Content'),
            Column::make('created_at')->title('Date'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
    }
}