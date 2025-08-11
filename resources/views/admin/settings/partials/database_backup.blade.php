<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header fw-bold bg-primary text-white">Database Backup Settings</div>
            <div class="card-body">
                <form method="POST" action="/admin/settings/database-backup">
                    @csrf
                    <div class="mb-3">
                        <label for="frequency" class="form-label">Backup Frequency (days)</label>
                        <input type="number" class="form-control" id="frequency" name="frequency" value="{{ old('frequency', $settings->frequency ?? 5) }}" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </form>
            </div>
        </div>





    </div>
    <div class="col-md-6">
       
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Current Database Backup Settings</h4>
            </div>
            {{-- <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-news-btn">View All News</button> --}}

        </div>

                <ul class="list-group">
                    <li class="list-group-item bg-light mb-2">
                        <p><strong>Last Backup Performed:</strong> {{ $settings->last_backup ?? 'Never' }}
                    </li>
                    <li class="list-group-item bg-light mb-2">
                        <p><strong>Backup Frequency:</strong> Every {{ $settings->frequency ?? 5 }} days</p>
                    </li>
                    @if(isset($backups) && count($backups))
                        <li class="list-group-item bg-light mb-2">
                            <strong>Backup Files:</strong>
                            <ul>
                                @foreach($backups as $backup)
                                    <li>
                                        <span>{{ $backup['name'] }}</span><br>
                                        <small class="text-muted">{{ $backup['path'] }}</small><br>
                                        <a href="{{ route('admin.settings.database_backup.download', ['file' => $backup['name']]) }}" class="btn btn-sm btn-outline-success mt-1">Download</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="list-group-item bg-light mb-2">
                            <strong>Backup files:</strong>
                             <small class="text-muted">Backups will appear here once created.</small>


                            @php
                                $backupFiles = [];
                                $backupPath = storage_path('app/db-backups');
                                if (is_dir($backupPath)) {
                                    $files = scandir($backupPath);
                                    foreach ($files as $file) {
                                        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                                            $backupFiles[] = $file;
                                        }
                                    }
                                }
                            @endphp

                            @if(count($backupFiles))
                                <ul>
                                    @foreach($backupFiles as $file)
                                        <li>
                                            <span>{{ $file }}</span><br>
                                            <small class="text-muted">{{ $backupPath . '/' . $file }}</small><br>
                                            <a href="{{ route('admin.settings.database_backup.download', ['file' => $file]) }}" class="btn btn-sm btn-outline-success mt-1">Download</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <small class="text-muted">No backup files available. Backups will appear here once created.</small>
                            @endif
                           
                        </li>
                    @endif
                </ul>
                <form method="POST" action="/admin/settings/database-backup/perform" class="mt-3">
                    @csrf                    <button type="submit" class="btn btn-warning">Perform Manual Backup Now</button>
                </form>
              
         
    </div>
</div>



            
            <div class="alert alert-info mt-3" id="system-info-alert" style="cursor: pointer;">
                <strong>System Info:</strong><br>
                <ul class="system-info-list">
                    <li><b>Purpose:</b> Automatically backs up the database every set number of days.</li>
                    <li><b>Manual Backup:</b> You can trigger a backup manually if needed.</li>
                    <li><b>Last Backup:</b> Shows when the last backup was performed.</li>
                </ul>
            </div>

      