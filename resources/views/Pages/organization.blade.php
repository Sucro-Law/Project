@extends('layout.main')

@push('styles')
@vite(['resources/css/pages.css'])
@endpush

@section('content')

<div class="container-custom">
    <h1 class="page-title">Organizations</h1>

    @foreach($organizations as $org)
    <div class="org-card-list">
        <div class="org-card-content">
            <div class="org-logo">
                {{ $org['short_name'] }}
            </div>

            <div class="org-info-section">
                <div class="org-title-row">
                    <div>
                        <h3 class="org-name-large">{{ $org['name'] }}</h3>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <span class="org-status-badge"><i class="bi bi-circle-fill status-dot"></i>{{ $org['status'] }}</span>
                            <span class="institute-badge">
                                <i class="bi bi-building"></i>
                                Institute of Bachelors in Information Technology Studies
                            </span>
                        </div>
                    </div>
                </div>

                <div class="org-meta-row">
                    <div class="meta-item">
                        <i class="bi bi-calendar-check"></i>
                        <span>Established: {{ $org['year'] }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-people-fill"></i>
                        <span>{{ $org['members'] }} Members</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-person-badge"></i>
                        <span>8 Officers</span>
                    </div>
                </div>

                <p class="org-desc-short">{{ $org['description'] }}</p>

                <div class="org-stats-inline">
                    <div class="stat-inline">
                        <i class="bi bi-person-circle"></i>
                        <span>Adviser: Josef Karol A. Velayo</span>
                    </div>
                </div>

                <div class="org-action-section">
                    <a href="{{ route('orgDetail') }}" class="btn-view-org">
                        <i class="bi bi-eye me-2"></i>View Organization
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection