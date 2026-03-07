<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
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
                return '<a href="'.route('admin.orders.show', $row->id).'" class="btn btn-sm btn-dark">View</a>';
            })
            ->editColumn('user_id', function($row) {
                return $row->user->name . '<br><small class="text-muted">' . $row->user->email . '</small>';
            })
            ->editColumn('status', function($row) {
                $class = match($row->status) {
                    'Pending'   => 'bg-warning text-dark',
                    'Delivered' => 'bg-success',
                    'Cancelled' => 'bg-danger',
                    default     => 'bg-primary'
                };
                return '<span class="badge '.$class.'">'.$row->status.'</span>';
            })
            ->editColumn('total_amount', fn($row) => '₱' . number_format($row->total_amount, 2)) // Changed to Pesos to match your dashboard
            ->editColumn('created_at', fn($row) => $row->created_at->format('Y-m-d H:i'))
            ->rawColumns(['user_id', 'status', 'action'])
            ->setRowId('id');
    }

    public function query(Order $model): QueryBuilder
    {
        $query = $model->newQuery()->with('user')->select('orders.*');

        // Apply Status Filter
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('order-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax('', 'data.status = $("#status-filter").val();') // Passes the dropdown value to the query
                    ->orderBy(4)
                    ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('order_number')->title('Order #'),
            Column::make('user_id')->title('Customer'),
            Column::make('total_amount')->title('Total'),
            Column::make('status'),
            Column::make('created_at')->title('Date'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }
}