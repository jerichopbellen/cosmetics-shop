<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($user){
                return '
                <div class="d-flex justify-content-center">
                    <a href="'.route('admin.users.show', $user->id).'" class="btn btn-sm btn-outline-secondary" title="View History">
                        <i class="fa-solid fa-eye me-1"></i> View
                    </a>
                </div>';
            })
            ->editColumn('created_at', fn($user) => $user->created_at->format('M d, Y'))
            ->editColumn('role', function($user) {
                $class = $user->role === 'admin' ? 'bg-primary text-white' : 'bg-light text-dark border';
                return '<span class="badge ' . $class . '" style="font-size: 0.75rem;">' . strtoupper($user->role) . '</span>';
            })
            ->editColumn('is_active', function($user) {
                if ($user->is_active) {
                    return '<span class="badge bg-success-subtle text-success border border-success px-2" style="font-size: 0.7rem;">
                                <i class="fas fa-check me-1"></i> ACTIVE
                            </span>';
                }
                return '<span class="badge bg-dark text-white px-2" style="font-size: 0.7rem;">
                            <i class="fas fa-ban me-1"></i> INACTIVE
                        </span>';
            })
            ->addColumn('total_orders', fn($user) => $user->orders_count ?? 0)
            ->editColumn('lifetime_spent', function($user) {
                return '<span class="fw-bold text-dark">₱' . number_format($user->lifetime_spent ?? 0, 2) . '</span>';
            })
            ->rawColumns(['action', 'role', 'is_active', 'lifetime_spent'])
            ->setRowId('id');
    }

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()
            ->withCount('orders')
            ->withSum(['orderItems as lifetime_spent' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'Delivered');
                })->select(DB::raw('SUM(price * quantity)'));
            }], 'price');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(6, 'desc') 
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(50),
            Column::make('name')->title('Customer'),
            Column::make('email')->title('Email Address'),
            Column::make('role')->title('Role')->addClass('text-center'),
            Column::make('is_active')->title('Status')->addClass('text-center'),
            Column::make('total_orders')->title('Orders')->addClass('text-center'),
            Column::make('lifetime_spent')->title('Lifetime Spent')->addClass('text-end'),
            Column::make('created_at')->title('Joined'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(100)
                  ->addClass('text-center'),
        ];
    }
}