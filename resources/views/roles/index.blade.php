<div>
    <!-- Do what you can, with what you have, where you are. - Theodore Roosevelt -->
</div>
@extends('layouts.app')

@section('title', 'Roles')

@section('content')
    <div class="container">

        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex justify-content-between">
                <h4>Roles</h4>

                @can('create', App\Models\Role::class)
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                        + Create Role
                    </a>
                @endcan
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Permissions</th>
                            <th width="160" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            @if ($role->name != 'admin')
                                <tr>
                                    <td><strong>{{ ucfirst($role->name) }}</strong></td>
                                    <td>
                                        @foreach ($role->permissions as $perm)
                                            <span class="badge bg-secondary">{{ $perm->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-end">
                                        @can('update', $role)
                                            <a href="{{ route('roles.edit', $role) }}"
                                                class="btn btn-sm btn-outline-warning">Edit</a>
                                        @endcan

                                        @can('delete', $role)
                                            <form method="POST" action="{{ route('roles.destroy', $role) }}" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $roles->links('pagination::bootstrap-5') }}
        </div>

    </div>
@endsection
