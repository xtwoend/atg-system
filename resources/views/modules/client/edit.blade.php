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
                    Create Client
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('client.index') }}" class="btn btn-default d-none d-sm-inline-block">
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
            <form method="POST" action="{{ route('client.update', $row->id) }}">
                @csrf
                @method('PUT')
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Name</label>
                    <div>
                        <input type="text" name="name" class="form-control" placeholder="Client 01" value="{{ old('name', $row->name) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Key</label>
                    <div>
                        <input type="text" name="key" class="form-control" readonly value="{{ old('key', $row->key) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Secret</label>
                    <div>
                        <input type="text" name="secret" class="form-control" readonly value="{{ old('secret', $row->secret) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-label">Active This Client</div>
                    <label class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" @checked($row->active)>
                        <span class="form-check-label">Yes Actived</span>
                    </label>
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