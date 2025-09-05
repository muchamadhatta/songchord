@extends('layouts.admin')

@section('title', $version->version_label . ' - ' . $song->title)

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1>👁️ Version Preview</h1>
            <p>"{{ $version->version_label }}" version of "{{ $song->title }}"</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('songs.versions.edit', [$song, $version]) }}" class="btn btn-primary">
                <span>✏️</span> Edit Version
            </a>
            <a href="{{ route('songs.versions.index', $song) }}" class="btn btn-secondary">
                <span>←</span> Back to Versions
            </a>
        </div>
    </div>

    <!-- Version Header -->
    <div class="version-preview-header">
        <div class="version-info">
            <div class="version-title-section">
                <h2 class="preview-song-title">{{ $song->title }}</h2>
                <p class="preview-artist">by {{ $song->artist->name }}</p>
                <div class="version-badge-container">
                    <span class="version-badge">{{ $version->version_label }}</span>
                    @if($version->is_default)
                        <span class="default-badge">Default</span>
                    @endif
                </div>
            </div>
            
            <div class="version-meta-info">
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
                        <span class="meta-label">Time:</span>
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

        @if($version->notes)
            <div class="version-notes">
                <h4>📝 Version Notes</h4>
                <p>{{ $version->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Song Structure -->
    <div class="song-structure">
        @if($version->sections->count() > 0)
            @foreach($version->sections as $section)
                <div class="section-preview">
                    <div class="section-header">
                        <h3 class="section-name">{{ $section->section_type }}</h3>
                        <span class="section-order">{{ $section->section_order }}</span>
                    </div>
                    
                    <div class="section-content">
                        @if($section->lines->count() > 0)
                            @foreach($section->lines as $line)
                                <div class="line-preview">
                                    <div class="line-number">{{ $line->line_order }}</div>
                                    <div class="line-content">
                                        @if($line->chordPositions->count() > 0)
                                            <div class="chord-line">
                                                @php
                                                    $lyrics = $line->lyrics_text ?? '';
                                                    $chordLine = str_repeat(' ', strlen($lyrics));
                                                    
                                                    foreach($line->chordPositions as $chord) {
                                                        $pos = min($chord->char_index, strlen($lyrics));
                                                        $chordLine = substr_replace($chordLine, $chord->chord, $pos, strlen($chord->chord));
                                                    }
                                                @endphp
                                                <pre class="chords">{{ $chordLine }}</pre>
                                            </div>
                                        @endif
                                        <div class="lyrics-line">&nbsp;
                                            {{ $line->lyrics_text ?: '(No lyrics)' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-section">
                                <span class="empty-icon">📝</span>
                                <p>No lines added yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-structure">
                <div class="empty-icon">🎼</div>
                <h3>No Sections Yet</h3>
                <p>This version doesn't have any sections yet. Start editing to add song structure.</p>
                <a href="{{ route('songs.versions.edit', [$song, $version]) }}" class="btn btn-primary">
                    <span>✏️</span> Start Editing
                </a>
            </div>
        @endif
    </div>

    <!-- Version Actions -->
    <div class="version-actions-footer">
        <div class="action-group">
            <h4>Version Actions</h4>
            <div class="action-buttons-horizontal">
                <a href="{{ route('songs.versions.edit', [$song, $version]) }}" class="btn btn-primary">
                    <span>✏️</span> Edit Structure
                </a>
                
                <form action="{{ route('songs.versions.duplicate', [$song, $version]) }}" method="POST" class="inline-form">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <span>📋</span> Duplicate Version
                    </button>
                </form>

                @if(!$version->is_default)
                    <form action="{{ route('songs.versions.set-default', [$song, $version]) }}" method="POST" class="inline-form">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline">
                            <span>⭐</span> Set as Default
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if($song->versions()->count() > 1)
            <div class="danger-group">
                <h4>Danger Zone</h4>
                <form action="{{ route('songs.versions.destroy', [$song, $version]) }}" 
                      method="POST" 
                      class="inline-form"
                      onsubmit="return confirmDelete()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <span>🗑️</span> Delete Version
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Version History -->
    <div class="version-history">
        <h4>📅 Version History</h4>
        <div class="history-timeline">
            <div class="history-item">
                <div class="history-icon">➕</div>
                <div class="history-content">
                    <p><strong>Version created</strong></p>
                    <small>{{ $version->created_at->format('F j, Y \a\t g:i A') }}</small>
                </div>
            </div>
            @if($version->created_at != $version->updated_at)
                <div class="history-item">
                    <div class="history-icon">✏️</div>
                    <div class="history-content">
                        <p><strong>Last updated</strong></p>
                        <small>{{ $version->updated_at->format('F j, Y \a\t g:i A') }}</small>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        const versionLabel = '{{ $version->version_label }}';
        return confirm(
            `Are you sure you want to delete the version "${versionName}"?\n\n` +
            `This will permanently delete:\n` +
            `- All sections ({{ $version->sections->count() }})\n` +
            `- All lyrics and chord progressions\n` +
            `- This action cannot be undone.`
        );
    }

    // Print functionality
    function printVersion() {
        window.print();
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // Ctrl+E or Cmd+E to edit
        if ((event.ctrlKey || event.metaKey) && event.key === 'e') {
            event.preventDefault();
            window.location.href = '{{ route("songs.versions.edit", [$song, $version]) }}';
        }
        
        // Ctrl+P or Cmd+P to print (default browser behavior)
        if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
            // Let browser handle this naturally
        }
    });
</script>
@endpush
