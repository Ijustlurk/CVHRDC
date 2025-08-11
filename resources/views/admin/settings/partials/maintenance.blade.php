
    <div class="row">
        <div class="col-md-6">


            <div class="card">
                <div class="card-header fw-bold bg-primary text-white">System Maintenance Settings</div>
                <div class="card-body">
                        <form method="POST" action="{{ route('admin.settings.update', 'maintenance') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="maintenance_mode" class="form-label">System Maintenance Mode</label>
                                <select class="form-select" id="maintenance_mode" name="maintenance_mode">
                                    <option value="off" {{ (isset($maintenance) && !$maintenance->active) ? 'selected' : '' }}>Off</option>
                                    <option value="on" {{ (isset($maintenance) && $maintenance->active) ? 'selected' : '' }}>On</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="maintenance_message" class="form-label">Maintenance Message</label>
                                <select class="form-select" id="maintenance_message" name="maintenance_message">
                                    @php
                                        $messages = [
                                            'We are performing scheduled maintenance. Please check back soon.',
                                            'Our team is fixing some issues. Thank you for your patience.',
                                            'The website is being updated for a better experience.',
                                            'Temporary downtime for improvements. We appreciate your understanding.',
                                            'We are currently resolving technical issues. Service will resume shortly.'
                                        ];
                                        $currentMessage = $maintenance->message ?? '';
                                    @endphp
                                    @foreach($messages as $msg)
                                        <option value="{{ $msg }}" {{ $currentMessage == $msg ? 'selected' : '' }}>{{ $msg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Maintenance settings</button>
                        </form>
                </div>
            </div>
           
        </div>
        <div class="col-md-6">          
                <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-left">
                            <h4 class="mb-0 me-3">Current Maintenance Settings</h4>
                        </div>
                        {{-- <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-news-btn">View All News</button> --}}

                    </div>
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
                    <ul class="list-group">
                        <li class="list-group-item bg-light mb-2">
                            <p><strong>Status:</strong> 
                                @if(isset($maintenance) && $maintenance->active)
                                    <span class="badge bg-danger">ON</span>
                                @else
                                    <span class="badge bg-success">OFF</span>
                                @endif
                            </p>
                        </li>
                        <li class="list-group-item bg-light mb-2">
                            <p><strong>Message:</strong> {{ $maintenance->message ?? 'No message set.' }}</p>
                        </li>
                        <li class="list-group-item bg-light mb-2">
                            <p><strong>Last Updated:</strong> {{ $maintenance->updated_at ?? 'N/A' }}</p>
                        </li>
                    </ul>
                   
                    </ul>
        </div>
    </div>


                    <div class="alert alert-info mt-3" id="system-info-alert" style="cursor: pointer;">
                        <strong>System Info:</strong><br>
                          <ul class="system-info-list">
                            <li><b>Purpose:</b> Maintenance mode allows the admin to temporarily disable user access to the website for updates, fixes, or improvements.</li>
                            <li><b>User Side:</b> When maintenance is ON, all user-facing pages display a maintenance message. Users cannot access their dashboards or other features until maintenance is OFF.</li>
                            <li><b>Admin Side:</b> Admins can still access the dashboard and all admin features during maintenance. A warning is shown to admins when maintenance is active.</li>
                            <li><b>Personalized Message:</b> Admins can select a maintenance message to inform users about the reason for downtime.</li>
                        </ul>
                    </div>