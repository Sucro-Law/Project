@extends('layout.main')

@push('styles')
@vite(['resources/css/orgdesc.css'])
@endpush

@section('content')

<div class="container-custom">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Organization Header -->
    <div class="org-header-card">
        <div class="org-header-content">
            <div class="org-logo-large">{{ $organization->short_name }}</div>

            <div class="org-main-info">
                <h1>{{ $organization->org_name }}</h1>
                <div>
                    <span class="org-status-badge">
                        <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i>
                        {{ strtoupper($organization->status) }}
                    </span>
                </div>
                <div class="org-meta-info">
                    <div class="meta-item">
                        <i class="bi bi-calendar3"></i>
                        Established: {{ $organization->year }}
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-people-fill"></i>
                        {{ $organization->member_count }} Members
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-person-badge"></i>
                        {{ $organization->officers_count }} Officers
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-building"></i>
                        Institute of Bachelors in Information Technology Studies
                    </div>
                </div>
            </div>

            <div class="org-actions">
                @if($role === 'officer' || $role === 'adviser')
                <button class="btn-add-member" onclick="openModal('memberAdmissionModal')">
                    <i class="bi bi-person-plus"></i> ADD MEMBER
                </button>
                @elseif($role === 'member')
                <span class="btn-primary-custom" style="background: #28a745; cursor: default;">
                    <i class="bi bi-check-circle me-1"></i> MEMBER
                </span>
                @elseif($account_type !== 'Faculty')
                <button class="btn-primary-custom" onclick="openModal('membershipModal')">
                    MEMBERSHIP FORM
                </button>

                @endif
                <button class="btn-secondary-custom" onclick="shareOrganization()">
                    <i class="bi bi-share me-1"></i> Share
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="custom-tabs">
        <div class="tabs-left">
            <button class="tab-btn active" onclick="showTab('about')">About us</button>
            <button class="tab-btn" onclick="showTab('officers')">Officers</button>
            <button class="tab-btn" onclick="showTab('members')">Members</button>
            <button class="tab-btn" onclick="showTab('alumni')">Alumni</button>
            <button class="tab-btn" onclick="showTab('events')">Events</button>
        </div>
        <div class="tabs-right">
            @if($role === 'officer' || $role === 'adviser')
            <button class="tab-btn" onclick="openModal('pendingMembers')">
                <span class="pending-badge" title="Pending member requests">
                    <i class="bi bi-person-plus-fill"></i>
                    <span class="badge-count">{{ count($organization->pendingMemberships) }}</span>
                </span>
            </button>
            <button class="tab-btn" onclick="openModal('pendingEvents')">
                <span class="pending-badge" title="Pending event approvals">
                    <i class="bi bi-clock-history"></i>
                    <span class="badge-count">{{ isset($pendingEvents) ? count($pendingEvents) : 0 }}</span>
                </span>
            </button>
            @endif
        </div>
    </div>

    <!-- About Tab -->
    <div id="about" class="tab-content active">
        <div class="about-section">
            <div class="section-title">About us!</div>

            @if($organization->adviser)
            <div class="adviser-info">
                <div class="adviser-avatar">
                    {{ strtoupper(substr($organization->adviser->full_name, 0, 2)) }}
                </div>
                <div class="adviser-details">
                    <h4>{{ $organization->adviser->full_name }}</h4>
                    <p>Organization Adviser</p>
                </div>
            </div>
            @endif

            <p class="description-text">
                {{ $organization->description ?? 'No description available' }}
            </p>
        </div>
    </div>

    <!-- Officers Tab -->
    <div id="officers" class="tab-content">
        <div class="about-section">
            <div class="section-title">Officers ({{ count($organization->officers) }})</div>
            @if(count($organization->officers) > 0)
            <div class="officers-grid" style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($organization->officers as $index => $officer)
                <div class="officer-item" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 25px; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">

                    <div style="display: flex; align-items: center; gap: 40px;">
                        <div class="officer-role" style="min-width: 120px; font-weight: 600; color: #800000;">
                            {{ $officer->position ?? 'Officer' }}
                        </div>
                        <div class="officer-name">
                            {{ $officer->full_name }}
                        </div>
                    </div>

                    @if($role === 'adviser')
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <button class="btn-edit-member" style="background: none; border: none; color: #800000; cursor: pointer;" onclick="openEditModal('{{ $officer->membership_id }}', '{{ $officer->school_id ?? '' }}', '{{ addslashes($officer->full_name) }}', '{{ $officer->email ?? '' }}', '{{ $officer->membership_role }}', '{{ $officer->position ?? '' }}')">
                            <i class="bi bi-box-arrow-up-right" style="font-size: 1.2rem;"></i>
                        </button>

                        <form action="/organization/{{ $organization->org_id }}/membership/{{ $officer->membership_id }}" method="POST" onsubmit="return confirmDelete('{{ addslashes($officer->full_name) }}')" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; display: flex; align-items: center;">
                                <i class="bi bi-trash3" style="font-size: 1.2rem;"></i>
                            </button>
                        </form>
                    </div>
                    @endif

                </div>
                @endforeach
            </div>
            @else
            <p style="text-align: center; color: #666; padding: 20px;">No officers assigned yet.</p>
            @endif
        </div>
    </div>

    <!-- Members Tab -->
    <div id="members" class="tab-content">
        <div class="about-section">
            <div class="section-title">Members ({{ count($organization->activeMemberships) }})</div>
            @if(count($organization->activeMemberships) > 0)
            <div class="members-grid">
                @foreach($organization->activeMemberships as $index => $member)
                <div class="member-card" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 15px;">
                    <span class="member-name">{{ $index + 1 }}. {{ $member->full_name }}</span>

                    @if($role === 'officer' || $role === 'adviser')
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <button class="btn-edit-member" onclick="openEditModal('{{ $member->membership_id }}', '{{ $member->school_id ?? '' }}', '{{ addslashes($member->full_name) }}', '{{ $member->email ?? '' }}', '{{ $member->membership_role }}', '{{ $member->position ?? '' }}')">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </button>

                        <form action="/organization/{{ $organization->org_id }}/membership/{{ $member->membership_id }}" method="POST" onsubmit="return confirmDelete('{{ addslashes($member->full_name) }}')" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; display: flex; align-items: center; padding: 0;">
                                <i class="bi bi-trash3" style="font-size: 1.2rem;"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <p style="text-align: center; color: #666; padding: 20px;">No active members yet.</p>
            @endif
        </div>
    </div>

    <!-- Alumni Tab -->
    <div id="alumni" class="tab-content">
        <div class="about-section">
            <div class="section-title">Alumni ({{ count($organization->alumniMembers) }})</div>
            @if(count($organization->alumniMembers) > 0)
            <div class="members-list">
                @foreach($organization->alumniMembers as $index => $alumni)
                <div class="member-item">{{ $index + 1 }}. {{ $alumni->full_name }}</div>
                @endforeach
            </div>
            @else
            <p style="text-align: center; color: #666; padding: 20px;">No alumni members yet.</p>
            @endif
        </div>
    </div>

    <!-- Events Tab -->
    <div id="events" class="tab-content">
        @if($role === 'officer' || $role === 'adviser')
        <button class="btn-create-event" onclick="openModal('eventPostingModal')">
            <i class="bi bi-plus-circle"></i>
            Create Event
        </button>
        @endif

        <div class="events-list">
            <div class="section-title">Events</div>

            @if(count($organizationEvents) > 0)
            @foreach($organizationEvents as $event)
            <div class="event-item">
                <div class="event-header-row">
                 <span class="event-status {{ $event->is_ended ? 'status-ended' : 'status-upcoming' }}">
                        {{ $event->is_ended ? 'ENDED' : 'UPCOMING' }}
</span>
                    @if(($role === 'officer' || $role === 'adviser') && !$event->is_ended)
                    <div class="event-menu">
                        <button class="event-menu-btn" onclick="toggleEventMenu('{{ $event->event_id }}')">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="event-dropdown" id="eventMenu{{ $event->event_id }}">
                            <button onclick="confirmDeleteEvent('{{ $event->event_id }}', '{{ addslashes($event->title) }}')">
                                <i class="bi bi-trash"></i> Delete Event
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
                <h3>{{ $event->title }}</h3>
                <p class="event-description">
                    {{ $event->description }}
                </p>
                <div class="event-details">
                    <div class="event-detail-item">
                        <i class="bi bi-calendar-check"></i>
                        Date: {{ $event->formatted_date }}
                    </div>
                    <div class="event-detail-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        Event's Place: {{ $event->venue ?? 'TBD' }}
                    </div>
                    <div class="event-detail-item">
                        <i class="bi bi-person-circle"></i>
                        Author: {{ $event->author_name ?? 'Unknown' }}
                    </div>
                    <div class="event-detail-item">
                        <button class="btn-like {{ $event->user_liked ? 'liked' : '' }}"
                            onclick="toggleLike('{{ $event->event_id }}', this)"
                            style="background: none; border: none; cursor: pointer; color: {{ $event->user_liked ? '#ff0000' : '#666' }}">
                            <i class="bi {{ $event->user_liked ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            <span class="like-count">{{ $event->likes_count ?? 0 }}</span> Likes
                        </button>
                    </div>
                </div>

                @if(!$event->is_ended)
                <div class="event-action-group">
                    @if($role === 'officer' || $role === 'adviser')
                    <button class="btn-view-attendees" title="See Attendees" onclick="showAttendees('{{ $event->event_id }}')">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    @endif
                    @if($role !== 'officer' && $role !== 'adviser' && $account_type !== 'Faculty')
                    <form action="{{ route('events.rsvp', $event->event_id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-rsvp">RSVP</button>
                    </form>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
            @else
            <p style="text-align: center; color: #666; padding: 40px;">No events posted yet.</p>
            @endif
        </div>
    </div>
    
    <!-- Attendees Modal -->
    <div class="modal-overlay" id="attendeesModal">
        <div class="modal-content" style="padding: 0; width: 400px; border: 3px solid #500000; background: white; border-radius: 8px;">
            <div style="background: #500000; color: white; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-weight: bold;">ATTENDEES</h3>
                <button class="modal-close" style="color: white; position: static;" onclick="closeModal('attendeesModal')">X</button>
            </div>
            <div class="attendees-list-container" id="attendeesListContainer"></div>
        </div>
    </div>
</div>

<!-- Member Admission Modal (For Officers/Advisers) -->
<div class="modal-overlay" id="memberAdmissionModal">
    <div class="modal-content" style="padding: 0; max-width: 700px; border-radius: 8px;">
        <div class="admission-header">
            <h4 style="margin: 0;">{{ $organization->org_name }}</h4>
            <button class="modal-close" style="color: white; top: 10px;" onclick="closeModal('memberAdmissionModal')">X</button>
        </div>
        <div class="admission-body text-center">
            <h2 class="admission-title">MEMBER ADMISSION</h2>

            <form action="{{ route('organization.addMember', $organization->org_id) }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" name="school_id" class="form-control" placeholder="School Number" required>
                    </div>
                    <div class="col-md-6">
                        <select name="member_type" class="form-select" required>
                            <option value="">MEMBER/OFFICER</option>
                            <option value="Member">Member</option>
                            <option value="Officer">Officer</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><input type="text" name="first_name" class="form-control" placeholder="First Name" required></div>
                    <div class="col-md-4"><input type="text" name="middle_name" class="form-control" placeholder="Middle Name"></div>
                    <div class="col-md-4"><input type="text" name="last_name" class="form-control" placeholder="Last Name" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Email@gmail.com" required></div>
                    <div class="col-md-6"><input type="text" name="position" class="form-control" placeholder="Role (IF OFFICER)"></div>
                </div>
                <button type="submit" class="btn-primary-custom" style="width: 100%; background: #800000; height: 50px;">ADD MEMBER</button>
            </form>
        </div>
    </div>
</div>

<!-- Event Posting Modal -->
<div class="modal-overlay" id="eventPostingModal">
    <div class="modal-content-event">
        <button class="modal-close" onclick="closeModal('eventPostingModal')">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="event-posting-title">EVENT POSTING</h2>

        <form action="{{ route('organization.createEvent', $organization->org_id) }}"
            method="POST"
            enctype="multipart/form-data"
            id="eventPostingForm">
            @csrf
            <div class="posting-grid">
                <div class="full-width">
                    <input type="text"
                        name="title"
                        class="posting-input"
                        placeholder="Title"
                        required
                        value="{{ old('title') }}">
                    @error('title')
                    <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="posting-left">

                    <div class="details-section">
                        <label class="mb-1">Details:</label>
                        <input type="date"
                            name="event_date"
                            class="posting-input-small mb-2"
                            placeholder="Date"
                            required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            value="{{ old('event_date') }}">
                        @error('event_date')
                        <small style="color: red;">{{ $message }}</small>
                        @enderror

                        <input type="text"
                            name="venue"
                            class="posting-input-small"
                            placeholder="Event's Place"
                            required
                            value="{{ old('venue') }}">
                        @error('venue')
                        <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="posting-right">
                    <textarea name="description"
                        class="posting-textarea"
                        placeholder="Description"
                        required>{{ old('description') }}</textarea>
                    @error('description')
                    <small style="color: red;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="posting-footer">
                <button type="submit" class="btn-submit-event">SUBMIT</button>
            </div>
        </form>
    </div>
</div>


<!-- Pending Members Modal -->
<div class="modal-overlay" id="pendingMembers">
    <div class="modal-content-pending">
        <button class="modal-close" onclick="closeModal('pendingMembers')">
            <i class="bi bi-x-lg"></i>
        </button>
        <h2 class="modal-title-pending">Pending Members</h2>

        @if(count($organization->pendingMemberships) > 0)
        <div class="accordion" id="pendingMembersAccordion">
            @foreach($organization->pendingMemberships as $index => $pending)
            <div class="accordion-item member-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#member{{ $index }}">
                        <span class="member-header-info">
                            <strong>Name:</strong> {{ $pending->full_name }}
                            <span class="divider">|</span>
                            <strong>School ID:</strong> {{ $pending->school_id }}
                        </span>
                    </button>
                </h2>
                <div id="member{{ $index }}" class="accordion-collapse collapse"
                    data-bs-parent="#pendingMembersAccordion">
                    <div class="accordion-body">
                        <div class="member-details-container">
                            <div class="member-info-box">
                                <div class="info-row">
                                    <strong>Full Name:</strong>
                                    <span class="text-maroon">{{ $pending->full_name }}</span>
                                </div>
                                <div class="info-row">
                                    <strong>School ID:</strong>
                                    <span class="text-maroon">{{ $pending->school_id }}</span>
                                </div>
                                <div class="info-row">
                                    <strong>Email:</strong>
                                    <span>{{ $pending->email }}</span>
                                </div>
                                <div class="info-row">
                                    <strong>Role:</strong> {{ $pending->membership_role }}
                                </div>
                                <div class="info-row">
                                    <strong>Applied:</strong> {{ date('M d, Y', strtotime($pending->joined_at)) }}
                                </div>
                            </div>
                            <div class="member-action-buttons">
                                <form action="{{ route('organization.approveMember', [$organization->org_id, $pending->membership_id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-accept">ACCEPT</button>
                                </form>
                                <form action="{{ route('organization.rejectMember', [$organization->org_id, $pending->membership_id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-decline">DECLINE</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align: center; color: #666; padding: 40px;">No pending membership requests.</p>
        @endif
    </div>
</div>

<!-- Pending Events Modal -->
<div class="modal-overlay" id="pendingEvents">
    <div class="modal-content-pending">
        <button class="modal-close" onclick="closeModal('pendingEvents')">
            <i class="bi bi-x-lg"></i>
        </button>
        <h2 class="modal-title-pending">Pending Events</h2>

        @if(isset($pendingEvents) && count($pendingEvents) > 0)
        <div class="accordion" id="pendingEventsAccordion">
            @foreach($pendingEvents as $index => $event)
            <div class="accordion-item event-accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#event{{ $event->event_id }}">
                        <div class="event-header-info">
                            <strong>Title:</strong> <span class="fw-normal">{{ $event->title }}</span><br>
                            <small class="text-muted">Submitted by: {{ $event->submitted_by ?? 'Unknown' }}</small>
                        </div>
                    </button>
                </h2>
                <div id="event{{ $event->event_id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                    data-bs-parent="#pendingEventsAccordion">
                    <div class="accordion-body">
                        <div class="event-details-container">
                            <input type="text" class="event-title-input" value="{{ $event->title }}" readonly>

                            <div class="event-content-row">
                                <div class="event-visual-column">

                                    <div class="event-details-inputs">
                                        <label class="input-label">Details:</label>
                                        <input type="text" class="detail-input" value="{{ $event->formatted_date ?? 'N/A' }}" readonly>
                                        <input type="text" class="detail-input" value="{{ $event->venue ?? 'TBD' }}" readonly>
                                    </div>
                                </div>

                                <div class="event-description-column">
                                    <p>{{ $event->description ?? 'No description provided.' }}</p>
                                </div>
                            </div>

                            <p class="waiting-verification">-- Waiting for Verification --</p>

                            <div class="event-action-buttons">
                                @if($role === 'adviser')
                                <button type="button" class="btn-edit-event" onclick="openEditEventModal('{{ $event->event_id }}', '{{ addslashes($event->title) }}', '{{ addslashes($event->description ?? '') }}', '{{ $event->event_date }}', '{{ $event->venue ?? '' }}', '{{ $event->event_duration ?? 4 }}')">EDIT</button>
                                <form action="{{ route('organization.approveEvent', [$organization->org_id, $event->event_id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-approve">APPROVE</button>
                                </form>
                                <form action="{{ route('organization.rejectEvent', [$organization->org_id, $event->event_id]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-reject">REJECT</button>
                                </form>
                                @endif

                                @if($role === 'officer' && Auth::check() && $event->created_by == Auth::user()->user_id)
                                <form action="{{ route('organization.cancelEvent', [$organization->org_id, $event->event_id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this event submission?');">
                                    @csrf
                                    <button type="submit" class="btn-cancel-event">CANCEL</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align: center; color: #666; padding: 40px;">No pending event approvals.</p>
        @endif
    </div>
</div>

<!-- Edit Event Modal (For Advisers) -->
<div class="modal-overlay" id="editEventModal">
    <div class="modal-content-event">
        <button class="modal-close" onclick="closeModal('editEventModal')">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="event-posting-title">EDIT EVENT</h2>

        <form action="" method="POST" id="editEventForm">
            @csrf
            @method('PUT')
            <div class="posting-grid">
                <div class="full-width">
                    <input type="text"
                        name="title"
                        id="editEventTitle"
                        class="posting-input"
                        placeholder="Title"
                        required>
                </div>

                <div class="posting-left">
                    <div class="details-section">
                        <label class="mb-1">Details:</label>
                        <input type="date"
                            name="event_date"
                            id="editEventDate"
                            class="posting-input-small mb-2"
                            placeholder="Date"
                            required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}">

                        <input type="text"
                            name="venue"
                            id="editEventVenue"
                            class="posting-input-small mb-2"
                            placeholder="Event's Place"
                            required>

                        <input type="number"
                            name="event_duration"
                            id="editEventDuration"
                            class="posting-input-small"
                            placeholder="Duration (hours)"
                            min="1"
                            max="24">
                    </div>
                </div>

                <div class="posting-right">
                    <textarea name="description"
                        id="editEventDescription"
                        class="posting-textarea"
                        placeholder="Description"
                        required></textarea>
                </div>
            </div>

            <div class="posting-footer">
                <button type="submit" class="btn-submit-event">UPDATE EVENT</button>
            </div>
        </form>
    </div>
</div>



<!-- Membership Modal (For Regular Users) -->
<div class="modal-overlay" id="membershipModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('membershipModal')">
            <i class="bi bi-x-lg"></i>
        </button>
        <h2 class="modal-title">MEMBERSHIP FORM</h2>
        <p style="color: #666; margin-bottom: 25px;">{{ $organization->org_name }}</p>

        <form action="{{ route('organization.submitMembership', $organization->org_id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">School Number</label>
                <input type="text" name="school_number" class="form-control" placeholder="SN-XXXXXXXX"
                    value="{{ Auth::check() ? Auth::user()->school_id : '' }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" placeholder="Middle Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Email@gmail.com"
                    value="{{ Auth::check() ? Auth::user()->email : '' }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Position</label>
                <select name="position" class="form-select" required>
                    <option value="">MEMBER/OFFICER</option>
                    <option value="member">Member</option>
                    <option value="officer">Officer</option>
                </select>
            </div>


            <div class="certification-text">
                I hereby certify that the information provided in this form is true, complete,
                and accurate to the best of my knowledge. I understand that any misrepresentation
                or material omission made on this form may result in the rejection of my application.
            </div>

            <button type="submit" class="btn-primary-custom">
                SUBMIT APPLICATION
            </button>
        </form>
    </div>
</div>

<!-- Edit Member Modal -->
<div class="modal-overlay" id="editMemberModal">
    <div class="modal-content" style="padding: 0; max-width: 500px; border-radius: 8px; overflow: visible;">
        <div style="background: #500000; color: white; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center; border-radius: 8px 8px 0 0; position: relative;">
            <h4 style="margin: 0;">{{ $organization->org_name }}</h4>
            <button type="button" class="edit-modal-close-btn" onclick="closeModal('editMemberModal')">×</button>
        </div>
        <div style="padding: 20px;">
            <h2 class="modal-title" style="text-align: center; margin-bottom: 20px;">EDIT MEMBER</h2>

            <form id="editMemberForm" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" id="editSchoolId" class="form-control" placeholder="School Number" readonly style="background: #f5f5f5;">
                    </div>
                    <div class="col-md-6">
                        <select name="member_type" id="editMemberType" class="form-select" required onchange="togglePositionField()">
                            <option value="Member">Member</option>
                            <option value="Officer">Officer</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="editFirstName" class="form-control" placeholder="First Name" readonly style="background: #f5f5f5;">
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="editMiddleName" class="form-control" placeholder="Middle Name" readonly style="background: #f5f5f5;">
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="editLastName" class="form-control" placeholder="Last Name" readonly style="background: #f5f5f5;">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="email" id="editEmail" class="form-control" placeholder="Email@gmail.com" readonly style="background: #f5f5f5;">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="position" id="editMemberPosition" class="form-control" placeholder="Role (IF OFFICER)">
                    </div>
                </div>

                <button type="submit" class="btn-primary-custom" style="width: 100%; background: #800000; height: 45px;">
                    UPDATE RECORD
                </button>
            </form>
        </div>
    </div>
</div>



<!-- RSVP Modal -->
<div class="modal-overlay" id="rsvpModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('rsvpModal')">
            <i class="bi bi-x-lg"></i>
        </button>
        <h2 class="modal-title">EVENT RSVP FORM</h2>
        <p style="color: #666; margin-bottom: 25px;">{{ $organization->org_name }}</p>

        <form id="rsvpForm">
            @csrf
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                <h4 style="color: var(--pup-maroon); margin-bottom: 10px;">Event: 2026: Web Development Workshop</h4>
                <p style="margin: 0; color: #666;"><strong>Details:</strong></p>
                <p style="margin: 5px 0; color: #666;">Date: 01/18/26</p>
                <p style="margin: 5px 0; color: #666;">Event's Place: PUP South</p>
            </div>

            <div class="form-group">
                <label class="form-label">School Number</label>
                <input type="text" class="form-control" placeholder="SN-XXXXXXXX"
                    value="{{ Auth::check() ? Auth::user()->school_id : '' }}" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" placeholder="First Name" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" placeholder="Middle Name">
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" placeholder="Last Name" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" placeholder="Email@gmail.com"
                    value="{{ Auth::check() ? Auth::user()->email : '' }}" required>
            </div>

            <div class="certification-text">
                I acknowledge that this RSVP is a confirmation of my attendance.
            </div>

            <button type="submit" class="btn-primary-custom" style="width: 100%;">
                SUBMIT RSVP
            </button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay confirmation-modal" id="confirmDeleteModal">
    <div class="modal-content">
        <div class="confirmation-body">
            <i class="bi bi-exclamation-triangle warning-icon"></i>
            <h4>Delete Event?</h4>
            <p id="deleteEventMessage">Are you sure you want to delete this event?</p>
            <form id="deleteEventForm" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            <div class="confirmation-actions">
                <button class="btn-secondary" onclick="closeModal('confirmDeleteModal')">Cancel</button>
                <button class="btn-danger" onclick="submitDeleteEvent()">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openEditEventModal(eventId, title, description, eventDate, venue, duration) {
        document.getElementById('editEventTitle').value = title.replace(/\\'/g, "'");
        document.getElementById('editEventDescription').value = description.replace(/\\'/g, "'");
        document.getElementById('editEventDate').value = eventDate.split(' ')[0];
        document.getElementById('editEventVenue').value = venue;
        document.getElementById('editEventDuration').value = duration;
        document.getElementById('editEventForm').action = '/events/' + eventId + '/update';
        openModal('editEventModal');
    }


    // Store attendees data for each event
    const eventAttendees = {
        @foreach($organizationEvents as $event)
        '{{ $event->event_id }}': [
            @foreach($event -> attendees as $attendee) {
                name: '{{ $attendee->full_name }}',
                status: '{{ $attendee->attendance_status }}'
            },
            @endforeach
        ],
        @endforeach
    };


    function showAttendees(eventId) {
        const container = document.getElementById('attendeesListContainer');
        const attendees = eventAttendees[eventId] || [];

        if (attendees.length === 0) {
            container.innerHTML = '<div class="attendee-row" style="text-align: center; color: #666;">No attendees yet</div>';
        } else {
            container.innerHTML = attendees.map((a, i) =>
                `<div class="attendee-row">${i + 1}. ${a.name} <span style="color: #888; font-size: 12px;">(${a.status})</span></div>`
            ).join('');
        }

        openModal('attendeesModal');
    }

    let currentEventId = null;

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
        const form = document.getElementById('deleteEventForm');
        form.action = '/events/' + currentEventId + '/delete';
        form.submit();
    }

    window.onclick = function(event) {
        if (!event.target.closest('.event-menu')) {
            document.querySelectorAll('.event-dropdown').forEach(d => d.classList.remove('show'));
        }
    }

    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        document.getElementById(tabName).classList.add('active');

        event.target.classList.add('active');
    }

    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });


    document.getElementById('eventImage')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const img = document.getElementById('previewImage');
            img.src = URL.createObjectURL(file);
            img.style.display = 'block';
        }
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    function shareOrganization() {
        const url = window.location.href;
        if (navigator.share) {
            navigator.share({
                title: '{{ $organization->org_name }}',
                url: url
            });
        } else {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    }

    // Edit Member Modal Functions
    function openEditModal(membershipId, schoolId, fullName, email, memberRole, position) {
        // Parse full name into parts
        const nameParts = fullName.replace(/\\'/g, "'").split(' ');
        let firstName = '',
            middleName = '',
            lastName = '';

        if (nameParts.length === 1) {
            firstName = nameParts[0];
        } else if (nameParts.length === 2) {
            firstName = nameParts[0];
            lastName = nameParts[1];
        } else {
            firstName = nameParts[0];
            lastName = nameParts[nameParts.length - 1];
            middleName = nameParts.slice(1, -1).join(' ');
        }

        document.getElementById('editSchoolId').value = schoolId || '';
        document.getElementById('editFirstName').value = firstName;
        document.getElementById('editMiddleName').value = middleName;
        document.getElementById('editLastName').value = lastName;
        document.getElementById('editEmail').value = email || '';
        document.getElementById('editMemberType').value = memberRole;
        document.getElementById('editMemberPosition').value = position || '';
        document.getElementById('editMemberForm').action = '/organization/{{ $organization->org_id }}/membership/' + membershipId + '/update';
        openModal('editMemberModal');
    }

    function togglePositionField() {
        // Position field is always visible now, just enable/disable based on role
        const memberType = document.getElementById('editMemberType').value;
        const positionInput = document.getElementById('editMemberPosition');

        if (memberType === 'Officer') {
            positionInput.required = true;
            positionInput.placeholder = 'Role (Required for Officer)';
        } else {
            positionInput.required = false;
            positionInput.placeholder = 'Role (IF OFFICER)';
        }
    }


    function confirmDelete(name) {
        return confirm("Are you sure you want to permanently delete " + name + " from this organization? This action cannot be undone.");
    }


    function toggleLike(eventId, button) {
        fetch(`/events/${eventId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const icon = button.querySelector('i');
                    const countSpan = button.querySelector('.like-count');

                    if (data.liked) {
                        icon.classList.replace('bi-heart', 'bi-heart-fill');
                        button.style.color = '#ff0000';
                    } else {
                        icon.classList.replace('bi-heart-fill', 'bi-heart');
                        button.style.color = '#666';
                    }
                    countSpan.innerText = data.likes_count;
                }
            });
    }
</script>

@endsection