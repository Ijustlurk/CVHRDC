@extends('layouts.admin.app')
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">CVHRDC Content Managment System</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Welcome to the CMS section. You can manage your content here.
        </div>
    </div>
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link{{ $tab == 'news' ? ' active' : '' }}" href="{{ route('admin.tabs.index', ['tab' => 'news']) }}">News</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $tab == 'projects' ? ' active' : '' }}" href="{{ route('admin.tabs.index', ['tab' => 'projects']) }}">Projects</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $tab == 'events' ? ' active' : '' }}" href="{{ route('admin.tabs.index', ['tab' => 'events']) }}">Events</a>
        </li>
    <!-- Publications and Researchers tabs removed as requested -->
        <li class="nav-item">
            <a class="nav-link{{ $tab == 'partners' ? ' active' : '' }}" href="{{ route('admin.tabs.index', ['tab' => 'partners']) }}">Partners</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $tab == 'announcements' ? ' active' : '' }}" href="{{ route('admin.tabs.index', ['tab' => 'announcements']) }}">Announcements</a>
        </li>
    </ul>
    <div>
        @if($tab == 'news')
            @include('admin.tabs.partials.news')
        @elseif($tab == 'projects')
            @include('admin.tabs.partials.projects')
        @elseif($tab == 'events')
            @include('admin.tabs.partials.events')
        @elseif($tab == 'publications')
            @include('admin.tabs.partials.publications')
        @elseif($tab == 'researchers')
            @include('admin.tabs.partials.researchers')
        @elseif($tab == 'partners')
            @include('admin.tabs.partials.partners')
        @elseif($tab == 'announcements')
            @include('admin.tabs.partials.announcement')
        @endif
    </div>
</div>






@endsection
