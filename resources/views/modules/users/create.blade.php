@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Manage
                </div>
                <h2 class="page-title">
                    Create User
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('users.index') }}" class="btn btn-default d-none d-sm-inline-block">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-narrow-left">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l14 0" />
                            <path d="M5 12l4 4" />
                            <path d="M5 12l4 -4" />
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form</div>
            </div>
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Name</label>
                    <div>
                        <input type="text" name="name" class="form-control" placeholder="Mr. Joe" value="{{ old('name') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Email</label>
                    <div>
                        <input type="text" name="email" class="form-control" placeholder="example@domain.com" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Password</label>
                    <div>
                        <input type="password" name="password" class="form-control" placeholder="**********">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Confirm Password</label>
                    <div>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="**********">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Role</label>
                    <div>
                        <select class="form-control" name="role">
                            <option value="admin">Administrator</option>
                            <option value="operator">Operator</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-label">Avatar</div>
                    <input type="file" class="form-control" name="avatar">
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary ms-auto">Save</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection