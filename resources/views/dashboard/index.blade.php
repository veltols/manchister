@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-grid">
    <!-- Welcome Card -->
    <div class="glass-panel welcome-card">
        <h2>Welcome back, {{ $entity ? ($entity->first_name ?? $entity->atp_name) : $user->user_email }}</h2>
        <p>You are logged in as <strong>{{ strtoupper($user->user_type) }}</strong></p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="glass-panel stat-card">
            <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
            <div class="stat-info">
                <h3>Total Employees</h3>
                <p>1,234</p>
            </div>
        </div>
        <div class="glass-panel stat-card">
            <div class="stat-icon"><i class="fa-solid fa-briefcase"></i></div>
            <div class="stat-info">
                <h3>Active Projects</h3>
                <p>42</p>
            </div>
        </div>
        <div class="glass-panel stat-card">
            <div class="stat-icon"><i class="fa-solid fa-check-circle"></i></div>
            <div class="stat-info">
                <h3>Pending Approvals</h3>
                <p>7</p>
            </div>
        </div>
    </div>
</div>

<style>
    .welcome-card {
        margin-bottom: 2rem;
        background: linear-gradient(135deg, rgba(123, 98, 49, 0.4), rgba(195, 143, 42, 0.1));
    }
    .welcome-card h2 {
        margin: 0 0 0.5rem 0;
        color: var(--text-light);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
    }

    .stat-icon {
        font-size: 2rem;
        color: var(--secondary);
    }

    .stat-info h3 {
        margin: 0;
        font-size: 0.9rem;
        color: #aaa;
    }
    .stat-info p {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-light);
    }
</style>
@endsection
