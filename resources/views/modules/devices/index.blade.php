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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-devices">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M13 9a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1v-10z" />
                        <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h9" />
                        <path d="M16 9h2" />
                    </svg>
                    Devices
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                @if(Auth::user()->role == 'admin')
                <div class="btn-list">
                    <a href="{{ route('devices.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Add New Device
                    </a>
                </div>
                @endif
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
                            <th>Serial Number</th>
                            <th>Model</th>
                            <th>Manufaktur</th>
                            <th>Connection</th>
                            <th>Status</th>
                            <th>Last Message At</th>
                            <th>Added At</th>
                            @if(Auth::user()->role == 'admin')
                            <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->serial_number }}</td>
                            <td>{{ $item->model }}</td>
                            <td>{{ $item->manufacture }}</td>
                            <td>{{ $item->connection?->name }}</td>
                            <td>
                                @if($item->status) 
                                <span class="badge bg-success me-1"></span>  Connected 
                                @else
                                <span class="badge bg-danger me-1"></span>  Disconnected 
                                @endif
                            </td>
                            <td>{{ $item->last_connected }}</td>
                            <td>{{ $item->created_at }}</td>
                            @if(Auth::user()->role == 'admin')
                            <td class="text-end">
                                <a href="{{ route('devices.edit', $item->id) }}">Edit</a> | <a href="#" onclick="event.preventDefault(); document.getElementById('del-{{$item->id}}').submit();"> Delete <a>
                                <form id="del-{{$item->id}}" action="{{ route('devices.destroy', $item->id) }}" method="POST" class="d-none">
                                    @method("DELETE")
                                    @csrf
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-secondary">Showing <span> {{($rows->currentPage()-1) * $rows->perPage()+($rows->total() ? 1:0)}}</span> to <span>{{($rows->currentPage()-1) * $rows->perPage() + count($rows)}}</span> of <span>{{ $rows->total() }}</span> entries</p>
                {{ $rows->links() }}
            </div>
        </div>
    </div>
</div>
@endsection