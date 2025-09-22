@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Overview
                </div>
                <h2 class="page-title">
                   {{ config('app.name', 'Dashboard') }}
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    {{--  --}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="card-title">LIVE CPO STOCK</div>
                            <div class="card-actions btn-actions">
                                <date-input :default="{{ json_encode(request()->all() ?: ['date' => now()->format('Y-m-d')]) }}" />
                            </div>
                        </div>

                        @if($tanks->count() > 2)
                        
                        <div class="row g-0">
                            @foreach($tanks as $tank)
                            <div class="col">
                                <svg-embeded 
                                    path="/svg/storage_single.svg" 
                                    channel="atgs.calculate"
                                    listen="AtgCalculateEvent"
                                    name="{{ $tank->name }}"
                                    :id="'tank-' + {{ $tank->id }}"
                                    :height="450" 
                                    :ratio="450"
                                    :default-data="{{ json_encode($tank) }}" 
                                />
                            </div>
                            @endforeach
                        </div>

                        <div class="row g-0 mt-4">
                            <div class="table-wrapper">
                                <div class="table-scroller">
                                    <table class="table table-sm table-striped card-table table-vcenter">
                                        <thead class="fixed">
                                            <tr>
                                                <th rowspan="2" style="width: 80px;">Hour</th>
                                                @foreach($tanks as $tank)
                                                <th colspan="2" style="text-align: center">{{ $tank->name }}</th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach($tanks as $tank)
                                                <th style="text-align: center">Kg</th>
                                                <th style="text-align: center">Ton</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rows as $row)
                                            <tr class="text-center">
                                                <td style="width: 80px;">{{ timeoftron($row->stock_time) }}</td>
                                                @foreach($tanks as $tank)
                                                @php
                                                    $stock_field = 'stock_st' . $tank->id;
                                                @endphp
                                                <td class="number">{{ number_format($row->{$stock_field}) }}</td>
                                                <td class="number">{{ number_format(($row->{$stock_field} / 1000), 2) }}</td>
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row g-0">
                            <div class="col-md-8">
                                <svg-home 
                                    path="/svg/storage.svg" 
                                    channel="atgs.calculate"
                                    listen="AtgCalculateEvent"
                                    :height="580" 
                                    :ratio="800"
                                />
                            </div>
                            <div class="col-md-4">
                                <div class="table-wrapper">
                                    <div class="table-scroller">
                                        <table class="table table-sm table-striped card-table table-vcenter">
                                            <thead class="fixed">
                                                <tr>
                                                    <th rowspan="2" style="width: 80px;">Hour</th>
                                                    <th colspan="2" style="text-align: center">Storage 01</th>
                                                    <th colspan="2" style="text-align: center">Storage 02</th>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: center">Kg</th>
                                                    <th style="text-align: center">Ton</th>
                                                    <th style="text-align: center">Kg</th>
                                                    <th style="text-align: center">Ton</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rows as $row)
                                                <tr>
                                                    <td style="width: 80px;">{{ timeoftron($row->stock_time) }}</td>
                                                    <td class="number">{{ number_format($row->stock_st1) }}</td>
                                                    <td class="number">{{ number_format(($row->stock_st1 / 1000), 2) }}</td>
                                                    <td class="number">{{ number_format($row->stock_st2) }}</td>
                                                    <td class="number">{{ number_format(($row->stock_st2 / 1000), 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        {{-- <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="card-title">Trend Stock CPO</h3>
                            <div class="ms-auto">
                                <a class="text-secondary" href="#" aria-haspopup="true" aria-expanded="false">Last 30 days</a>
                            </div>
                        </div>
                        <chart start-from="{{ $date }}" :data="{{ json_encode($chartOptions) }}" />
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Current Stock CPO</h3>
                    </div>
                    <table class="table card-table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th>Tank Name</th>
                                <th>Status</th>
                                <th class="number">Level (cm)</th>
                                <th class="number">AVG Temp (&deg;)</th>
                                <th class="number">Stock (Kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tanks as $tank)
                            <tr>
                                <td><a href="{{ route('atg', $tank->id) }}" class="link">{{ $tank->name }}</a></td>
                                <td>
                                    @if($tank->status) 
                                    <span class="badge bg-success me-1"></span> Online 
                                    @else
                                    <span class="badge bg-danger me-1"></span> Offline 
                                    @endif 
                                </td>
                                <td class="number">1600</td>
                                <td class="number">45</td>
                                <td class="number">{{ number_format($tank->stock()?->cpo) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection
