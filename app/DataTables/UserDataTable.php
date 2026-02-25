<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($user){
                return '<a href="'.route('admin.users.show', $user->id).'" class="btn btn-sm btn-dark text-white shadow-sm">
                            <i class="fa-solid fa-eye me-1"></i> View History
                        </a>';
            })
            ->editColumn('created_at', fn($user) => $user->created_at->format('M d, Y'))
            ->editColumn('role', function($user) {
                $class = $user->role === 'admin' ? 'bg-danger' : 'bg-secondary';
                return '<span class="badge ' . $class . '">' . strtoupper($user->role) . '</span>';
            })
            ->addColumn('total_orders', fn($user) => $user->orders->count())
            ->rawColumns(['action', 'role']);
    }

    public function query(User $model)
    {
        // We eager load orders count to keep the table fast
        return $model->newQuery()->withCount('orders');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns([
                ['data' => 'id', 'title' => 'ID'],
                ['data' => 'name', 'title' => 'Name'],
                ['data' => 'email', 'title' => 'Email'],
                ['data' => 'total_orders', 'title' => 'Total Orders', 'orderable' => false],
                ['data' => 'created_at', 'title' => 'Joined'],
                ['data' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false],
                ['data' => 'role', 'title' => 'Role'],
            ])
            ->minifiedAjax()
            ->orderBy(0);
    }
}