@extends('layouts.admin')

@section('title', 'Songs')

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1>🎵 Songs Management</h1>
            <p>Manage your song collection</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('songs.create') }}" class="btn btn-primary">
                <span>➕</span> Add New Song
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('songs.index') }}" class="filters-form">
            <div class="filter-group">
                <input type="text" 
                       name="search" 
                       placeholder="Search songs or artists..." 
                       value="{{ request('search') }}"
                       class="search-input">
            </div>
            
            <div class="filter-group">
                <select name="artist" class="filter-select">
                    <option value="">All Artists</option>
                    @foreach($artists as $artist)
                        <option value="{{ $artist->id }}" 
                                {{ request('artist') == $artist->id ? 'selected' : '' }}>
                            {{ $artist->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <select name="key" class="filter-select">
                    <option value="">All Keys</option>
                    @foreach($keys as $key)
                        <option value="{{ $key }}" 
                                {{ request('key') == $key ? 'selected' : '' }}>
                            {{ $key }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-filter">🔍 Filter</button>
                <a href="{{ route('songs.index') }}" class="btn btn-clear">🗑️ Clear</a>
            </div>
        </form>
    </div>

    <!-- Songs Table -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Key</th>
                        <th>BPM</th>
                        <th>Time Sig.</th>
                        <th>Capo</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($songs as $song)
                        <tr>
                            <td class="song-title">
                                <div class="title-info">
                                    <strong>{{ $song->title }}</strong>
                                    @if($song->youtube_url)
                                        <a href="{{ $song->youtube_url }}" target="_blank" class="youtube-link">
                                            📺
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $song->artist->name }}</td>
                            <td>
                                @if($song->original_key)
                                    <span class="key-badge">{{ $song->original_key }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($song->bpm)
                                    {{ $song->bpm }} BPM
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($song->time_signature)
                                    {{ $song->time_signature }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($song->capo)
                                    Capo {{ $song->capo }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $song->creator->name ?? 'System' }}</td>
                            <td>{{ $song->created_at->format('M d, Y') }}</td>
                            <td class="actions">
                                <div class="action-buttons">
                                    <a href="{{ route('songs.show', $song) }}" class="btn-action btn-view" title="View">👁️</a>
                                    <a href="{{ route('songs.edit', $song) }}" class="btn-action btn-edit" title="Edit">✏️</a>
                                    <a href="{{ route('songs.versions.index', $song) }}" class="btn-action btn-versions" title="Versions">🎼</a>
                                    <form action="{{ route('songs.destroy', $song) }}" method="POST" class="delete-form">
                                        <button type="submit" class="btn-action btn-delete" title="Delete">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="no-data">
                                <div class="empty-state">
                                    <span class="empty-icon">🎵</span>
                                    <h3>No Songs Found</h3>
                                    <p>Start by adding your first song to the collection.</p>
                                    <a href="{{ route('songs.create') }}" class="btn btn-primary">
                                        ➕ Add New Song
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($songs->hasPages())
            <div class="pagination-wrapper">
                {{ $songs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection