@extends('layouts.admin.app')
@section('content')
<div class="container mt-4">
    <h2>System Settings</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <ul class="nav nav-tabs mb-3">
        @foreach($sections as $section)
            <li class="nav-item">
                <a class="nav-link{{ $activeTab == $section ? ' active' : '' }}" href="{{ route('admin.settings.index', ['tab' => $section]) }}">{{ ucfirst($section) }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($sections as $section)
            <div class="tab-pane fade{{ $activeTab == $section ? ' show active' : '' }}" id="{{ $section }}">
                @include('admin.settings.partials.' . $section, ['settings' => $settings[$section] ?? collect()])
            </div>
        @endforeach
    </div>
</div>
@endsection
