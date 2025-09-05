@extends('layouts.admin')

@section('title', 'Create Version - ' . $song->title)

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h1>➕ Create New Version</h1>
            <p>Add a new version for "{{ $song->title }}" by {{ $song->artist->name }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('songs.versions.index', $song) }}" class="btn btn-secondary">
                <span>←</span> Back to Versions
            </a>
        </div>
    </div>

    <!-- Song Context -->
    <div class="context-card">
        <div class="context-info">
            <span class="context-icon">🎵</span>
            <div class="context-text">
                <strong>{{ $song->title }}</strong>
                <small>by {{ $song->artist->name }}</small>
            </div>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form action="{{ route('songs.versions.store', $song) }}" method="POST" class="version-form">
                @csrf
                
                <div class="form-section">
                    <h3 class="section-title">Version Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="version_label" class="form-label required">
                                <span class="label-icon">🎼</span>
                                Version Name
                            </label>
                            <input type="text" 
                                   id="version_label" 
                                   name="version_label" 
                                   class="form-input @error('version_label') error @enderror"
                                   value="{{ old('version_label', 'Original') }}"
                                   placeholder="e.g., Original, Acoustic, Live, Simplified"
                                   required>
                            @error('version_label')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <div class="form-help">
                                Give this version a descriptive name to distinguish it from others
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="is_default" class="form-label checkbox-label">
                                <input type="checkbox" 
                                       id="is_default" 
                                       name="is_default" 
                                       value="1"
                                       class="form-checkbox @error('is_default') error @enderror"
                                       {{ old('is_default') ? 'checked' : '' }}>
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-text">
                                    <span class="label-icon">⭐</span>
                                    Set as Default Version
                                </span>
                            </label>
                            @error('is_default')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <div class="form-help">
                                The default version will be displayed first and used as the primary version
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Version Notes</h3>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="notes" class="form-label">
                                <span class="label-icon">📝</span>
                                Notes (Optional)
                            </label>
                            <textarea id="notes" 
                                      name="notes" 
                                      class="form-textarea @error('notes') error @enderror"
                                      rows="3"
                                      placeholder="Add any notes about this version (e.g., intended for beginners, uses capo on 2nd fret, etc.)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                @if($existingVersions->count() > 0)
                <div class="form-section">
                    <h3 class="section-title">Copy From Existing Version</h3>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="copy_from_version" class="form-label">
                                <span class="label-icon">📋</span>
                                Copy Structure From
                            </label>
                            <select id="copy_from_version" 
                                    name="copy_from_version" 
                                    class="form-select @error('copy_from_version') error @enderror">
                                <option value="">Start with basic structure</option>
                                @foreach($existingVersions as $existing)
                                    <option value="{{ $existing->id }}" 
                                            {{ old('copy_from_version') == $existing->id ? 'selected' : '' }}>
                                        Copy from "{{ $existing->version_label }}"
                                    </option>
                                @endforeach
                            </select>
                            @error('copy_from_version')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <div class="form-help">
                                <strong>Basic structure:</strong> Creates empty sections (Intro, Verse, Chorus, etc.)<br>
                                <strong>Copy from existing:</strong> Duplicates all sections, lyrics, and chords from selected version
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">
                        <span>❌</span> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <span>💾</span> Create Version
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <h4 class="info-title">
                <span class="info-icon">💡</span>
                What happens next?
            </h4>
            <div class="info-content">
                <p>After creating this version, you'll be taken to the versions list where you can:</p>
                <ul class="info-list">
                    <li>✏️ Edit the version to add sections, lyrics, and chords</li>
                    <li>👁️ Preview how the version looks</li>
                    <li>📋 Duplicate the version to create variations</li>
                    <li>⭐ Set it as the default version for this song</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.version-form');
        const submitBtn = form.querySelector('.btn-primary');
        
        // Add loading state on form submit
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>⏳</span> Creating Version...';
        });

        // Clear errors on input
        document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorMsg = this.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            });
        });

        // Auto-generate version name suggestions
        const versionLabelInput = document.getElementById('version_label');
        const suggestions = ['Original', 'Acoustic', 'Electric', 'Live', 'Simplified', 'Advanced', 'Unplugged', 'Studio'];
        
        versionNameInput.addEventListener('focus', function() {
            if (this.value === 'Original') {
                this.select();
            }
        });
    });
</script>
@endpush
