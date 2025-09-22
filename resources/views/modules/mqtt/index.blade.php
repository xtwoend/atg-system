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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10.657 23.994h-9.45A1.21 1.21 0 0 1 0 22.788v-9.18h.071c5.784 0 10.504 4.65 10.586 10.386m7.606 0h-4.045C14.135 16.246 7.795 9.977 0 9.942V6.038h.071c9.983 0 18.121 8.044 18.192 17.956m4.53 0h-.97C21.754 12.071 11.995 2.407 0 2.372v-1.16C0 .55.544.006 1.207.006h7.64C15.733 2.49 21.257 7.789 24 14.508v8.291c0 .663-.544 1.195-1.207 1.195M16.713.006h6.092A1.19 1.19 0 0 1 24 1.2v5.914c-.91-1.242-2.046-2.65-3.158-3.762C19.588 2.11 18.122.987 16.714.005Z"/></svg> MQTT Connection
                </h2>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                @if(Auth::user()->role == 'admin')
                <div class="btn-list">
                    <a href="{{ route('connections.mqtt.reload') }}" class="btn btn-default d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-refresh">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                        </svg>
                        Reload
                    </a>
                    <a href="{{ route('connections.mqtt.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Add Connection
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
                            <th>Name</th>
                            <th>Host</th>
                            <th>Port</th>
                            <th>Status</th>
                            <th>Error</th>
                            <th>Added At</th>
                            @if(Auth::user()->role == 'admin')
                            <th class="text-end"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->host }}</td>
                            <td>{{ $item->port }}</td>
                            <td>
                                @if($item->status) 
                                <span class="badge bg-success me-1"></span>  Connected 
                                @else
                                <span class="badge bg-danger me-1"></span>  Disconnected 
                                @endif
                            </td>
                            <td>{{ $item->error_message }}</td>
                            <td>{{ $item->created_at }}</td>
                            @if(Auth::user()->role == 'admin')
                            <td class="text-end">
                                @if(! $item->status)
                                <a href="{{ route('connections.mqtt.start', $item->id) }}">Start</a> | 
                                @else
                                <a href="{{ route('connections.mqtt.stop', $item->id) }}">Stop</a> | 
                                @endif
                                <a href="{{ route('connections.mqtt.edit', $item->id) }}">Edit</a> 
                                {{-- | <a href="#" onclick="event.preventDefault(); document.getElementById('del-{{$item->id}}').submit();"> Delete <a> --}}
                                {{-- <form id="del-{{$item->id}}" action="{{ route('connections.mqtt.destroy', $item->id) }}" method="POST" class="d-none">
                                    @method("DELETE")
                                    @csrf
                                </form> --}}
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