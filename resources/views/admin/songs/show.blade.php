@extends('layouts.admin')

@section('title', $song->title)

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1>🎵 Song Details</h1>
            <p>View song information and manage versions</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('songs.edit', $song) }}" class="btn btn-primary">
                <span>✏️</span> Edit Song
            </a>
            <a href="{{ route('songs.versions.index', $song) }}" class="btn btn-outline">
                <span>🎼</span> Manage Versions
            </a>
            <a href="{{ route('songs.index') }}" class="btn btn-secondary">
                <span>←</span> Back to Songs
            </a>
        </div>
    </div>

    <!-- Song Header -->
    <div class="song-details-header">
        <div class="song-title-section">
            <h2 class="song-title">{{ $song->title }}</h2>
            <p class="song-artist">by {{ $song->artist->name }}</p>
            
            <div class="song-meta">
                @if($song->original_key)
                    <div class="meta-item">
                        <span class="meta-label">Key:</span>
                        <span class="key-badge">{{ $song->original_key }}</span>
                    </div>
                @endif
                
                @if($song->bpm)
                    <div class="meta-item">
                        <span class="meta-label">BPM:</span>
                        <span class="meta-value">{{ $song->bpm }}</span>
                    </div>
                @endif
                
                @if($song->time_signature)
                    <div class="meta-item">
                        <span class="meta-label">Time Signature:</span>
                        <span class="meta-value">{{ $song->time_signature }}</span>
                    </div>
                @endif
                
                @if($song->capo)
                    <div class="meta-item">
                        <span class="meta-label">Capo:</span>
                        <span class="meta-value">{{ $song->capo }}</span>
                    </div>
                @endif
            </div>
        </div>
        
        @if($song->youtube_url)
            <div class="youtube-section">
                <h4>📺 YouTube Reference</h4>
                <a href="{{ $song->youtube_url }}" target="_blank" class="youtube-link-full">
                    {{ $song->youtube_url }}
                </a>
            </div>
        @endif
    </div>

    <!-- Song Versions -->
    <div class="versions-section">
        <div class="section-header">
            <h3>🎼 Song Versions</h3>
            <div class="section-actions">
                <a href="{{ route('songs.versions.create', $song) }}" class="btn btn-sm btn-primary">
                    <span>➕</span> New Version
                </a>
                <a href="{{ route('songs.versions.index', $song) }}" class="btn btn-sm btn-outline">
                    <span>📋</span> Manage All
                </a>
            </div>
        </div>

        @if($song->versions->count() > 0)
            <div class="versions-grid">
                @foreach($song->versions as $version)
                    <div class="version-card">
                        <div class="version-header">
                            <h4 class="version-title">{{ $version->version_label }}</h4>
                            @if($version->is_default)
                                <span class="default-badge">Default</span>
                            @endif
                        </div>
                        
                        <div class="version-stats">
                            <div class="stat-item">
                                <span class="stat-icon">📄</span>
                                <span class="stat-text">{{ $version->sections->count() }} sections</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">📝</span>
                                <span class="stat-text">{{ $version->sections->sum(fn($s) => $s->lines->count()) }} lines</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">🎸</span>
                                <span class="stat-text">{{ $version->sections->sum(fn($s) => $s->lines->sum(fn($l) => $l->chordPositions->count())) }} chords</span>
                            </div>
                        </div>

                        @if($version->notes)
                            <div class="version-notes">
                                <p>{{ Str::limit($version->notes, 100) }}</p>
                            </div>
                        @endif

                        <div class="version-actions">
                            <a href="{{ route('songs.versions.show', [$song, $version]) }}" class="btn btn-sm btn-outline">
                                <span>👁️</span> Preview
                            </a>
                            <a href="{{ route('songs.versions.edit', [$song, $version]) }}" class="btn btn-sm btn-primary">
                                <span>✏️</span> Edit
                            </a>
                        </div>

                        <div class="version-meta">
                            <small class="version-date">
                                Updated {{ $version->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-versions">
                <div class="empty-icon">🎼</div>
                <h4>No Versions Yet</h4>
                <p>Start by creating the first version of this song.</p>
                <a href="{{ route('songs.versions.create', $song) }}" class="btn btn-primary">
                    <span>➕</span> Create First Version
                </a>
            </div>
        @endif
    </div>

    <!-- Song Information -->
    <div class="song-info-section">
        <div class="info-grid">
            <div class="info-card">
                <h4>📊 Song Statistics</h4>
                <div class="stats-list">
                    <div class="stat-row">
                        <span class="stat-label">Total Versions:</span>
                        <span class="stat-value">{{ $song->versions->count() }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Default Version:</span>
                        <span class="stat-value">
                            @if($song->versions->where('is_default', true)->first())
                                {{ $song->versions->where('is_default', true)->first()->version_label }}
                            @else
                                <em>None set</em>
                            @endif
                        </span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Created:</span>
                        <span class="stat-value">{{ $song->created_at->format('F j, Y') }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Last Modified:</span>
                        <span class="stat-value">{{ $song->updated_at->format('F j, Y') }}</span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Created By:</span>
                        <span class="stat-value">{{ $song->creator->name ?? 'System' }}</span>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <h4>🎵 Quick Actions</h4>
                <div class="quick-actions">
                    @if($song->versions->where('is_default', true)->first())
                        <a href="{{ route('songs.versions.show', [$song, $song->versions->where('is_default', true)->first()]) }}" class="quick-action">
                            <span class="action-icon">👁️</span>
                            <span class="action-text">View Default Version</span>
                        </a>
                        <a href="{{ route('songs.versions.edit', [$song, $song->versions->where('is_default', true)->first()]) }}" class="quick-action">
                            <span class="action-icon">✏️</span>
                            <span class="action-text">Edit Default Version</span>
                        </a>
                    @endif
                    
                    <a href="{{ route('songs.versions.create', $song) }}" class="quick-action">
                        <span class="action-icon">➕</span>
                        <span class="action-text">Create New Version</span>
                    </a>
                    
                    <a href="{{ route('songs.edit', $song) }}" class="quick-action">
                        <span class="action-icon">⚙️</span>
                        <span class="action-text">Edit Song Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="danger-section">
        <div class="danger-card">
            <h4>⚠️ Danger Zone</h4>
            <p>Permanent actions that cannot be undone.</p>
            
            <form action="{{ route('songs.destroy', $song) }}" 
                  method="POST" 
                  class="delete-form"
                  onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <span>🗑️</span> Delete Song
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        const songTitle = '{{ addslashes($song->title) }}';
        const versionsCount = {{ $song->versions->count() }};
        
        return confirm(
            'Are you sure you want to delete "' + songTitle + '"?\\n\\n' +
            'This will permanently delete:\\n' +
            '- The song and all its metadata\\n' +
            '- All ' + versionsCount + ' version(s)\\n' +
            '- All sections, lyrics, and chord progressions\\n\\n' +
            'This action cannot be undone.'
        );
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // Ctrl+E or Cmd+E to edit song
        if ((event.ctrlKey || event.metaKey) && event.key === 'e') {
            event.preventDefault();
            window.location.href = '{{ route("songs.edit", $song) }}';
        }
        
        // Ctrl+V or Cmd+V to view versions
        if ((event.ctrlKey || event.metaKey) && event.key === 'v') {
            event.preventDefault();
            window.location.href = '{{ route("songs.versions.index", $song) }}';
        }
    });
</script>
@endpush
