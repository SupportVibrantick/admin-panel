@extends('admin.layout.admin-master')

@section('title', 'Team Genealogy')    

@if(request('seeTree'))
    @dump($treeData)
@endif
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Team Genealogy</li>
                </ol>
            </div>
            <div id="tree"></div>
            
        </div>
    </div>

    <!-- User Profile Modal -->
    <div class="modal fade" id="userProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-center">
                    <div id="profileContent">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 

    <style>    
        #tree{
            width:100%;
            height:70vh;
            overflow:auto;
            border:1px solid #ddd;
            background:#fff;
        }

        .node-box{
            padding:10px;
            border-radius:8px;
            background:#fff;
            border:2px solid #3498db;
            min-width:180px;
            text-align:center;
            box-shadow:0 2px 8px rgba(0,0,0,.1);
        }

        .inactive{
            border-color:#e74c3c;
        }

        .Treant .node {
            cursor:pointer;
            /* position:relative; */
            overflow: visible !important;
        }
        .Treant .collapse-switch {
            width: 24px !important;
            height: 24px !important;
            line-height: 24px !important;
            font-size: 16px !important;
        }
        .Treant .node > .collapse-switch {
            top: auto !important;
            bottom: -10px !important;

            left: 50% !important;
            right: auto !important;

            transform: translateX(-50%) !important;

            width: 20px !important;
            height: 20px !important;

            border-radius: 50%;
            background: #fff !important;
            z-index: 1 !important;
        }

        .empty-node {
            background: #f8f9fa !important;
            border: 2px dashed #ccc !important;
            color: #999 !important;
            min-width: 180px;
            padding: 10px;
            border-radius: 8px;
        }
        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            color: white;
            margin-bottom: 12px;
        }
        .avatar-blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>    
@push('styles')
    
@endpush

@push('scripts')
    <script>
        const mlmData = @json($treeData);

        function convertToTreant(node, isRoot = false)
        {
            if(!node) return null;

            const result = {
                // text: {
                //     name: node.first_name + ' ' + node.last_name,
                //     title: node.is_active ?  'Active' : 'Inactive',
                // },

                innerHTML: `
                    <div class="custom-node text-center" data-user-id="${node.user_id}">

                        <div class="user-avatar avatar-blue mb-2 border mx-auto">
                            ${
                                node.profile_image
                                    ? `
                                        <img
                                            src="${node.profile_image}"
                                            alt="${node.first_name}"
                                            class="avatar-img"
                                        />
                                    `
                                    : `
                                        <div class="avatar-placeholder">
                                            ${node.first_name?.charAt(0).toUpperCase() || ""}
                                            ${node.last_name?.charAt(0).toUpperCase() || ""}
                                        </div>
                                    `
                            }
                        </div>

                        <div class="user-name">
                            ${node.first_name} ${node.last_name}
                            <p class="small m-0">${node.user_name}</p>
                        </div>

                        <div class="mt-1">
                            ${
                                node.is_active
                                    ? `
                                        <span class="badge text-success">
                                            <span class="d-inline-block rounded-circle bg-success me-1"
                                                style="width:8px;height:8px;"></span>
                                            Active
                                        </span>
                                    `
                                    : `
                                        <span class="badge text-danger">
                                            <span class="d-inline-block rounded-circle bg-danger me-1"
                                                style="width:8px;height:8px;"></span>
                                            Inactive
                                        </span>
                                    `
                            }
                        </div>

                    </div>
                `,
                HTMLclass: node.is_active
                    ? "node-box"
                    : "node-box inactive",

                collapsed: !isRoot
            };

            const children = [];

            // LEFT
            if(node.left){
                children.push(convertToTreant(node.left));
            } else {
                children.push({
                    HTMLclass: 'empty-node',
                    // text: {
                    //     name: '',
                    //     title: 'Empty Slot (LS)'
                    // }
                    innerHTML: `
                    <div class="text-center d-flex flex-column justify-content-center" style="height:127px;">
                        <i class="fas fa-user fa-2x text-muted mb-2"></i>
                        <div>Empty Slot (LS)</div>
                        </div>
                    `
                });
            }

            // RIGHT
            if(node.right){
                children.push(convertToTreant(node.right));
            } else {
                children.push({
                    HTMLclass: 'empty-node',
                    // text: {
                    //     name: '',
                    //     title: 'Empty Slot (RS)'
                    // }
                    innerHTML: `                    
                        <div class="text-center d-flex flex-column justify-content-center" style="height:127px;">
                            <i class="fas fa-user fa-2x text-muted mb-2 mx-auto"></i>
                            <div>Empty Slot (RS)</div>
                        </div>
                    `
                });
            }

            result.children = children;

            return result;
        }

        const treeStructure = convertToTreant(mlmData, true);

        new Treant({

            chart: {

                container: "#tree",

                rootOrientation: "NORTH",

                nodeAlign: "CENTER",

                connectors: {
                    type: "step"
                },

                animateOnInit: true,

                animateOnInitDelay: 300,

                scrollbar: "native",

                collapsable: true
            },

            nodeStructure: treeStructure

        });

        $(document).on('click', '.empty-node', function() {
            // Open add member modal
            console.log('Empty slot clicked');
        });
        $(document).on('click', '.custom-node', function() {
            // Open add member modal
            const userId = $(this).data('user-id');
            
            if (userId) {
                openProfileModal(userId);
            }
        });

        function openProfileModal(userId) {
            const modalEl = document.getElementById('userProfileModal');
            const modal = new bootstrap.Modal(modalEl);
            const contentEl = document.getElementById('profileContent');
            
            // Show loading
            contentEl.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading profile...</p>
                </div>`;
            
            modal.show();

            // Fetch modal content
            fetch(`/team-genealogy/user/${userId}/modal`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('HTTP ' + res.status);
                    }
                    return res.text();
                })
                .then(html => {
                    contentEl.innerHTML = html;
                })
                .catch(err => {
                    console.error('Modal error:', err);
                    contentEl.innerHTML = `
                        <div class="alert alert-danger m-3">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Failed to load profile. Please try again.
                        </div>`;
                });
        }      
        
        
    </script>
@endpush