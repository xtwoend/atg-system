@extends('layouts.app')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Automatic Tank Gauging / Overview
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
            <div class="col-12">
                <div class="row row-cards">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database-leak">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" />
                                                <path d="M4 6v12c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-12" />
                                                <path d="M4 15a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-bold">
                                            <span id="cpo_ton">0</span> Ton / <span id="cpo">0</span> Kg
                                        </div>
                                        <div class="text-secondary">
                                            Current Estimate Stock
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-success text-white avatar">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-autofit-height"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 20h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h6" /><path d="M18 14v7" /><path d="M18 3v7" /><path d="M15 18l3 3l3 -3" /><path d="M15 6l3 -3l3 3" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-bold">
                                            <span id="level">0</span> mm
                                        </div>
                                        <div class="text-secondary">
                                            Current Level
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-x text-white avatar">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-temperature"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13.5a4 4 0 1 0 4 0v-8.5a2 2 0 0 0 -4 0v8.5" /><path d="M10 9l4 0" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-bold">
                                            <span id="temp">0</span> &deg; C
                                        </div>
                                        <div class="text-secondary">
                                            Current Average Temperature
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-droplet-half-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13.905 2.923l.098 .135l4.92 7.306a7.566 7.566 0 0 1 1.043 3.167l.024 .326c.007 .047 .01 .094 .01 .143l-.002 .06c.056 2.3 -.944 4.582 -2.87 6.14c-2.969 2.402 -7.286 2.402 -10.255 0c-1.904 -1.54 -2.904 -3.787 -2.865 -6.071a1.052 1.052 0 0 1 .013 -.333a7.66 7.66 0 0 1 .913 -3.176l.172 -.302l4.893 -7.26c.185 -.275 .426 -.509 .709 -.686c1.055 -.66 2.446 -.413 3.197 .55zm-2.06 1.107l-.077 .038l-.041 .03l-.037 .036l-.033 .042l-4.863 7.214a5.607 5.607 0 0 0 -.651 1.61h11.723a5.444 5.444 0 0 0 -.49 -1.313l-.141 -.251l-4.891 -7.261a.428 .428 0 0 0 -.5 -.145z" /></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-bold">
                                            <span id="density">0</span> g/mL
                                        </div>
                                        <div class="text-secondary">
                                            Density 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-8">
                <trend-chart
                    :headers="{{ json_encode($headers) }}"
                    :options="{{ json_encode($chartOptions) }}" 
                    :request="{{ json_encode(request()->all() ?: ['from' => \Carbon\Carbon::now()->subDays(7)->format('Y-m-d'), 'to' => date('Y-m-d')]) }}"
                    :default="{{ json_encode($default) }}"
                    :socket="{{ json_encode($socket) }}"
                    url="{{ route('atg.trend.data', $atg->id) }}"
                    />
            </div>
            <div class="col-md-6 col-lg-4">
                <svg-embeded 
                    path="{{ $atg->svg_path ?? '/svg/storage_single.svg' }}" 
                    channel="atgs.calculate"
                    listen="AtgCalculateEvent"
                    :height="450" 
                    :ratio="450"
                    :default-data="{{ json_encode($atg) }}" 
                    />
            </div>
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

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        window.Echo.channel('atgs.calculate').listen('AtgCalculateEvent', (e) => {
            let data = e.data
            if(data.atg_id == {{ $atg->id }}) {
                console.log(data)
                document.getElementById("cpo").innerHTML = data.cpo.toLocaleString()
                document.getElementById("cpo_ton").innerHTML = data.cpo_ton.toLocaleString()
                document.getElementById("level").innerHTML = parseFloat(data.level).toLocaleString()
                document.getElementById("temp").innerHTML = data.temp_avg.toLocaleString()
                document.getElementById("density").innerHTML = data.density
            }
        })
    });
</script>
@endsection