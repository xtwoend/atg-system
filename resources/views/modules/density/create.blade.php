@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Table Tank
                </div>
                <h2 class="page-title">
                    Density Table Upload
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('atgsetting.table-density.index') }}" class="btn btn-default d-none d-sm-inline-block">
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
                <div class="card-title">Upload Density Table</div>
                <div class="card-actions">
                    <a href="{{ asset('density-template.xlsx') }}?time={{ time() }}" class="btn">
                      <!-- Download SVG icon from http://tabler-icons.io/i/phone -->
                      <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-cloud-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" /><path d="M12 13l0 9" /><path d="M9 19l3 3l3 -3" /></svg>
                      Download Excel Template
                    </a>
                </div>
            </div>
            <form method="POST" action="{{ route('atgsetting.table-density.store') }}" enctype="multipart/form-data">
                @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">ATG Tank</label>
                    <div>
                        <select class="form-select" name="atg_id">
                            @foreach($atgs as $atg)
                            <option value="{{ $atg->id }}">{{ $atg->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-label">Select Volume Table (xlsx)</div>
                    <input type="file" name="file" class="form-control">
                </div>
            </div>
            <div class="card-footer text-end">
                <div class="d-flex">
                    <button type="submit" class="btn btn-primary ms-auto">Upload</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection