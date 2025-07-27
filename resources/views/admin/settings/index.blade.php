@extends('layouts.admin.app')
@section('content')

      <style>
            .list-group-item.bg-light {
                transition: background 0.2s, box-shadow 0.2s;
            }
            .list-group-item.bg-light:hover {
                background: #e9ecef;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                cursor: pointer;
            }
        </style>

<div class="container mt-4">

    <div class="d-flex align-items-center mb-4 gap-3">
        <h4 class="mb-0">CVHRDC Website System Settings</h4>
        <div class="alert alert-info mb-0 py-2 px-3">
            Welcome to the Website System Settings section. You can manage your website settings here.
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif





    <ul class="nav nav-tabs mb-3">
        @foreach($sections as $section)
            @if(!in_array($section, ['announcement', 'general_announcement']))
                <li class="nav-item">
                    <a class="nav-link{{ $activeTab == $section ? ' active' : '' }}" href="{{ route('admin.settings.index', ['tab' => $section]) }}">{{ ucfirst($section) }}</a>
                </li>
            @endif
        @endforeach
        <li class="nav-item">
            <a class="nav-link{{ $activeTab == 'database_backup' ? ' active' : '' }}" href="{{ route('admin.settings.index', ['tab' => 'database_backup']) }}">Database Backup</a>
        </li>
    </ul>


    <div class="tab-content">
        @foreach($sections as $section)
            @if(!in_array($section, ['announcement', 'general_announcement']))
                <div class="tab-pane fade{{ $activeTab == $section ? ' show active' : '' }}" id="{{ $section }}">
                    @if($section === 'maintenance')
                        @include('admin.settings.partials.maintenance', ['maintenance' => $maintenance])
                    @else
                        @include('admin.settings.partials.' . $section, ['settings' => $settings[$section] ?? collect()])
                    @endif
                </div>
            @endif
        @endforeach
        <div class="tab-pane fade{{ $activeTab == 'database_backup' ? ' show active' : '' }}" id="database_backup">
            @include('admin.settings.partials.database_backup', ['settings' => isset($settings['database_backup']) && $settings['database_backup'] instanceof \App\Models\DatabaseBackupSetting ? $settings['database_backup'] : (object)['frequency' => 5, 'last_backup' => null]])
        </div>
    </div>

    
</div>



            <style>
                .system-info-list {
                    max-height: 0;
                    opacity: 0;
                    overflow: hidden;
                    transition: max-height 0.4s ease, opacity 0.4s ease;
                }
                #system-info-alert:hover .system-info-list {
                    display: block;
                    max-height: 500px;
                    opacity: 1;
                }
            </style>

@endsection
