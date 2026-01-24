<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --pup-maroon: #800000;
            --pup-gold: #FFD700;
            --pup-dark: #4a0000;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }


        .events-container {
            padding: 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: #800000;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
            /* This ensures buttons and search box line up perfectly */
            justify-content: flex-end;
            /* Optional: keeps them on the right side */
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 40px 10px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            width: 300px;
            font-size: 14px;
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .btn-create-org {
            background: #800000;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-create-org:hover {
            background: #600000;
        }

        /* Organizations Section */
        .organizations-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 22px;
            font-weight: bold;
            color: #800000;
            margin-bottom: 20px;
            border-bottom: 3px solid #800000;
            padding-bottom: 10px;
        }

        .org-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .org-card {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .org-card:hover {
            border-color: #800000;
            box-shadow: 0 4px 12px rgba(128, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .org-logo {
            width: 60px;
            height: 60px;
            background: #800000;
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .org-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .org-description {
            font-size: 13px;
            color: #666;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .org-meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 12px;
            color: #555;
        }

        .org-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 10px;
        }

        .org-status.active {
            background: #d4edda;
            color: #155724;
        }

        .org-status.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        /* Events Section */
        .events-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .events-grid {
            display: grid;
            gap: 20px;
            margin-top: 20px;
        }

        .event-card {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px;
            background: white;
            position: relative;
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .event-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .event-badge.upcoming {
            background: #fff3cd;
            color: #856404;
        }

        .event-badge.ended {
            background: #d1ecf1;
            color: #0c5460;
        }

        .event-menu {
            position: relative;
        }

        .event-menu-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
            padding: 5px 10px;
        }

        .event-menu-btn:hover {
            color: #800000;
        }

        .event-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 100;
            min-width: 150px;
        }

        .event-dropdown.show {
            display: block;
        }

        .event-dropdown button {
            width: 100%;
            padding: 10px 15px;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
            color: #dc3545;
            font-weight: 500;
        }

        .event-dropdown button:hover {
            background: #f8f9fa;
        }

        .event-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .event-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .event-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        .event-detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #555;
            font-size: 14px;
        }

        .event-detail-item i {
            color: #800000;
        }

        .event-org-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .event-org-logo {
            width: 30px;
            height: 30px;
            background: #800000;
            color: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        /* Modal Overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 0;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: #800000;
            color: white;
            padding: 20px 25px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 22px;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #800000;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-primary {
            background: #800000;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #600000;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Confirmation Modal */
        .confirmation-modal .modal-content {
            max-width: 450px;
        }

        .confirmation-body {
            padding: 30px;
            text-align: center;
        }

        .warning-icon {
            font-size: 60px;
            color: #ffc107;
            margin-bottom: 20px;
        }

        .confirmation-body h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .confirmation-body p {
            color: #666;
            margin-bottom: 25px;
        }

        .confirmation-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .no-data i {
            font-size: 60px;
            margin-bottom: 15px;
            color: #ddd;
        }


        body {
            margin: 0;
            padding: 0;
        }

        .dashboard {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 0;
            background: #f5f5f5;
        }


        .btn-logout {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease;
        }

        .btn-logout:hover {
            background: #5a6268;
        }

        .btn-logout i {
            font-size: 1.1rem;
        }

        .btn-create-org,
        .btn-logout,
        .search-box input {
            height: 42px;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>

    <div class="events-container">
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
                <!-- Organization Card 1 -->
                <div class="org-card" data-org-name="google developer groups on campus – pup" onclick="openEditOrgModal(1)">
                    <div class="org-logo">GDG</div>


                    <div class="org-name">Google Developer Groups on Campus – PUP</div>
                    <div class="org-description">An organization is a group of people who work together, like a neighborhood association, a charity, a union, or a corporation.</div>
                    <div class="org-meta">
                        <div><i class="bi bi-calendar3"></i> Est. 2018</div>
                        <div><i class="bi bi-person"></i> Adviser: Josef Karol A. Velayo</div>
                    </div>
                    <span class="org-status active">ACTIVE</span>
                </div>

                <!-- Organization Card 2 -->
                <div class="org-card" data-org-name="amazon web services – pup" onclick="openEditOrgModal(2)">
                    <div class="org-logo">AWS</div>
                    <div class="org-name">Amazon Web Services – PUP</div>
                    <div class="org-description">Learn cloud computing and modern infrastructure with AWS technologies and tools.</div>
                    <div class="org-meta">
                        <div><i class="bi bi-calendar3"></i> Est. 2020</div>
                        <div><i class="bi bi-person"></i> Adviser: John Doe</div>
                    </div>
                    <span class="org-status active">ACTIVE</span>
                </div>

                <!-- Organization Card 3 -->
                <div class="org-card" data-org-name="microsoft student partners – pup" onclick="openEditOrgModal(3)">
                    <div class="org-logo">MSP</div>
                    <div class="org-name">Microsoft Student Partners – PUP</div>
                    <div class="org-description">Empowering students through technology and innovation with Microsoft tools and platforms.</div>
                    <div class="org-meta">
                        <div><i class="bi bi-calendar3"></i> Est. 2019</div>
                        <div><i class="bi bi-person"></i> Adviser: Jane Smith</div>
                    </div>
                    <span class="org-status inactive">INACTIVE</span>
                </div>
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
                <form id="createOrgForm" onsubmit="confirmCreateOrg(event)">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Organization Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Short Name *</label>
                            <input type="text" class="form-control" name="short_name" maxlength="10" required>
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
                                <option value="ACTIVE">Active</option>
                                <option value="INACTIVE">Inactive</option>
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
                <form id="editOrgForm" onsubmit="confirmUpdateOrg(event)">
                    <input type="hidden" name="org_id" id="editOrgId">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Organization Name *</label>
                            <input type="text" class="form-control" name="name" id="editOrgName" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Short Name *</label>
                            <input type="text" class="form-control" name="short_name" id="editOrgShortName" maxlength="10" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editOrgDescription" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Year Established *</label>
                            <input type="number" class="form-control" name="year" id="editOrgYear" min="1900" max="2100" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Institute</label>
                            <input type="text" class="form-control" name="institute" id="editOrgInstitute">
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
                                <option value="ACTIVE">Active</option>
                                <option value="INACTIVE">Inactive</option>
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

        <!-- Confirmation Modal for Delete Event -->
        <div class="modal-overlay confirmation-modal" id="confirmDeleteModal">
            <div class="modal-content">
                <div class="confirmation-body">
                    <i class="bi bi-exclamation-triangle warning-icon"></i>
                    <h4>Delete Event?</h4>
                    <p id="deleteEventMessage">Are you sure you want to delete this event?</p>
                    <div class="confirmation-actions">
                        <button class="btn-secondary" onclick="closeModal('confirmDeleteModal')">Cancel</button>
                        <button class="btn-danger" onclick="submitDeleteEvent()">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>


        @push('scripts')
        <script>
            // GLOBAL STATE
            let currentEventId = null;

            // Hardcoded organization data
            const organizationsData = {
                1: {
                    id: 1,
                    name: 'Google Developer Groups on Campus – PUP',
                    short_name: 'GDG',
                    description: 'An organization is a group of people who work together, like a neighborhood association, a charity, a union, or a corporation.',
                    year: 2018,
                    institute: 'Institute of Bachelors in Information Technology Studies',
                    adviser_school_number: 'ADV-001',
                    adviser_name: 'Josef Karol A. Velayo',
                    status: 'ACTIVE'
                },
                2: {
                    id: 2,
                    name: 'Amazon Web Services – PUP',
                    short_name: 'AWS',
                    description: 'Learn cloud computing and modern infrastructure with AWS technologies and tools.',
                    year: 2020,
                    institute: 'Institute of Bachelors in Information Technology Studies',
                    adviser_school_number: 'ADV-002',
                    adviser_name: 'John Doe',
                    status: 'ACTIVE'
                },
                3: {
                    id: 3,
                    name: 'Microsoft Student Partners – PUP',
                    short_name: 'MSP',
                    description: 'Empowering students through technology and innovation with Microsoft tools and platforms.',
                    year: 2019,
                    institute: 'Institute of Bachelors in Information Technology Studies',
                    adviser_school_number: 'ADV-003',
                    adviser_name: 'Jane Smith',
                    status: 'INACTIVE'
                }
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
                document.getElementById('createOrgModal').classList.add('show');
            }

            function openEditOrgModal(orgId) {
                const org = organizationsData[orgId];

                if (org) {
                    document.getElementById('editOrgId').value = org.id;
                    document.getElementById('editOrgName').value = org.name;
                    document.getElementById('editOrgShortName').value = org.short_name;
                    document.getElementById('editOrgDescription').value = org.description || '';
                    document.getElementById('editOrgYear').value = org.year;
                    document.getElementById('editOrgInstitute').value = org.institute || '';
                    document.getElementById('editOrgAdviserSchoolNo').value = org.adviser_school_number || '';
                    document.getElementById('editOrgAdviserName').value = org.adviser_name || '';
                    document.getElementById('editOrgStatus').value = org.status;

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
                const form = document.getElementById('createOrgForm');
                const formData = new FormData(form);

                // Here you would normally send to backend
                console.log('Creating organization with data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }

                alert('Organization created successfully! (Demo mode - no backend connection)');
                closeModal('confirmCreateModal');
                closeModal('createOrgModal');
                form.reset();
            }

            function confirmUpdateOrg(event) {
                event.preventDefault();
                document.getElementById('confirmUpdateModal').classList.add('show');
            }

            function submitUpdateOrg() {
                const orgId = document.getElementById('editOrgId').value;
                const form = document.getElementById('editOrgForm');
                const formData = new FormData(form);

                // Here you would normally send to backend
                console.log('Updating organization ' + orgId + ' with data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }

                alert('Organization updated successfully! (Demo mode - no backend connection)');
                closeModal('confirmUpdateModal');
                closeModal('editOrgModal');
            }

            // EVENT LOGIC
            function toggleEventMenu(eventId) {
                document.querySelectorAll('.event-dropdown').forEach(el => {
                    if (el.id !== 'eventMenu' + eventId) el.classList.remove('show');
                });
                const menu = document.getElementById('eventMenu' + eventId);
                menu.classList.toggle('show');
            }

            function confirmDeleteEvent(id, title) {
                currentEventId = id;
                document.getElementById('deleteEventMessage').innerText = `Are you sure you want to delete "${title}"? This action cannot be undone.`;
                document.getElementById('confirmDeleteModal').classList.add('show');
            }

            function submitDeleteEvent() {
                if (!currentEventId) return;

                console.log('Deleting event ID: ' + currentEventId);

                alert('Event deleted successfully! (Demo mode - no backend connection)');
                closeModal('confirmDeleteModal');

                // In a real application, you would reload or remove the card from DOM
                currentEventId = null;
            }

            // GLOBAL EVENT LISTENERS
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) this.classList.remove('show');
                });
            });

            window.onclick = function(event) {
                if (!event.target.closest('.event-menu')) {
                    document.querySelectorAll('.event-dropdown').forEach(d => d.classList.remove('show'));
                }
            }
        </script>
</body>

</html>