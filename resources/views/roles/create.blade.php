@extends('layouts.app')

@section('title', 'Manage Role')

@section('content')
    <div class="container">

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST">
                    @csrf
                    @isset($role)
                        @method('PUT')
                    @endisset

                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}" class="form-control"
                            required>
                    </div>

                    <hr>

                    <h5>Permissions</h5>

                    @foreach ($permissions as $group => $perms)
                        <div class="mb-2">
                            <strong>{{ ucfirst($group) }}</strong>
                            <div class="row">
                                @foreach ($perms as $perm)
                                    <div class="col-md-3">
                                        <label>
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                                {{ in_array($perm->id, $assigned ?? []) ? 'checked' : '' }}>
                                            {{ $perm->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <button class="btn btn-primary mt-3">
                        Save Role
                    </button>
                </form>
            </div>
        </div>

    </div>
@endsection
