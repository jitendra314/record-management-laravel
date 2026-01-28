@extends('layouts.app')

@section('title', isset($role) ? 'Edit Role' : 'Create Role')

@section('content')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    {{ isset($role) ? 'Edit Role' : 'Create Role' }}
                </h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}">

                    @csrf

                    @isset($role)
                        @method('PUT')
                    @endisset

                    {{-- Role Name --}}
                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}"
                            class="form-control @error('name') is-invalid @enderror">

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    {{-- Permissions --}}
                    <h6 class="mb-3">Permissions</h6>

                    @foreach ($permissions as $group => $perms)
                        <div class="border rounded p-3 mb-3">
                            <strong class="text-capitalize">{{ $group }}</strong>

                            <div class="row mt-2">
                                @foreach ($perms as $perm)
                                    <div class="col-md-3 col-sm-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                value="{{ $perm->id }}" id="perm_{{ $perm->id }}"
                                                {{ in_array($perm->id, $assigned ?? []) ? 'checked' : '' }}>

                                            <label class="form-check-label" for="perm_{{ $perm->id }}">
                                                {{ $perm->label ?? $perm->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('roles.index') }}" class="btn btn-light">
                            Cancel
                        </a>

                        <button class="btn btn-primary">
                            {{ isset($role) ? 'Update Role' : 'Create Role' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
