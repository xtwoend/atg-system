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
                    Add Tank
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('atgsetting.tank.index') }}" class="btn btn-default d-none d-sm-inline-block">
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
                <div class="card-title">Tank Form</div>
            </div>
            <form method="POST" action="{{ route('atgsetting.tank.store') }}" enctype="multipart/form-data">
                @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label required">Name</label>
                    <div>
                        <input type="text" name="name" class="form-control" placeholder="Tank 01">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Location</label>
                    <div>
                        <input type="text" name="location" class="form-control" placeholder="Site 01 - Palangkaraya">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Capacity</label>
                    <div>
                        <input type="text" name="capacity" class="form-control" placeholder="18000">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Factor Correction</label>
                    <div>
                        <input type="text" name="factor_correction" class="form-control" placeholder="0.0000348">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Temperature Sounding</label>
                    <div>
                        <input type="text" name="temperature" class="form-control" placeholder="0.0000348">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Time of Sounding</label>
                    <div>
                        <input type="time" name="sounding_time" class="form-control" min="00:00" max="23:00" placeholder="07:00">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-label">Mimic SVG</div>
                    <input type="file" class="form-control" name="svg_path">
                </div>
                <hr>
                <h4>Equipment Sensor</h4>
                <div class="mb-3">
                    <label class="form-label">Device Registered</label>
                    <div>
                        <select class="form-select" name="device_id">
                            @foreach($devices as $device)
                            <option value="{{ $device->id }}">{{ $device->serial_number }}</option>
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