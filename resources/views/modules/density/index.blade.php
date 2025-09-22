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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-database-leak">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" />
                        <path d="M4 6v12c0 1.657 3.582 3 8 3s8 -1.343 8 -3v-12" />
                        <path d="M4 15a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1" />
                    </svg>
                    Density Table
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                
                <div class="btn-list">
                    <div class="dropdown d-none d-sm-inline">
                        @php
                           $atgId = request()->input('atg_id', 1); 
                           $a = \App\Models\Atg::find($atgId);
                        @endphp
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $a?->name }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-demo">
                            <h6 class="dropdown-header">Filter By</h6>
                            @foreach(\App\Models\Atg::all() as $atg)
                            <a href="{{ route('atgsetting.table-density.index', ['atg_id' => $atg->id]  + request()->all()) }}" class="dropdown-item">{{ $atg->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    @if(Auth::user()->role == 'admin')
                    <a href="{{ route('atgsetting.table-density.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cloud-upload">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1" />
                            <path d="M9 15l3 -3l3 3" />
                            <path d="M12 12l0 9" />
                        </svg>
                        Upload
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tank</th>
                            <th class="text-end">Temperature</th>
                            <th class="text-end">Density</th>
                            <th class="text-end">FK</th>
                            <th class="text-end">Updated At</th>
                            {{-- <th class="text-end"></th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $item)  
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->atg->name }}</td>
                            <td class="text-end">{{ number_format($item->temperature) }}</td>
                            <td class="text-end">{{ number_format($item->density, 6) }}</td>
                            <td class="text-end">{{ $item->fk }}</td>
                            <td class="text-end">{{ $item->created_at }}</td>
                            {{-- <td class="text-end">
                                <a href="{{ route('atgsetting.table-volume.edit', $item->id) }}">Edit</a> | <a href="#" onclick="event.preventDefault(); document.getElementById('del-{{$item->id}}').submit();"> Delete <a>
                                <form id="del-{{$item->id}}" action="{{ route('atgsetting.table-volume.destroy', $item->id) }}" method="POST" class="d-none">
                                    @method("DELETE")
                                    @csrf
                                </form>
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                {{ $rows->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection