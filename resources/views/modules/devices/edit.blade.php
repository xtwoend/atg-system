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
                    Add Device
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('devices.index') }}" class="btn btn-default d-none d-sm-inline-block">
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
                <div class="card-title">Device Form</div>
            </div>
            <form method="POST" action="{{ route('devices.update', $row->id) }}">
                @csrf
                @method('PUT')
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Serial Number</label>
                    <div>
                        <input type="text" name="serial_number" class="form-control" placeholder="Serial Number" value="{{ old('serial_number', $row->serial_number) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Manufaktur</label>
                    <div>
                        <input type="text" name="manufacture" class="form-control" placeholder="Manufaktur" value="{{ old('manufacture', $row->manufacture) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Model</label>
                    <div>
                        <input type="text" name="model" class="form-control" placeholder="model" value="{{ old('model', $row->model) }}">
                    </div>
                </div>
                <hr>
                <h4>Connection Settings</h4>
                <div class="mb-3">
                    <label class="form-label">MQTT Connection</label>
                    <div>
                        <select class="form-select" name="connection_id">
                            @foreach($connections as $conn)
                            <option value="{{ $conn->id }}" @if($conn->id == $row->connection_id) selected @endif>{{ $conn->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">MQTT Topic</label>
                    <div>
                        <input type="text" name="topic" class="form-control" placeholder="mqtt/data/device/1" value="{{ old('topic', $row->topic) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message Handler</label>
                    <div>
                        <select class="form-select" name="handler">
                            @foreach(config('handlers') as $key => $handler)
                            <option value="{{ $handler }}" @if($handler == $row->handler) selected @endif>{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
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