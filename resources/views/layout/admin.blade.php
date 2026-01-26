<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/admin.css'])

</head>

<body>

    <div class="events-container">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 20px;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="header-actions">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search organizations...">
                <i class="bi bi-search"></i>
            </div>

            <button class="btn-create-org" onclick="openCreateOrgModal()">
                <i class="bi bi-plus-circle"></i>
                Create Organization
            </button>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <button class="btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>Logout
            </button>
        </div>

        <!-- Organizations Section -->
        <div class="organizations-section" style="margin-top: 30px">
            <h2 class="section-title">
                <i class="bi bi-building"></i> Organizations
            </h2>
            <div class="org-grid" id="organizationsGrid">
                @foreach($organizations as $org)
                <div class="org-card" data-org-name="{{ strtolower($org->org_name) }}" onclick="openEditOrgModal('{{ $org->org_id }}')">
                    <div class="org-logo">{{ $org->short_name }}</div>
                    <div class="org-name">{{ $org->org_name }}</div>
                    <div class="org-description">{{ $org->description ?? 'No description available' }}</div>
                    <div class="org-meta">
                        <div><i class="bi bi-calendar3"></i> Est. {{ $org->year }}</div>
                        <div><i class="bi bi-person"></i> Adviser: {{ $org->adviser_name ?? 'Not assigned' }}</div>
                    </div>
                    <span class="org-status {{ strtolower($org->status) }}">{{ strtoupper($org->status) }}</span>
                </div>
                @endforeach

                @if(count($organizations) === 0)
                <p style="text-align: center; color: #666; padding: 40px; grid-column: 1 / -1;">No organizations found.</p>
                @endif
            </div>
        </div>

        <!-- Create Organization Modal -->
        <div class="modal-overlay" id="createOrgModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Create New Organization</h3>
                    <button class="modal-close" onclick="closeModal('createOrgModal')">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <form id="createOrgForm" action="{{ route('admin.organization.create') }}" method="POST" onsubmit="confirmCreateOrg(event)">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Organization Name *</label>
                            <input type="text" class="form-control" name="org_name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Adviser School Number</label>
                            <input type="text" class="form-control" name="adviser_school_number">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Adviser Name</label>
                            <input type="text" class="form-control" name="adviser_name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select class="form-select" name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" onclick="closeModal('createOrgModal')">Cancel</button>
                        <button type="submit" class="btn-primary">Create Organization</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Organization Modal -->
        <div class="modal-overlay" id="editOrgModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Edit Organization</h3>
                    <button class="modal-close" onclick="closeModal('editOrgModal')">
                        <i class="bi bi-x"></i>
                    </button>
                </div>

                <form id="editOrgForm" method="POST" onsubmit="confirmUpdateOrg(event)">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="org_id" id="editOrgId">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Organization Name *</label>
                            <input type="text" class="form-control" name="org_name" id="editOrgName" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editOrgDescription" rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Adviser School Number</label>
                            <input type="text" class="form-control" name="adviser_school_number" id="editOrgAdviserSchoolNo">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Adviser Name</label>
                            <input type="text" class="form-control" name="adviser_name" id="editOrgAdviserName">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status *</label>
                            <select class="form-select" name="status" id="editOrgStatus" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" onclick="closeModal('editOrgModal')">Cancel</button>
                        <button type="submit" class="btn-primary">Update Organization</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirmation Modal for Create -->
        <div class="modal-overlay confirmation-modal" id="confirmCreateModal">
            <div class="modal-content">
                <div class="confirmation-body">
                    <i class="bi bi-exclamation-triangle warning-icon"></i>
                    <h4>Create Organization?</h4>
                    <p>Are you sure you want to create this organization?</p>
                    <div class="confirmation-actions">
                        <button class="btn-secondary" onclick="closeModal('confirmCreateModal')">Cancel</button>
                        <button class="btn-primary" onclick="submitCreateOrg()">Yes, Create</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal for Update -->
        <div class="modal-overlay confirmation-modal" id="confirmUpdateModal">
            <div class="modal-content">
                <div class="confirmation-body">
                    <i class="bi bi-exclamation-triangle warning-icon"></i>
                    <h4>Update Organization?</h4>
                    <p>Are you sure you want to update this organization's details?</p>
                    <div class="confirmation-actions">
                        <button class="btn-secondary" onclick="closeModal('confirmUpdateModal')">Cancel</button>
                        <button class="btn-primary" onclick="submitUpdateOrg()">Yes, Update</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const organizationsData = {
            @foreach($organizations as $org)
            '{{ $org->org_id }}': {
                id: '{{ $org->org_id }}',
                name: '{{ $org->org_name }}',
                short_name: '{{ $org->short_name }}',
                description: `{!! addslashes($org->description ?? '') !!}`,
                year: {{ $org->year }},
                adviser_school_number: '{{ $org->adviser_school_id ?? '' }}',
                adviser_name: '{{ $org->adviser_name ?? '' }}',
                status: '{{ $org->status }}'
            },
            @endforeach
        };

        // SEARCH FUNCTIONALITY
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const orgCards = document.querySelectorAll('.org-card');

            orgCards.forEach(card => {
                const orgName = card.getAttribute('data-org-name');
                card.style.display = orgName.includes(searchTerm) ? 'block' : 'none';
            });
        });

        // MODAL CONTROLS
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // ORGANIZATION LOGIC
        function openCreateOrgModal() {
            document.getElementById('createOrgForm').reset();
            document.getElementById('createOrgModal').classList.add('show');
        }

        function openEditOrgModal(orgId) {
            const org = organizationsData[orgId];

            if (org) {
                document.getElementById('editOrgId').value = org.id;
                document.getElementById('editOrgName').value = org.name;
                document.getElementById('editOrgDescription').value = org.description || '';
                document.getElementById('editOrgAdviserSchoolNo').value = org.adviser_school_number || '';
                document.getElementById('editOrgAdviserName').value = org.adviser_name || '';
                document.getElementById('editOrgStatus').value = org.status === 'Active' ? 'Active' : 'Inactive';
                document.getElementById('editOrgForm').action = '/admin/organization/' + org.id + '/update';

                document.getElementById('editOrgModal').classList.add('show');
            } else {
                alert('Organization not found');
            }
        }

        function confirmCreateOrg(event) {
            event.preventDefault();
            document.getElementById('confirmCreateModal').classList.add('show');
        }

        function submitCreateOrg() {
            closeModal('confirmCreateModal');
            document.getElementById('createOrgForm').removeEventListener('submit', confirmCreateOrg);
            document.getElementById('createOrgForm').submit();
        }

        function confirmUpdateOrg(event) {
            event.preventDefault();
            document.getElementById('confirmUpdateModal').classList.add('show');
        }

        function submitUpdateOrg() {
            closeModal('confirmUpdateModal');
            document.getElementById('editOrgForm').removeEventListener('submit', confirmUpdateOrg);
            document.getElementById('editOrgForm').submit();
        }

        // GLOBAL EVENT LISTENERS
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('show');
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
