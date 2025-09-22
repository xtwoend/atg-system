@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Automatic Tank Gauging / Data Logger
                </div>
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database-leak">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" />
                        <path d="M4 6v12c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-12" />
                        <path d="M4 15a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1" />
                    </svg>
                    {{ $atg->name }} - {{ $atg->location }}
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <date-range :default="{{ json_encode(request()->all() ?: ['from' => \Carbon\Carbon::now()->subDays(7)->format('Y-m-d'), 'to' => date('Y-m-d')]) }}" />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-md-12 col-lg-12">
                <data-table
                    :headers="{{ json_encode($headers) }}" 
                    url="{{ route('atg.data', $atg->id) }}" 
                    :request="{{ json_encode(request()->all() ?: ['from' => \Carbon\Carbon::now()->subDays(7)->format('Y-m-d'), 'to' => date('Y-m-d')]) }}" 
                    download-url="{{ route('atg.data.download', ['id' => $atg->id, 'from' => request()->input('from', \Carbon\Carbon::now()->subDays(7)->format('Y-m-d')), 'to' => request()->input('to', date('Y-m-d')), 'time' => time()]) }}"
                    /></data-table>
            </div>
        </div>
    </div>
</div>
@endsection