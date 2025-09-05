@extends('layouts.admin')

@section('title', 'Edit ' . $version->version_label . ' - ' . $song->title)

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>✏️ Edit Version</h1>
        <p>Edit "{{ $version->version_label }}" version of "{{ $song->title }}"</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('songs.versions.show', [$song, $version]) }}" class="btn btn-outline">
            <span>👁️</span> Preview
        </a>
        <a href="{{ route('songs.versions.index', $song) }}" class="btn btn-secondary">
            <span>←</span> Back to Versions
        </a>
    </div>
</div>

<!-- Version Info Header -->
<div class="version-edit-header">
    <div class="edit-context">
        <div class="context-info">
            <h2>{{ $song->title }} - {{ $version->version_label }}</h2>
            <p>by {{ $song->artist->name }}</p>
            @if($version->is_default)
            <span class="default-badge">Default Version</span>
            @endif
        </div>
        <div class="edit-tools">
            <button type="button" class="btn btn-tool" onclick="addSection()">
                <span>➕</span> Add Section
            </button>
            <button type="button" class="btn btn-tool" onclick="previewMode()">
                <span>👁️</span> Preview
            </button>
        </div>
    </div>
</div>

<!-- Version Settings Form -->
<div class="version-settings">
    <form action="{{ route('songs.versions.update', [$song, $version]) }}" method="POST" class="settings-form">
        @csrf
        @method('PUT')

        <div class="settings-row">
            <div class="setting-group">
                <label for="version_label" class="setting-label">
                    <span class="label-icon">🎼</span>
                    Version Name
                </label>
                <input type="text"
                    id="version_label"
                    name="version_label"
                    class="setting-input @error('version_label') error @enderror"
                    value="{{ old('version_label', $version->version_label) }}"
                    required>
                @error('version_label')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="setting-group">
                <label for="is_default" class="setting-label checkbox-label">
                    <input type="checkbox"
                        id="is_default"
                        name="is_default"
                        value="1"
                        class="setting-checkbox @error('is_default') error @enderror"
                        {{ old('is_default', $version->is_default) ? 'checked' : '' }}>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-text">
                        <span class="label-icon">⭐</span>
                        Default Version
                    </span>
                </label>
            </div>

            <div class="setting-group full-width">
                <label for="notes" class="setting-label">
                    <span class="label-icon">📝</span>
                    Notes
                </label>
                <textarea id="notes"
                    name="notes"
                    class="setting-textarea @error('notes') error @enderror"
                    rows="2"
                    placeholder="Version notes...">{{ old('notes', $version->notes) }}</textarea>
            </div>

            <div class="setting-actions">
                <button type="submit" class="btn btn-primary btn-sm">
                    <span>💾</span> Save Settings
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Song Structure Editor -->
<div class="structure-editor">
    <div class="editor-header">
        <h3>🎼 Song Structure</h3>
        <div class="editor-actions">
            <button type="button" class="btn btn-sm btn-outline" onclick="reorderSections()">
                <span>🔄</span> Reorder
            </button>
            <button type="button" class="btn btn-sm btn-primary" onclick="addSection()">
                <span>➕</span> Add Section
            </button>
        </div>
    </div>

    <div class="sections-container" id="sections-container">
        @if($version->sections->count() > 0)
        @foreach($version->sections as $section)
        <div class="section-editor" data-section-id="{{ $section->id }}">
            <div class="section-editor-header">
                <div class="section-info">
                    <input type="text"
                        class="section-name-input"
                        value="{{ $section->section_type }}"
                        placeholder="Section name"
                        onchange="updateSectionName({{ $section->id }}, this.value)">
                    <span class="section-order">{{ $section->section_order }}</span>
                </div>
                <div class="section-actions">
                    <button type="button" class="btn-icon" onclick="duplicateSection({{ $section->id }})">📋</button>
                    <button type="button" class="btn-icon" onclick="moveSection({{ $section->id }}, 'up')">⬆️</button>
                    <button type="button" class="btn-icon" onclick="moveSection({{ $section->id }}, 'down')">⬇️</button>
                    <button type="button" class="btn-icon btn-danger" onclick="deleteSection({{ $section->id }})">🗑️</button>
                </div>
            </div>

            <div class="lines-editor">
                @foreach($section->lines as $line)
                <div class="line-editor" data-line-id="{{ $line->id }}">
                    <div class="line-number">{{ $line->line_order }}</div>
                    <div class="line-content">
                        <!-- Interactive Chord-Lyrics Editor -->
                        <div class="chord-lyrics-container" data-line-id="{{ $line->id }}">
                            <!-- Chord positioning area -->
                            <div class="chord-track" ondrop="dropChord(event, {{ $line->id }})" ondragover="allowDrop(event)">
                                @foreach($line->chordPositions->sortBy('char_index') as $chordPos)
                                <div class="chord-pill draggable" 
                                     draggable="true"
                                     data-chord-id="{{ $chordPos->id }}"
                                     data-char-index="{{ $chordPos->char_index }}"
                                     style="left: {{ 8 + ($chordPos->char_index * 10.4) }}px;"
                                     ondragstart="dragStart(event)">
                                    <span class="chord-name" onclick="editChordInline(this)">{{ $chordPos->chord }}</span>
                                    <button type="button" class="chord-remove" onclick="removeChord({{ $chordPos->id }})">×</button>
                                </div>
                                @endforeach
                                <button type="button" class="add-chord-quick" onclick="addQuickChord({{ $line->id }})" title="Add Chord">+</button>
                            </div>
                            
                            <!-- Lyrics with character positions -->
                            <div class="lyrics-track" data-line-id="{{ $line->id }}">
                                <div class="lyrics-ruler">
                                    @for($i = 0; $i < 60; $i++)
                                        <span class="char-marker" data-index="{{ $i }}">{{ $i % 10 == 0 ? $i : '·' }}</span>
                                    @endfor
                                </div>
                                <input type="text"
                                    class="lyrics-input-interactive"
                                    value="{{ $line->lyrics_text }}"
                                    placeholder="Click to add lyrics..."
                                    onkeyup="updateLyricsRealtime({{ $line->id }}, this.value)"
                                    onclick="showCharacterPositions(this)">
                            </div>
                        </div>
                    </div>
                    <div class="line-actions">
                        <button type="button" class="btn-icon" onclick="addLineAfter({{ $line->id }})">➕</button>
                        <button type="button" class="btn-icon btn-danger" onclick="deleteLine({{ $line->id }})">❌</button>
                    </div>
                </div>
                @endforeach

                <button type="button" class="add-line-btn" onclick="addLineToSection({{ $section->id }})">
                    <span>➕</span> Add Line
                </button>
            </div>
        </div>
        @endforeach
        @else
        <div class="empty-structure">
            <div class="empty-icon">🎼</div>
            <h4>No Sections Yet</h4>
            <p>Start building your song structure by adding sections.</p>
            <button type="button" class="btn btn-primary" onclick="addSection()">
                <span>➕</span> Add First Section
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Template for New Sections -->
<template id="section-template">
    <div class="section-editor" data-section-id="">
        <div class="section-editor-header">
            <div class="section-info">
                <input type="text" class="section-name-input" value="New Section" placeholder="Section name">
                <span class="section-order">1</span>
            </div>
            <div class="section-actions">
                <button type="button" class="btn-icon">📋</button>
                <button type="button" class="btn-icon">⬆️</button>
                <button type="button" class="btn-icon">⬇️</button>
                <button type="button" class="btn-icon btn-danger">🗑️</button>
            </div>
        </div>
        <div class="lines-editor">
            <div class="line-editor" data-line-id="">
                <div class="line-number">1</div>
                <div class="line-content">
                    <div class="chord-line">
                        <div class="chord-positions" data-line-id="">
                            <!-- Chord markers will be added dynamically -->
                        </div>
                        <button type="button" class="add-chord-btn">
                            + Add Chord
                        </button>
                    </div>
                    <div class="lyrics-editor">
                        <input type="text" class="lyrics-input" placeholder="Click to add lyrics...">
                    </div>
                </div>
                <div class="line-actions">
                    <button type="button" class="btn-icon">➕</button>
                    <button type="button" class="btn-icon btn-danger">❌</button>
                </div>
            </div>
            <button type="button" class="add-line-btn">
                <span>➕</span> Add Line
            </button>
        </div>
    </div>
</template>

<!-- Auto-save Status -->
<div class="autosave-status" id="autosave-status">
    <span class="status-icon">💾</span>
    <span class="status-text">All changes saved</span>
</div>
@endsection

@push('scripts')
<script>
    // Auto-save functionality
    let autoSaveTimeout;

    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    // Base URLs
    const baseUrl = '{{ url("/") }}';
    const songId = {{ $song->id }};
    const versionId = {{ $version->id }};

    function autoSave() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            updateAutoSaveStatus('saving');
            setTimeout(() => {
                updateAutoSaveStatus('saved');
            }, 1000);
        }, 2000);
    }

    function updateAutoSaveStatus(status) {
        const statusEl = document.getElementById('autosave-status');
        const iconEl = statusEl.querySelector('.status-icon');
        const textEl = statusEl.querySelector('.status-text');

        switch (status) {
            case 'saving':
                iconEl.textContent = '⏳';
                textEl.textContent = 'Saving...';
                statusEl.className = 'autosave-status saving';
                break;
            case 'saved':
                iconEl.textContent = '✅';
                textEl.textContent = 'All changes saved';
                statusEl.className = 'autosave-status saved';
                break;
            case 'error':
                iconEl.textContent = '❌';
                textEl.textContent = 'Error saving';
                statusEl.className = 'autosave-status error';
                break;
        }
    }

    // AJAX Helper Function
    function ajaxRequest(url, method, data = {}) {
        return fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: method !== 'GET' ? JSON.stringify(data) : null
        }).then(response => response.json());
    }

    // Section management
    function addSection() {
        const sectionsContainer = document.getElementById('sections-container');
        const sectionCount = sectionsContainer.querySelectorAll('.section-editor').length;
        const newOrder = sectionCount + 1;

        const data = {
            section_type: 'New Section',
            section_order: newOrder
        };

        updateAutoSaveStatus('saving');
        ajaxRequest(`${baseUrl}/songs/${songId}/versions/${versionId}/sections`, 'POST', data)
            .then(response => {
                if (response.success) {
                    const sectionHtml = createSectionHTML(response.section);
                    sectionsContainer.insertAdjacentHTML('beforeend', sectionHtml);
                    updateAutoSaveStatus('saved');
                } else {
                    updateAutoSaveStatus('error');
                    console.error('Failed to add section:', response);
                }
            })
            .catch(error => {
                updateAutoSaveStatus('error');
                console.error('Error adding section:', error);
            });
    }

    function deleteSection(sectionId) {
        if (confirm('Delete this section and all its content?')) {
            updateAutoSaveStatus('saving');
            ajaxRequest(`${baseUrl}/sections/${sectionId}`, 'DELETE')
                .then(response => {
                    if (response.success) {
                        document.querySelector(`[data-section-id="${sectionId}"]`).remove();
                        updateAutoSaveStatus('saved');
                    } else {
                        updateAutoSaveStatus('error');
                        console.error('Failed to delete section:', response);
                    }
                })
                .catch(error => {
                    updateAutoSaveStatus('error');
                    console.error('Error deleting section:', error);
                });
        }
    }

    function updateSectionName(sectionId, name) {
        const data = {
            section_type: name
        };

        updateAutoSaveStatus('saving');
        ajaxRequest(`${baseUrl}/sections/${sectionId}`, 'PATCH', data)
            .then(response => {
                if (response.success) {
                    updateAutoSaveStatus('saved');
                } else {
                    updateAutoSaveStatus('error');
                    console.error('Failed to update section name:', response);
                }
            })
            .catch(error => {
                updateAutoSaveStatus('error');
                console.error('Error updating section name:', error);
            });
    }

    // Line management
    function addLineToSection(sectionId) {
        const section = document.querySelector(`[data-section-id="${sectionId}"]`);
        const lines = section.querySelectorAll('.line-editor');
        const newOrder = lines.length + 1;

        const data = {
            line_order: newOrder,
            lyrics_text: ''
        };

        updateAutoSaveStatus('saving');
        ajaxRequest(`${baseUrl}/sections/${sectionId}/lines`, 'POST', data)
            .then(response => {
                if (response.success) {
                    const lineHtml = createLineHTML(response.line);
                    const addButton = section.querySelector('.add-line-btn');
                    addButton.insertAdjacentHTML('beforebegin', lineHtml);
                    updateAutoSaveStatus('saved');
                } else {
                    updateAutoSaveStatus('error');
                    console.error('Failed to add line:', response);
                }
            })
            .catch(error => {
                updateAutoSaveStatus('error');
                console.error('Error adding line:', error);
            });
    }

    function addLineAfter(lineId) {
        const currentLine = document.querySelector(`[data-line-id="${lineId}"]`);
        const section = currentLine.closest('.section-editor');
        const sectionId = section.getAttribute('data-section-id');
        const lines = section.querySelectorAll('.line-editor');
        const currentOrder = parseInt(currentLine.querySelector('.line-number').textContent);
        const newOrder = currentOrder + 1;

        // Update order numbers for subsequent lines
        lines.forEach(line => {
            const lineNumber = line.querySelector('.line-number');
            const order = parseInt(lineNumber.textContent);
            if (order > currentOrder) {
                lineNumber.textContent = order + 1;
            }
        });

        const data = {
            line_order: newOrder,
            lyrics_text: ''
        };

        updateAutoSaveStatus('saving');
        ajaxRequest(`${baseUrl}/sections/${sectionId}/lines`, 'POST', data)
            .then(response => {
                if (response.success) {
                    const lineHtml = createLineHTML(response.line);
                    currentLine.insertAdjacentHTML('afterend', lineHtml);
                    updateAutoSaveStatus('saved');
                } else {
                    updateAutoSaveStatus('error');
                    console.error('Failed to add line:', response);
                }
            })
            .catch(error => {
                updateAutoSaveStatus('error');
                console.error('Error adding line:', error);
            });
    }

    function deleteLine(lineId) {
        if (confirm('Delete this line?')) {
            updateAutoSaveStatus('saving');
            ajaxRequest(`${baseUrl}/lines/${lineId}`, 'DELETE')
                .then(response => {
                    if (response.success) {
                        document.querySelector(`[data-line-id="${lineId}"]`).remove();
                        updateAutoSaveStatus('saved');
                    } else {
                        updateAutoSaveStatus('error');
                        console.error('Failed to delete line:', response);
                    }
                })
                .catch(error => {
                    updateAutoSaveStatus('error');
                    console.error('Error deleting line:', error);
                });
        }
    }

    // ===== DRAG & DROP CHORD MANAGEMENT =====
    
    let draggedChord = null;
    let dragStartX = 0;
    const CHAR_WIDTH = 10.4; // pixels per character (monospace font)
    const PADDING_LEFT = 8; // 0.5rem = 8px padding to match input
    
    function dragStart(event) {
        draggedChord = event.target;
        dragStartX = event.clientX;
        draggedChord.classList.add('dragging');
        
        // Store original position for potential revert
        draggedChord.setAttribute('data-original-left', draggedChord.style.left);
    }
    
    function allowDrop(event) {
        event.preventDefault();
        const chordTrack = event.currentTarget;
        chordTrack.classList.add('drag-over');
        
        // Show position indicator
        showPositionIndicator(event, chordTrack);
    }
    
    function dropChord(event, lineId) {
        event.preventDefault();
        const chordTrack = event.currentTarget;
        chordTrack.classList.remove('drag-over');
        
        if (!draggedChord) return;
        
        // Calculate new character index based on drop position
        const rect = chordTrack.getBoundingClientRect();
        const x = event.clientX - rect.left - PADDING_LEFT; // Account for padding
        const charIndex = Math.max(0, Math.round(x / CHAR_WIDTH)); // Ensure non-negative
        
        // Update chord position
        updateChordPosition(draggedChord.getAttribute('data-chord-id'), charIndex);
        
        // Clean up
        draggedChord.classList.remove('dragging');
        removePositionIndicator();
        draggedChord = null;
    }
    
    function showPositionIndicator(event, chordTrack) {
        removePositionIndicator();
        
        const rect = chordTrack.getBoundingClientRect();
        const x = event.clientX - rect.left - PADDING_LEFT; // Account for padding
        const charIndex = Math.max(0, Math.round(x / CHAR_WIDTH));
        
        const indicator = document.createElement('div');
        indicator.className = 'position-indicator';
        indicator.style.left = (PADDING_LEFT + charIndex * CHAR_WIDTH) + 'px';
        indicator.setAttribute('data-char-index', charIndex);
        chordTrack.appendChild(indicator);
    }
    
    function removePositionIndicator() {
        document.querySelectorAll('.position-indicator').forEach(el => el.remove());
    }
    
    // Handle drag leave to remove visual feedback
    document.addEventListener('dragleave', function(event) {
        if (event.target.classList.contains('chord-track')) {
            event.target.classList.remove('drag-over');
            removePositionIndicator();
        }
    });
    
    function addQuickChord(lineId) {
        const chordName = prompt('Enter chord name (e.g., C, Am, F, G):');
        if (chordName && chordName.trim()) {
            // Add at position 0 by default, user can drag to reposition
            addChordToLine(lineId, 0, chordName.trim());
        }
    }
    
    function editChordInline(chordNameElement) {
        const currentName = chordNameElement.textContent;
        const newName = prompt('Edit chord name:', currentName);
        
        if (newName && newName !== currentName) {
            const chordPill = chordNameElement.closest('.chord-pill');
            const chordId = chordPill.getAttribute('data-chord-id');
            
            updateAutoSaveStatus('saving');
            ajaxRequest(`${baseUrl}/chord-positions/${chordId}`, 'PATCH', {
                chord: newName.trim()
            })
            .then(response => {
                if (response.success) {
                    chordNameElement.textContent = newName.trim();
                    updateAutoSaveStatus('saved');
                } else {
                    updateAutoSaveStatus('error');
                    console.error('Failed to update chord:', response);
                }
            })
            .catch(error => {
                updateAutoSaveStatus('error');
                console.error('Error updating chord:', error);
            });
        }
    }
    
    function updateChordPosition(chordId, newCharIndex) {
        updateAutoSaveStatus('saving');
        ajaxRequest(`${baseUrl}/chord-positions/${chordId}`, 'PATCH', {
            char_index: newCharIndex
        })
        .then(response => {
            if (response.success) {
                // Update UI position
                const chordPill = document.querySelector(`[data-chord-id="${chordId}"]`);
                chordPill.style.left = (PADDING_LEFT + newCharIndex * CHAR_WIDTH) + 'px';
                chordPill.setAttribute('data-char-index', newCharIndex);
                updateAutoSaveStatus('saved');
            } else {
                // Revert position on failure
                const chordPill = document.querySelector(`[data-chord-id="${chordId}"]`);
                chordPill.style.left = chordPill.getAttribute('data-original-left');
                updateAutoSaveStatus('error');
                console.error('Failed to update chord position:', response);
            }
        })
        .catch(error => {
            // Revert position on error
            const chordPill = document.querySelector(`[data-chord-id="${chordId}"]`);
            chordPill.style.left = chordPill.getAttribute('data-original-left');
            updateAutoSaveStatus('error');
            console.error('Error updating chord position:', error);
        });
    }

    function addChordToLine(lineId, charIndex, chordName) {
        const data = {
            char_index: charIndex,
            chord: chordName
            // display_order will be auto-generated in backend
        };

        updateAutoSaveStatus('saving');
        ajaxRequest(`${baseUrl}/lines/${lineId}/chords`, 'POST', data)
            .then(response => {
                if (response.success) {
                    // Add chord marker to UI
                    addChordMarkerToLine(lineId, response.chord_position);
                    updateAutoSaveStatus('saved');
                } else {
                    updateAutoSaveStatus('error');
                    console.error('Failed to add chord:', response);
                }
            })
            .catch(error => {
                updateAutoSaveStatus('error');
                console.error('Error adding chord:', error);
            });
    }

    function addChordMarkerToLine(lineId, chordPosition) {
        const chordTrack = document.querySelector(`[data-line-id="${lineId}"] .chord-track`);
        if (chordTrack) {
            const chordPillHtml = `
                <div class="chord-pill draggable" 
                     draggable="true"
                     data-chord-id="${chordPosition.id}"
                     data-char-index="${chordPosition.char_index}"
                     style="left: ${8 + (chordPosition.char_index * 10.4)}px;"
                     ondragstart="dragStart(event)">
                    <span class="chord-name" onclick="editChordInline(this)">${chordPosition.chord}</span>
                    <button type="button" class="chord-remove" onclick="removeChord(${chordPosition.id})">×</button>
                </div>
            `;
            
            // Insert before the add button
            const addButton = chordTrack.querySelector('.add-chord-quick');
            addButton.insertAdjacentHTML('beforebegin', chordPillHtml);
        }
    }
    
    function updateLyricsRealtime(lineId, lyricsText) {
        // Debounce the update
        clearTimeout(window.lyricsUpdateTimeout);
        window.lyricsUpdateTimeout = setTimeout(() => {
            updateAutoSaveStatus('saving');
            ajaxRequest(`${baseUrl}/lines/${lineId}`, 'PATCH', {
                lyrics_text: lyricsText
            })
            .then(response => {
                if (response.success) {
                    updateAutoSaveStatus('saved');
                } else {
                    updateAutoSaveStatus('error');
                    console.error('Failed to update lyrics:', response);
                }
            })
            .catch(error => {
                updateAutoSaveStatus('error');
                console.error('Error updating lyrics:', error);
            });
        }, 800); // Wait 800ms after user stops typing
    }
    
    function showCharacterPositions(input) {
        input.classList.add('show-positions');
        
        // Remove after 3 seconds
        setTimeout(() => {
            input.classList.remove('show-positions');
        }, 3000);
    }

    function updateChordName(chordPositionId, newName) {
        const data = {
            chord: newName
        };

        updateAutoSaveStatus('saving');
        ajaxRequest(`${baseUrl}/chord-positions/${chordPositionId}`, 'PATCH', data)
            .then(response => {
                if (response.success) {
                    updateAutoSaveStatus('saved');
                } else {
                    updateAutoSaveStatus('error');
                    console.error('Failed to update chord name:', response);
                }
            })
            .catch(error => {
                updateAutoSaveStatus('error');
                console.error('Error updating chord name:', error);
            });
    }

    function removeChord(chordPositionId) {
        if (confirm('Remove this chord?')) {
            updateAutoSaveStatus('saving');
            ajaxRequest(`${baseUrl}/chord-positions/${chordPositionId}`, 'DELETE')
                .then(response => {
                    if (response.success) {
                        document.querySelector(`[data-chord-id="${chordPositionId}"]`).remove();
                        updateAutoSaveStatus('saved');
                    } else {
                        updateAutoSaveStatus('error');
                        console.error('Failed to remove chord:', response);
                    }
                })
                .catch(error => {
                    updateAutoSaveStatus('error');
                    console.error('Error removing chord:', error);
                });
        }
    }

    // Remove the old updateChords function since we now handle chords individually

    // HTML generation functions
    function createSectionHTML(section) {
        return `
            <div class="section-editor" data-section-id="${section.id}">
                <div class="section-editor-header">
                    <div class="section-info">
                        <input type="text" 
                               class="section-name-input" 
                               value="${section.section_type}"
                               placeholder="Section name"
                               onchange="updateSectionName(${section.id}, this.value)">
                        <span class="section-order">${section.section_order}</span>
                    </div>
                    <div class="section-actions">
                        <button type="button" class="btn-icon" onclick="duplicateSection(${section.id})">📋</button>
                        <button type="button" class="btn-icon" onclick="moveSection(${section.id}, 'up')">⬆️</button>
                        <button type="button" class="btn-icon" onclick="moveSection(${section.id}, 'down')">⬇️</button>
                        <button type="button" class="btn-icon btn-danger" onclick="deleteSection(${section.id})">🗑️</button>
                    </div>
                </div>
                <div class="lines-editor">
                    ${section.lines ? section.lines.map(line => createLineHTML(line)).join('') : ''}
                    <button type="button" class="add-line-btn" onclick="addLineToSection(${section.id})">
                        <span>➕</span> Add Line
                    </button>
                </div>
            </div>
        `;
    }

    function createLineHTML(line) {
        const chordPillsHtml = line.chordPositions ?
            line.chordPositions.map(cp => `
                <div class="chord-pill draggable" 
                     draggable="true"
                     data-chord-id="${cp.id}"
                     data-char-index="${cp.char_index}"
                     style="left: ${8 + (cp.char_index * 10.4)}px;"
                     ondragstart="dragStart(event)">
                    <span class="chord-name" onclick="editChordInline(this)">${cp.chord}</span>
                    <button type="button" class="chord-remove" onclick="removeChord(${cp.id})">×</button>
                </div>
            `).join('') : '';

        return `
            <div class="line-editor" data-line-id="${line.id}">
                <div class="line-number">${line.line_order}</div>
                <div class="line-content">
                    <div class="chord-lyrics-container" data-line-id="${line.id}">
                        <div class="chord-track" ondrop="dropChord(event, ${line.id})" ondragover="allowDrop(event)">
                            ${chordPillsHtml}
                            <button type="button" class="add-chord-quick" onclick="addQuickChord(${line.id})" title="Add Chord">+</button>
                        </div>
                        <div class="lyrics-track" data-line-id="${line.id}">
                            <div class="lyrics-ruler">
                                ${Array.from({length: 60}, (_, i) => `
                                    <span class="char-marker" data-index="${i}">${i % 10 == 0 ? i : '·'}</span>
                                `).join('')}
                            </div>
                            <input type="text"
                                class="lyrics-input-interactive"
                                value="${line.lyrics_text || ''}"
                                placeholder="Click to add lyrics..."
                                onkeyup="updateLyricsRealtime(${line.id}, this.value)"
                                onclick="showCharacterPositions(this)">
                        </div>
                    </div>
                </div>
                <div class="line-actions">
                    <button type="button" class="btn-icon" onclick="addLineAfter(${line.id})">➕</button>
                    <button type="button" class="btn-icon btn-danger" onclick="deleteLine(${line.id})">❌</button>
                </div>
            </div>
        `;
    }

    // Section movement functions
    function moveSection(sectionId, direction) {
        const data = {
            direction: direction
        };

        updateAutoSaveStatus('saving');
        ajaxRequest(`${baseUrl}/sections/${sectionId}/move`, 'PATCH', data)
            .then(response => {
                if (response.success) {
                    // Reload the page to show the new order
                    // Or we could implement dynamic reordering in the UI
                    location.reload();
                } else {
                    updateAutoSaveStatus('error');
                    console.error('Failed to move section:', response);
                }
            })
            .catch(error => {
                updateAutoSaveStatus('error');
                console.error('Error moving section:', error);
            });
    }

    function duplicateSection(sectionId) {
        if (confirm('Duplicate this section with all its content?')) {
            updateAutoSaveStatus('saving');
            ajaxRequest(`${baseUrl}/sections/${sectionId}/duplicate`, 'POST', {})
                .then(response => {
                    if (response.success) {
                        // Reload to show the duplicated section
                        location.reload();
                    } else {
                        updateAutoSaveStatus('error');
                        console.error('Failed to duplicate section:', response);
                    }
                })
                .catch(error => {
                    updateAutoSaveStatus('error');
                    console.error('Error duplicating section:', error);
                });
        }
    }

    // Preview mode
    function previewMode() {
        window.location.href = '{{ route("songs.versions.show", [$song, $version]) }}';
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Add CSRF token to meta if not exists
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.head.appendChild(meta);
        }

        // Add event listeners for auto-save
        document.addEventListener('input', function(event) {
            if (event.target.matches('.lyrics-input, .chord-input, .section-name-input')) {
                // Debounce the auto-save
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    if (event.target.classList.contains('lyrics-input')) {
                        const lineId = event.target.closest('.line-editor').getAttribute('data-line-id');
                        updateLyrics(lineId, event.target.value);
                    } else if (event.target.classList.contains('chord-input')) {
                        const lineId = event.target.closest('.line-editor').getAttribute('data-line-id');
                        updateChords(lineId, event.target.value);
                    } else if (event.target.classList.contains('section-name-input')) {
                        const sectionId = event.target.closest('.section-editor').getAttribute('data-section-id');
                        updateSectionName(sectionId, event.target.value);
                    }
                }, 1000);
            }
        });
    });
</script>
@endpush