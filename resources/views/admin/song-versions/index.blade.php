@extends('layouts.admin')

@section('title', 'Song Versions - ' . $song->title)

@section('content')
        <div class="page-header">
            <div class="page-title">
                <h1>🎼 Song Versions</h1>
                <p>Manage versions for "{{ $song->title }}" by {{ $song->artist->name }}</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('songs.versions.create', $song) }}" class="btn btn-primary">
                    <span>➕</span> Add New Version
                </a>
                <a href="{{ route('songs.index') }}" class="btn btn-secondary">
                    <span>←</span> Back to Songs
                </a>
            </div>
        </div>

        <!-- Song Info Card -->
        <div class="song-info-card">
            <div class="song-details">
                <div class="song-main-info">
                    <h2 class="song-title">
                        <span class="song-icon">🎵</span>
                        {{ $song->title }}
                    </h2>
                    <p class="song-artist">
                        <span class="artist-icon">🎤</span>
                        by {{ $song->artist->name }}
                    </p>
                </div>
                <div class="song-meta">
                    @if($song->original_key)
                    <span class="meta-item">
                        <span class="meta-label">Key:</span>
                        <span class="key-badge">{{ $song->original_key }}</span>
                    </span>
                    @endif
                    @if($song->bpm)
                    <span class="meta-item">
                        <span class="meta-label">BPM:</span>
                        <span class="meta-value">{{ $song->bpm }}</span>
                    </span>
                    @endif
                    @if($song->time_signature)
                    <span class="meta-item">
                        <span class="meta-label">Time:</span>
                        <span class="meta-value">{{ $song->time_signature }}</span>
                    </span>
                    @endif
                    @if($song->capo)
                    <span class="meta-item">
                        <span class="meta-label">Capo:</span>
                        <span class="meta-value">{{ $song->capo }}</span>
                    </span>
                    @endif
                </div>
            </div>
            @if($song->youtube_url)
            <div class="song-actions">
                <a href="{{ $song->youtube_url }}" target="_blank" class="btn btn-youtube">
                    <span>📺</span> Watch Video
                </a>
            </div>
            @endif
        </div>

        <!-- Versions Grid -->
        <div class="versions-container">
            @if($versions->count() > 0)
            <div class="versions-grid">
                @foreach($versions as $version)
                <div class="version-card {{ $version->is_default ? 'default-version' : '' }}">
                    <div class="version-header">
                        <div class="version-title">
                            <h3>{{ $version->version_label }}</h3>
                            @if($version->is_default)
                            <span class="default-badge">Default</span>
                            @endif
                        </div>
                        <div class="version-actions">
                            <div class="dropdown">
                                <button class="btn-menu" onclick="toggleDropdown({{ $version->id }})">
                                    ⋯
                                </button>
                                <div class="dropdown-menu" id="dropdown-{{ $version->id }}">
                                    <a href="{{ route('songs.versions.show', [$song, $version]) }}" class="dropdown-item">
                                        <span>👁️</span> Preview
                                    </a>
                                    <a href="{{ route('songs.versions.edit', [$song, $version]) }}" class="dropdown-item">
                                        <span>✏️</span> Edit
                                    </a>
                                    <form action="{{ route('songs.versions.duplicate', [$song, $version]) }}" method="POST" class="dropdown-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <span>📋</span> Duplicate
                                        </button>
                                    </form>
                                    @if(!$version->is_default)
                                    <form action="{{ route('songs.versions.set-default', [$song, $version]) }}" method="POST" class="dropdown-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="dropdown-item">
                                            <span>⭐</span> Set as Default
                                        </button>
                                    </form>
                                    @endif
                                    @if($versions->count() > 1)
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('songs.versions.destroy', [$song, $version]) }}"
                                        method="POST"
                                        class="dropdown-form"
                                        onsubmit="return confirmDelete('{{ $version->version_label }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item danger">
                                            <span>🗑️</span> Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="version-content">
                        @if($version->notes)
                        <p class="version-notes">{{ $version->notes }}</p>
                        @endif

                        <div class="version-stats">
                            <div class="stat-item">
                                <span class="stat-icon">📑</span>
                                <span class="stat-label">Sections:</span>
                                <span class="stat-value">{{ $version->sections->count() }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">📝</span>
                                <span class="stat-label">Lines:</span>
                                <span class="stat-value">{{ $version->sections->sum(function($section) { return $section->lines->count(); }) }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">🎸</span>
                                <span class="stat-label">Chords:</span>
                                <span class="stat-value">{{ $version->sections->sum(function($section) { return $section->lines->sum(function($line) { return $line->chordPositions->count(); }); }) }}</span>
                            </div>
                        </div>

                        <div class="version-meta">
                            <small class="created-info">
                                Created {{ $version->created_at->diffForHumans() }}
                                @if($version->created_at != $version->updated_at)
                                • Updated {{ $version->updated_at->diffForHumans() }}
                                @endif
                            </small>
                        </div>
                    </div>

                    <div class="version-footer">
                        <a href="{{ route('songs.versions.show', [$song, $version]) }}" class="btn btn-sm btn-outline">
                            <span>👁️</span> Preview
                        </a>
                        <a href="{{ route('songs.versions.edit', [$song, $version]) }}" class="btn btn-sm btn-primary">
                            <span>✏️</span> Edit
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <div class="empty-icon">🎼</div>
                <h3>No Versions Yet</h3>
                <p>This song doesn't have any versions yet. Create the first version to start adding chord progressions and lyrics.</p>
                <a href="{{ route('songs.versions.create', $song) }}" class="btn btn-primary">
                    <span>➕</span> Create First Version
                </a>
            </div>
            @endif
        </div>
        @endsection

        @push('scripts')
        <script>
            function toggleDropdown(versionId) {
                const dropdown = document.getElementById(`dropdown-${versionId}`);
                const allDropdowns = document.querySelectorAll('.dropdown-menu');

                // Close all other dropdowns
                allDropdowns.forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.remove('show');
                    }
                });

                // Toggle current dropdown
                dropdown.classList.toggle('show');
            }

            function confirmDelete(versionName) {
                return confirm(`Are you sure you want to delete the version "${versionName}"?\n\nThis will permanently delete:\n- All sections\n- All lyrics and chord progressions\n- This action cannot be undone.`);
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });
        </script>
        @endpush