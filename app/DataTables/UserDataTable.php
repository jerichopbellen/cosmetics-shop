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
                $class = $user->role === 'admin' ? 'bg-pink text-white' : 'bg-light text-dark border';
                return '<span class="badge ' . $class . '" style="font-size: 0.75rem;">' . strtoupper($user->role) . '</span>';
            })
            // --- ADDED STATUS COLUMN ---
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
            // Add 'is_active' to rawColumns so the HTML renders
            ->rawColumns(['action', 'role', 'is_active']);
    }

    public function getColumns(): array
    {
        return [
            ['data' => 'id', 'title' => 'ID', 'width' => '50px'],
            ['data' => 'name', 'title' => 'Customer Name'],
            ['data' => 'email', 'title' => 'Email Address'],
            ['data' => 'role', 'title' => 'Role', 'addClass' => 'text-center'],
            // --- ADDED TO COLUMNS ARRAY ---
            ['data' => 'is_active', 'title' => 'Status', 'addClass' => 'text-center'],
            ['data' => 'total_orders', 'title' => 'Orders', 'addClass' => 'text-center'],
            ['data' => 'created_at', 'title' => 'Joined'],
            ['data' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'addClass' => 'text-center'],
        ];
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
}