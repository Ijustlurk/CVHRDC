
<div class="row">
    <div class="col-md-6">


        <div class="card">
            <div class="card-header fw-bold bg-primary text-white">Footer Settings</div>
            <div class="card-body">

                <form method="POST" action="/admin/footer/update">
                            @csrf
                            @php $footer = \App\Models\Footer::first(); @endphp
                            <div class="mb-3">
                                <label for="org_name" class="form-label">Organization Name</label>
                                <input type="text" class="form-control @error('org_name') is-invalid @enderror" id="org_name" name="org_name" value="{{ old('org_name', $footer->org_name ?? '') }}" required>
                                @error('org_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" rows="6" id="description" name="description" required>{{ old('description', $footer->description ?? '') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $footer->address ?? '') }}" required>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $footer->email ?? '') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $footer->phone ?? '') }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $footer->website ?? '') }}" required>
                                @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="copyright" class="form-label">Copyright</label>
                                <textarea class="form-control @error('copyright') is-invalid @enderror" id="copyright" name="copyright" required>{{ old('copyright', $footer->copyright ?? '') }}</textarea>
                                @error('copyright')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Save Footer settings</button>
                        </form>

            </div>
        </div>
       



       
    </div>
    <div class="col-md-6">





        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-left">
                <h4 class="mb-0 me-3">Current Footer Settings</h4>
            </div>
            {{-- <button class="btn btn-outline-primary btn-sm align-items-right" id="view-all-news-btn">View All News</button> --}}

        </div>
  
        <ul class="list-group">
            <li class="list-group-item bg-light mb-2">
                <p><strong>Organization Name:</strong> {{ $footer->org_name ?? '' }} </p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Copyright:</strong> {{ $footer->copyright ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Description:</strong> {{ $footer->description ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Address:</strong> {{ $footer->address ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Email:</strong> {{ $footer->email ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Phone:</strong> {{ $footer->phone ?? '' }}</p>
            </li>
            <li class="list-group-item bg-light mb-2">
                <p><strong>Website:</strong> {{ $footer->website ?? '' }}</p>
            </li>
        </ul>
        

  
    
    </div>
</div>

                <div class="alert alert-info mt-3" id="system-info-alert" style="cursor: pointer;">
                    <strong>System Info:</strong><br>
                    <ul class="system-info-list">
                        <li><b>Purpose:</b> Footer settings allow you to manage the organization’s contact information, description, and copyright details shown on every page.</li>
                        <li><b>User Side:</b> The footer provides users with essential information about the organization, including how to contact or visit.</li>
                        <li><b>Admin Side:</b> Admins can update footer details to ensure users always see the latest and most accurate information.</li>
                        <li><b>Branding:</b> The footer helps reinforce the organization’s identity and professionalism across the website.</li>
                    </ul>
                </div>