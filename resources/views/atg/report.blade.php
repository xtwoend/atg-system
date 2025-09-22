@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Automatic Tank Gauging
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
                    <div class="dropdown d-none d-sm-inline">
                        @php
                            $modes = [
                                'daily' => 'Daily',
                                'monthly' => 'Monthly',
                                'yearly' => 'Yearly'
                            ];  
                        @endphp
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $modes[request()->input('mode', 'daily')] }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-demo">
                            <h6 class="dropdown-header">Report Mode</h6>
                            <a href="{{ route('atg.report', ['mode' => 'daily', 'id' => $atg->id]  + request()->all()) }}" class="dropdown-item">Daily</a>
                            <a href="{{ route('atg.report', ['mode' => 'monthly', 'id' => $atg->id] + request()->all()) }}" class="dropdown-item">Monthly</a>
                            {{-- <a href="{{ route('atg.report', ['mode' => 'yearly', 'id' => $atg->id] + request()->all()) }}" class="dropdown-item">Yearly</a> --}}
                        </div>
                    </div>
                    <date-range mode="{{ request()->input('mode', 'daily') }}" :default="{{ json_encode(request()->all() ?: ['from' => \Carbon\Carbon::now()->subDays(7)->format('Y-m-d'), 'to' => date('Y-m-d')]) }}" />
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="card">
                    <report-chart
                        title=""
                        :headers="{{ json_encode($headers) }}"
                        :options="{{ json_encode($chartOptions) }}" 
                        :request="{{ json_encode(request()->all() ?: ['from' => \Carbon\Carbon::now()->subDays(7)->format('Y-m-d'), 'to' => date('Y-m-d')]) }}"
                        :default="{{ json_encode($default) }}"
                        url="{{ route('atg.stock.trend', $atg->id) }}"
                    />
                </div>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <data-table 
                    title="Report"
                    :headers="{{ json_encode($headers) }}" 
                    url="{{ route('atg.stock.data', ['id' => $atg->id]) }}" 
                    :request="{{ json_encode(request()->all() ?: ['from' => \Carbon\Carbon::now()->subDays(7)->format('Y-m-d'), 'to' => date('Y-m-d')]) }}" 
                    download-url="{{ route('atg.stock.download', ['id' => $atg->id] +request()->all()) }}"
                    /></data-table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection