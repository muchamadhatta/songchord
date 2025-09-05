@extends('layouts.admin')

@section('title', 'Create Song')

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1>🎵 Create New Song</h1>
            <p>Add a new song to your collection</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('songs.index') }}" class="btn btn-secondary">
                <span>←</span> Back to Songs
            </a>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('songs.store') }}" method="POST" class="song-form">
                @csrf
                
                <div class="form-section">
                    <h3 class="section-title">Basic Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title" class="form-label required">
                                <span class="label-icon">🎵</span>
                                Song Title
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   class="form-input @error('title') error @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="Enter song title..."
                                   required>
                            @error('title')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="artist_id" class="form-label required">
                                <span class="label-icon">🎤</span>
                                Artist
                            </label>
                            <select id="artist_id" 
                                    name="artist_id" 
                                    class="form-select @error('artist_id') error @enderror"
                                    required>
                                <option value="">Select an artist...</option>
                                @foreach($artists as $artist)
                                    <option value="{{ $artist->id }}" 
                                            {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                                        {{ $artist->name }}
                                        @if($artist->country)
                                            ({{ $artist->country }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('artist_id')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            
                            <div class="form-help">
                                Don't see the artist? 
                                <a href="#" class="help-link">Add new artist</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Musical Details</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="original_key" class="form-label">
                                <span class="label-icon">🎼</span>
                                Original Key
                            </label>
                            <select id="original_key" 
                                    name="original_key" 
                                    class="form-select @error('original_key') error @enderror">
                                <option value="">Select key...</option>
                                @php
                                    $keys = ['C', 'C#', 'Db', 'D', 'D#', 'Eb', 'E', 'F', 'F#', 'Gb', 'G', 'G#', 'Ab', 'A', 'A#', 'Bb', 'B'];
                                    $minorKeys = ['Cm', 'C#m', 'Dm', 'D#m', 'Em', 'Fm', 'F#m', 'Gm', 'G#m', 'Am', 'A#m', 'Bm'];
                                    $allKeys = array_merge($keys, $minorKeys);
                                @endphp
                                @foreach($allKeys as $key)
                                    <option value="{{ $key }}" 
                                            {{ old('original_key') == $key ? 'selected' : '' }}>
                                        {{ $key }}
                                    </option>
                                @endforeach
                            </select>
                            @error('original_key')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bpm" class="form-label">
                                <span class="label-icon">🥁</span>
                                BPM (Beats Per Minute)
                            </label>
                            <input type="number" 
                                   id="bpm" 
                                   name="bpm" 
                                   class="form-input @error('bpm') error @enderror"
                                   value="{{ old('bpm') }}"
                                   placeholder="120"
                                   min="1" 
                                   max="300">
                            @error('bpm')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="time_signature" class="form-label">
                                <span class="label-icon">📏</span>
                                Time Signature
                            </label>
                            <select id="time_signature" 
                                    name="time_signature" 
                                    class="form-select @error('time_signature') error @enderror">
                                <option value="">Select time signature...</option>
                                @php
                                    $timeSignatures = ['4/4', '3/4', '2/4', '6/8', '9/8', '12/8', '2/2', '3/8'];
                                @endphp
                                @foreach($timeSignatures as $timeSig)
                                    <option value="{{ $timeSig }}" 
                                            {{ old('time_signature') == $timeSig ? 'selected' : '' }}>
                                        {{ $timeSig }}
                                    </option>
                                @endforeach
                            </select>
                            @error('time_signature')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="capo" class="form-label">
                                <span class="label-icon">🎸</span>
                                Capo Position
                            </label>
                            <select id="capo" 
                                    name="capo" 
                                    class="form-select @error('capo') error @enderror">
                                <option value="">No capo</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" 
                                            {{ old('capo') == $i ? 'selected' : '' }}>
                                        Capo {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('capo')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Additional Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="youtube_url" class="form-label">
                                <span class="label-icon">📺</span>
                                YouTube URL
                            </label>
                            <input type="url" 
                                   id="youtube_url" 
                                   name="youtube_url" 
                                   class="form-input @error('youtube_url') error @enderror"
                                   value="{{ old('youtube_url') }}"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('youtube_url')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <div class="form-help">
                                Optional: Add a YouTube link for reference
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">
                        <span>❌</span> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span>💾</span> Create Song
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Form validation and UX enhancements
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.song-form');
        const submitBtn = document.querySelector('.btn-primary');
        
        // Add loading state on form submit
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>⏳</span> Creating Song...';
        });

        // Auto-format BPM input
        const bpmInput = document.getElementById('bpm');
        bpmInput.addEventListener('input', function() {
            let value = parseInt(this.value);
            if (value < 1) this.value = '';
            if (value > 300) this.value = 300;
        });

        // YouTube URL validation
        const youtubeInput = document.getElementById('youtube_url');
        youtubeInput.addEventListener('blur', function() {
            const url = this.value;
            if (url && !url.includes('youtube.com') && !url.includes('youtu.be')) {
                this.style.borderColor = '#f59e0b';
            } else {
                this.style.borderColor = '#e5e7eb';
            }
        });

        // Clear errors on input
        document.querySelectorAll('.form-input, .form-select').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorMsg = this.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });
        });
    });
</script>
@endpush