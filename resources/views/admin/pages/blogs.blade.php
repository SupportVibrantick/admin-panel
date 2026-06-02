@extends("admin.layout.admin-master")
@section("title", "Blogs | Continuity Care")

@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Blogs</li>
                </ol>
            </div>

            <div class="form-head d-flex mb-4 align-items-center justify-content-between">
                <div class="input-group search-area w-25">
                    {{-- <input type="text" id="searchInput" class="form-control" placeholder="Search blogs...">
                    <span class="input-group-text"><i class="flaticon-381-search-2"></i></span> --}}
                </div>
                <button class="btn btn-primary btn-rounded" data-bs-toggle="modal" data-bs-target="#addModal">
                    + Add Blog
                </button>
            </div>

            @if(session('success'))
                <script>
                    Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}', timer: 4000 });
                </script>
            @endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped bg-theme" id="blogTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Published</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blogs as $blog)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($blog->image)
                                                <img src="{{ asset('storage/' . $blog->image) }}" width="60" class="rounded">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                    style="width:60px;height:60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td><strong>{{ Str::limit($blog->title, 40) }}</strong></td>
                                        <td>{{ $blog->author }}</td>
                                       
                                        <td>{{ $blog->published_at?->format('d M Y') ?? '—' }}</td>
                                        <td>
                                            <span
                                                class="badge light badge-{{ $blog->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($blog->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                                                data-bs-target="#view{{ $blog->id }}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $blog->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger light delete-btn"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">No blogs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php $categories = \App\Models\BlogCategory::where('status', 'active')->get(); @endphp

    <!-- Add Modal -->
    <div class="modal fade" id="addModal">
        <div class="modal-dialog custom-modal">
            <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-theme-light">
                        <h5>Add New Blog</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label>Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Blog Title" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>Published By <span class="text-danger">*</span></label>
                                <input type="text" name="author" class="form-control" value="Admin" required>
                            </div>

                            <div class="col-md-6">
                                <label>Categories <span class="text-danger">*</span></label>
                                <select name="category_ids[]" multiple class="form-control" style="height:150px;" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl to select multiple</small>
                            </div>

                            <div class="col-md-3">
                                <label>Publish Date <span class="text-danger">*</span></label>
                                <input type="date" name="published_at" class="form-control" value="{{ date('Y-m-d') }}"
                                    required>
                            </div>

                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Cover Image <span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                            <div class="col-md-8">
                                <label>Quote <span class="text-danger">*</span></label>
                                <input type="text" name="quote" class="form-control" placeholder="Quote" required>
                            </div>

                            <div class="col-12">
                                <label>Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="summernote" required></textarea>
                            </div>
                            <div class="col-12">
                                <label>Benefits <span class="text-danger">*</span></label>
                                <textarea name="benefits" class="summernote" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit & View Modals -->
    @foreach($blogs as $blog)
        <!-- View Modal -->
        <div class="modal fade" id="view{{ $blog->id }}" tabindex="-1">
            <div class="modal-dialog custom-modal modal-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Blog Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Table Layout -->
                    <table class="table table-bordered table-striped mb-0">

                        <tr>
                            <th>Title :</th>
                            <td >{{ $blog->title }}</td>
                             <th>Image :</th>
                            <td >
                                @if($blog->image)
                                    <img src="{{ asset('storage/' . $blog->image) }}" class="img-fluid rounded"
                                        style="max-height:100px;">
                                @else
                                    <em class="text-muted">No image uploaded</em>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Author :</th>
                            <td>{{ $blog->author }}</td>

                            <th>Status :</th>
                            <td>
                                <span class="badge bg-{{ $blog->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($blog->status) }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Published :</th>
                            <td>{{ $blog->published_at?->format('d M Y') ?? '—' }}</td>

                            <th>Categories :</th>
                            <td>
                                @forelse($blog->categories as $cat)
                                    <span class="badge bg-info me-1 mb-1">{{ $cat->name }}</span>
                                @empty
                                    <em class="text-muted">No categories</em>
                                @endforelse
                            </td>
                        </tr>

                       
                        <tr>
                            <th>Description :</th>
                            <td colspan="3">{!! $blog->description !!}</td>
                        </tr>
                        <tr>
                            <th>Benefits :</th>
                            <td colspan="3">{!! $blog->benefits !!}</td>
                        </tr>
                        <tr>
                            <th>Quote :</th>
                             <td colspan="3">{{ $blog->quote }}</td>
                        </tr>

                    </table>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>


        <!-- Edit Modal -->
        <div class="modal fade" id="edit{{ $blog->id }}">
            <div class="modal-dialog custom-modal">
                <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit Blog</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label>Title</label>
                                    <input type="text" name="title" value="{{ $blog->title }}" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Author</label>
                                    <input type="text" name="author" value="{{ $blog->author }}" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label>Categories</label>
                                    <select name="category_ids[]" multiple class="form-control" style="height:150px;" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ in_array($cat->id, $blog->category_ids ?? []) ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Publish Date</label>
                                    <input type="date" name="published_at" value="{{ $blog->published_at?->format('Y-m-d') }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $blog->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $blog->status == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Change Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    @if($blog->image)
                                        <img src="{{ asset('storage/' . $blog->image) }}" width="100" class="mt-2 rounded">
                                    @endif
                                </div>
 <div class="col-md-8">
                                    <label>Quote</label>
                                    <input type="text" name="quote" value="{{ $blog->quote }}" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label>Description</label>
                                    <textarea name="description" class="summernote">{!! $blog->description !!}</textarea>
                                </div>
                                <div class="col-12">
                                    <label>Benefits</label>
                                    <textarea name="benefits" class="summernote">{!! $blog->benefits !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        $(function () {
            $('.summernote').summernote({
                placeholder: 'Write blog content here...',
                height: 300
            });

            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $("#blogTable tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('.delete-btn').click(function (e) {
                e.preventDefault();
                let form = $(this).closest('form');
                Swal.fire({
                    title: 'Delete Blog?',
                    text: "This Blog will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endpush