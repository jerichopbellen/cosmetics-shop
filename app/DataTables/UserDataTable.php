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
                return '
                <div class="d-flex justify-content-center">
                    <a href="'.route('admin.users.show', $user->id).'" class="btn btn-sm btn-outline-secondary" title="View History">
                        <i class="fa-solid fa-eye me-1"></i> View
                    </a>
                </div>';
            })
            ->editColumn('created_at', fn($user) => $user->created_at->format('M d, Y'))
            ->editColumn('role', function($user) {
                // Using a softer pink-themed badge for admin to match the site
                $class = $user->role === 'admin' ? 'bg-pink text-white' : 'bg-light text-dark border';
                return '<span class="badge ' . $class . '" style="font-size: 0.75rem;">' . strtoupper($user->role) . '</span>';
            })
            // Using the eager-loaded count directly
            ->addColumn('total_orders', fn($user) => $user->orders_count ?? 0)
            ->rawColumns(['action', 'role']);
    }

    public function query(User $model)
    {
        // Eager loading counts to prevent N+1 issues
        return $model->newQuery()->withCount('orders');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0);
    }

    public function getColumns(): array
    {
        return [
            ['data' => 'id', 'title' => 'ID', 'width' => '50px'],
            ['data' => 'name', 'title' => 'Customer Name'],
            ['data' => 'email', 'title' => 'Email Address'],
            ['data' => 'role', 'title' => 'Role', 'addClass' => 'text-center'],
            ['data' => 'total_orders', 'title' => 'Orders', 'addClass' => 'text-center'],
            ['data' => 'created_at', 'title' => 'Joined'],
            ['data' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'addClass' => 'text-center'],
        ];
    }
}