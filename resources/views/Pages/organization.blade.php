@extends('layout.main')

@section('content')

<style>
    .container-custom {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-title {
        color: maroon !important;
        font-size: 35px;
        font-weight: 700;
        margin-bottom: 30px;
    }

    .org-card-list {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 8px solid var(--pup-maroon);
        display: flex;
        flex-direction: column;
    }

    .org-card-list:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        transform: translateX(5px);
    }

    .org-card-content {
        display: flex;
        gap: 25px;
        align-items: flex-start;
    }

    .org-logo {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--pup-maroon) 0%, var(--pup-dark) 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 2rem;
        flex-shrink: 0;
    }

    .org-info-section {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .org-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .org-name-large {
        font-size: 22px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }

    .org-status-badge {
        background: #d4edda;
        color: #155724;
        padding: 5px 14px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        background: #155724;
        border-radius: 50%;
    }

    .institute-badge {
        background: #f0f0f0;
        color: #555;
        padding: 5px 14px;
        border-radius: 12px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .org-meta-row {
        display: flex;
        gap: 25px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #666;
    }

    .meta-item i {
        color: var(--pup-maroon);
        font-size: 16px;
    }

    .org-desc-short {
        color: #666;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 8px;
        flex-grow: 1;
    }

    .org-action-section {
        display: flex;
        gap: 12px;
        margin-top: 15px;
    }

    .btn-view-org {
        background: var(--pup-maroon);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-view-org:hover {
        background: var(--pup-dark);
        color: white;
        transform: translateY(-2px);
    }

    .alert {
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    @media (max-width: 768px) {
        .org-card-content {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .org-title-row {
            flex-direction: column;
            align-items: center;
        }

        .org-meta-row {
            justify-content: center;
        }
    }
</style>

<div class="container-custom">
    <h1 class="page-title">Organizations</h1>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ session('error') }}
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i>
        {{ session('info') }}
    </div>
    @endif

    @forelse($organizations as $org)
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
                            <span class="org-status-badge">
                                <span class="status-dot"></span>
                                {{ $org['status'] }}
                            </span>
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
                        <span>{{ $org['officers_count'] }} Officers</span>
                    </div>
                </div>

                <p class="org-desc-short">{{ $org['description'] }}</p>

                <div class="org-action-section">
                    <a href="{{ route('orgDetail', ['id' => $org['org_id']]) }}" class="btn-view-org">
                        <i class="bi bi-eye me-2"></i>View Organization
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i>
        No organizations found.
    </div>
    @endforelse
</div>

@endsection