@extends('admin.layout.admin-master')

@section('title', 'My Profile')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">My Profile</li>
                </ol>
            </div>

            <!-- Success Messages -->
            @if(session('status') === 'profile-updated')
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Profile updated successfully!',
                        timer: 4000,
                        timerProgressBar: true,
                        });
                </script>
            @endif

            @if(session('status') === 'password-updated')
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Password changed successfully!',
                        timer: 4000,
                        timerProgressBar: true,
                    });
                </script>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-primary">
                                <i class="fa fa-user-circle me-2"></i> My Profile
                            </h4>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" checked disabled>
                                <label class="form-check-label fw-bold text-success">Active Account</label>
                            </div>
                        </div>

                        <div class="card-body">
                            <ul class="nav nav-tabs mb-4" id="profileTabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#overview">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#edit-profile">Edit Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#change-password">Change Password</a>
                                </li>
                            </ul>

                            <div class="tab-content border border-top-0 rounded-bottom p-4 ">

                                <!-- Overview Tab -->
                                <div class="tab-pane fade show active" id="overview">
                                    <div class="row align-items-center g-5 p-3">
                                        <div class="col-lg-4 text-center">
                                            <div class="position-relative d-inline-block">
                                                <img src="{{ Auth::user()->profile_photo_path
                                                        ? asset('storage/' . Auth::user()->profile_photo_path)
                                                        : asset('images/profile/profile.png') }}" class="rounded-circle shadow-lg border  border-white"
                                                        width="180" height="180" style="object-fit: cover;" alt="Profile">
                                                <span
                                                    class="position-absolute bottom-0 end-0 badge bg-success rounded-pill px-3 py-2">Active</span>
                                            </div>
                                            <h4 class="mt-4">{{ Auth::user()->name }}</h4>
                                            <p class="text-muted">Administrator</p>
                                            <p class="text-muted small">Joined {{ Auth::user()->created_at->format('M Y') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-8 card p-4 shadow">
                                            <h5 class="fw-bold mb-3">Personal Information</h5>
                                            <hr>
                                            <div class="row g-3">
                                                <div class="col-sm-4"><strong>Name :</strong></div>
                                                <div class="col-sm-8">{{ Auth::user()->name }}</div>

                                                <div class="col-sm-4"><strong>Email :</strong></div>
                                                <div class="col-sm-8">{{ Auth::user()->email }}</div>

                                                <div class="col-sm-4"><strong>Phone :</strong></div>
                                                <div class="col-sm-8">{{ Auth::user()->phone ?? 'Not set' }}</div>

                                                <div class="col-sm-4"><strong>Bio :</strong></div>
                                                <div class="col-sm-8">{{ Auth::user()->bio ?? 'No bio added.' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Profile Tab -->
                                <div class="tab-pane fade" id="edit-profile">
                                    <form action="{{ route('profile.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')

                                        <div class="row g-4">
                                            <div class="col-lg-8">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Full Name *</label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="{{ old('name', Auth::user()->name) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Email Address *</label>
                                                        <input type="email" name="email" placeholder="enter email address" class="form-control"
                                                            value="{{ old('email', Auth::user()->email) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Phone Number</label>
                                                        <input type="text" name="phone" placeholder="Enter phone number" class="form-control"
                                                            value="{{ old('phone', Auth::user()->phone) }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold">Bio / About Me</label>
                                                        <textarea name="bio" rows="5"
                                                            class="form-control summernote">{{ old('bio', Auth::user()->bio) }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 text-center">
                                                <label class="form-label fw-bold d-block">Profile Photo</label>
                                                <div class="preview-box border rounded-3 p-4 bg-white mb-3">
                                                    <img src="{{ Auth::user()->profile_photo_path
                                                        ? asset('storage/' . Auth::user()->profile_photo_path)
                                                        : asset('images/profile/profile.png') }}" class="rounded-circle shadow-lg border  border-white"
                                                        width="180" height="180" style="object-fit: cover;" alt="Profile">
                                                </div>
                                                <input type="file" name="photo" class="form-control" accept="image/*"
                                                    onchange="previewImage(event)">

                                                <small class="text-muted">Recommended: 300×300px • Max 2MB</small>
                                            </div>
                                        </div>

                                        <div class=" mt-5">
                                            <button type="submit" class="btn btn-success btn-lg px-6">
                                                Update 
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Change Password Tab -->
                                <div class="tab-pane fade" id="change-password">
                                    <div class="row ">
                                        <div class="col-lg-7">
                                            <form action="{{ route('password.update') }}" method="POST">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Current Password</label>
                                                    <input type="password" placeholder="Enter Current Password" name="current_password" class="form-control"
                                                        required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">New Password</label>
                                                    <input type="password" placeholder="Enter New Password" name="password" class="form-control" required>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">Confirm New Password</label>
                                                    <input type="password" placeholder=" Confirm Password" name="password_confirmation" class="form-control"
                                                        required>
                                                </div>

                                                <div class="">
                                                    <button type="submit" class="btn btn-primary btn-lg px-6">
                                                        Update 
                                                    </button>
                                                </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Image Preview
        function previewImage(event) {
            const output = document.getElementById('previewImg');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = () => URL.revokeObjectURL(output.src);
        }

        $(document).ready(function () {
            $('.summernote').summernote({
                height: 180,
                placeholder: 'your bio description.........',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['height', ['height']]
                ]
            });
        });
    </script>
@endpush