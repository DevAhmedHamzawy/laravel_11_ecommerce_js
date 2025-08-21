@extends('admin.layouts.master')

@section('title')
    {{ trans('dashboard.activity_logs') }}
@endsection

@section('content')
    <div class="col-xl-12 col-md-12 col-lg-12">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">{{ trans('dashboard.dashboard') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ trans('dashboard.activity_logs') }}</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header pb-1">
                <h3 class="card-title mb-2">{{ trans('dashboard.activity_logs') }}</h3>
            </div>
            <div class="product-timeline card-body pt-2 mt-1">
                <ul class="timeline-1 mb-0">
                    @foreach ($activityLogs as $log)
                        <li class="mt-0"> <i class="ti-pie-chart bg-primary-gradient text-white product-icon"></i> <span
                                class="font-weight-semibold mb-4 tx-14 ">Log</span> <a href="#"
                                class="float-right tx-11 text-muted">{{ $log->created_at->diffForHumans() }}</a>
                            <p class="mb-0 text-muted tx-12">{{ $log->description }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
