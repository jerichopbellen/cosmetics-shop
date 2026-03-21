<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($row) {
                return '
                <div class="d-flex justify-content-center">
                    <a href="'.route('admin.orders.show', $row->id).'" class="btn btn-sm btn-outline-secondary" title="View">
                        <i class="fa-solid fa-eye me-1"></i> View
                    </a>
                </div>';
            })
            ->editColumn('user_id', function($row) {
                $name = $row->user->name ?? 'Guest';
                $email = $row->user->email ?? '';
                return '<div>'. $name .'</div><small class="text-muted">' . $email . '</small>';
            })
            ->editColumn('status', function($row) {
                $class = match($row->status) {
                    'Pending'   => 'bg-warning text-dark',
                    'Delivered' => 'bg-success text-white',
                    'Cancelled' => 'bg-danger text-white',
                    default     => 'bg-secondary text-white'
                };
                return '<span class="badge '.$class.' px-2 py-1" style="font-size: 0.75rem; font-weight: 500;">' . strtoupper($row->status) . '</span>';
            })
            // This reads the 'total_amount' alias created in the query() method below
            ->editColumn('total_amount', fn($row) => '₱' . number_format($row->total_amount, 2))
            ->editColumn('created_at', fn($row) => $row->created_at->format('M d, Y'))
            ->rawColumns(['user_id', 'status', 'action'])
            ->setRowId('id');
    }

    public function query(Order $model): QueryBuilder
    {
        /* FIX: We use 'orderItems' here because that is the name of 
           the relationship function in your Order Model.
        */
        return $model->newQuery()
            ->with('user')
            ->select('orders.*')
            ->withSum(['orderItems as total_amount' => function($query) {
                $query->select(DB::raw('SUM(price * quantity)'));
            }], 'price');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('order-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax('', 'data.status = $("#status-filter").val();')
                    ->orderBy(4)
                    ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('order_number')->title('Order #'),
            Column::make('user_id')->title('Customer'),
            
            // We set searchable to false because SQL cannot search 
            // a calculated SUM without a complex HAVING clause.
            Column::make('total_amount')
                ->title('Total')
                ->searchable(false)
                ->orderable(true),

            Column::make('status')->addClass('text-center'),
            Column::make('created_at')->title('Date'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(100)
                  ->addClass('text-center'),
        ];
    }
}