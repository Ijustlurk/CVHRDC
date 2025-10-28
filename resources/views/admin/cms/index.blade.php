@extends('layouts.Admin.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">CMS Dashboard</h1>
    <div class="alert alert-info mb-4">
        Welcome to the CMS section. You can manage your content here.
    </div>
    @include('admin.tabs.index', ['tab' => 'news'])
</div>

@endsection

