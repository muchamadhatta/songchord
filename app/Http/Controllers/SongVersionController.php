<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\SongVersion;
use App\Models\SongSection;
use App\Models\SongLine;
use App\Models\ChordPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SongVersionController extends Controller
{
    /**
     * Display versions for a specific song
     */
    public function index(Song $song)
    {
        $versions = $song->versions()
            ->with(['sections.lines.chordPositions'])
            ->orderByDesc('is_default')
            ->orderBy('version_label')
            ->get();

        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Songs', 'url' => route('songs.index')],
            ['title' => $song->title, 'url' => route('songs.show', $song)],
            ['title' => 'Versions']
        ];

        return view('admin.song-versions.index', compact('song', 'versions', 'breadcrumbs'));
    }

    /**
     * Show form to create new version
     */
    public function create(Song $song)
    {
        // Check if song has any versions to potentially copy from
        $existingVersions = $song->versions()->select('id', 'version_label')->get();
        
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Songs', 'url' => route('songs.index')],
            ['title' => $song->title, 'url' => route('songs.show', $song)],
            ['title' => 'Versions', 'url' => route('songs.versions.index', $song)],
            ['title' => 'Create Version']
        ];

        return view('admin.song-versions.create', compact('song', 'existingVersions', 'breadcrumbs'));
    }

    /**
     * Store new version
     */
    public function store(Request $request, Song $song)
    {
        $validated = $request->validate([
            'version_label' => [
                'required',
                'string',
                'max:255',
                Rule::unique('song_versions')->where(function ($query) use ($song) {
                    return $query->where('song_id', $song->id);
                })
            ],
            'notes' => 'nullable|string|max:1000',
            'is_default' => 'boolean',
            'copy_from_version' => 'nullable|exists:song_versions,id'
        ]);

        DB::beginTransaction();
        
        try {
            // If this is set as default, unset other defaults
            if ($validated['is_default'] ?? false) {
                $song->versions()->update(['is_default' => false]);
            }

            // If no versions exist yet, make this default
            if ($song->versions()->count() === 0) {
                $validated['is_default'] = true;
            }

            // Prepare data for creating version (exclude copy_from_version)
            $versionData = [
                'song_id' => $song->id,
                'version_label' => $validated['version_label'],
                'notes' => $validated['notes'] ?? null,
                'is_default' => $validated['is_default'] ?? false,
                'created_by' => Auth::id(),
            ];
            
            $version = SongVersion::create($versionData);

            // Copy structure from existing version if specified
            if (!empty($validated['copy_from_version'])) {
                $this->copyVersionStructure($validated['copy_from_version'], $version->id);
            } else {
                // Create basic structure for new version
                $this->createBasicStructure($version->id);
            }

            DB::commit();

            return redirect()
                ->route('songs.versions.index', $song)
                ->with('success', 'Version created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create version: ' . $e->getMessage());
        }
    }

    /**
     * Show specific version
     */
    public function show(Song $song, SongVersion $version)
    {
        // Ensure version belongs to song
        if ($version->song_id !== $song->id) {
            abort(404);
        }

        $version->load([
            'sections' => function($query) {
                $query->orderBy('section_order');
            },
            'sections.lines' => function($query) {
                $query->orderBy('line_order');
            },
            'sections.lines.chordPositions' => function($query) {
                $query->orderBy('char_index');
            }
        ]);

        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Songs', 'url' => route('songs.index')],
            ['title' => $song->title, 'url' => route('songs.show', $song)],
            ['title' => 'Versions', 'url' => route('songs.versions.index', $song)],
            ['title' => $version->version_label]
        ];

        return view('admin.song-versions.show', compact('song', 'version', 'breadcrumbs'));
    }

    /**
     * Show form to edit version
     */
    public function edit(Song $song, SongVersion $version)
    {
        // Ensure version belongs to song
        if ($version->song_id !== $song->id) {
            abort(404);
        }

        $version->load([
            'sections' => function($query) {
                $query->orderBy('section_order');
            },
            'sections.lines' => function($query) {
                $query->orderBy('line_order');
            },
            'sections.lines.chordPositions' => function($query) {
                $query->orderBy('char_index');
            }
        ]);

        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Songs', 'url' => route('songs.index')],
            ['title' => $song->title, 'url' => route('songs.show', $song)],
            ['title' => 'Versions', 'url' => route('songs.versions.index', $song)],
            ['title' => $version->version_label, 'url' => route('songs.versions.show', [$song, $version])],
            ['title' => 'Edit']
        ];

        return view('admin.song-versions.edit', compact('song', 'version', 'breadcrumbs'));
    }

    /**
     * Update version
     */
    public function update(Request $request, Song $song, SongVersion $version)
    {
        // Ensure version belongs to song
        if ($version->song_id !== $song->id) {
            abort(404);
        }

        $validated = $request->validate([
            'version_label' => [
                'required',
                'string',
                'max:255',
                Rule::unique('song_versions')->where(function ($query) use ($song) {
                    return $query->where('song_id', $song->id);
                })->ignore($version->id)
            ],
            'notes' => 'nullable|string|max:1000',
            'is_default' => 'boolean'
        ]);

        DB::beginTransaction();
        
        try {
            // If this is set as default, unset other defaults
            if ($validated['is_default'] ?? false) {
                $song->versions()->where('id', '!=', $version->id)->update(['is_default' => false]);
            }

            $version->update($validated);

            DB::commit();

            return redirect()
                ->route('songs.versions.index', $song)
                ->with('success', 'Version updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update version: ' . $e->getMessage());
        }
    }

    /**
     * Delete version
     */
    public function destroy(Song $song, SongVersion $version)
    {
        // Ensure version belongs to song
        if ($version->song_id !== $song->id) {
            abort(404);
        }

        // Prevent deleting the last version
        if ($song->versions()->count() <= 1) {
            return back()->with('error', 'Cannot delete the last version of a song.');
        }

        // If deleting default version, set another one as default
        if ($version->is_default) {
            $song->versions()
                ->where('id', '!=', $version->id)
                ->first()
                ->update(['is_default' => true]);
        }

        // Delete version and all related data (cascade)
        $version->delete();

        return redirect()
            ->route('songs.versions.index', $song)
            ->with('success', 'Version deleted successfully!');
    }

    /**
     * Set version as default
     */
    public function setDefault(Song $song, SongVersion $version)
    {
        // Ensure version belongs to song
        if ($version->song_id !== $song->id) {
            abort(404);
        }

        DB::beginTransaction();
        
        try {
            // Unset all defaults
            $song->versions()->update(['is_default' => false]);
            
            // Set this version as default
            $version->update(['is_default' => true]);

            DB::commit();

            return back()->with('success', 'Default version updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->with('error', 'Failed to set default version: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate version
     */
    public function duplicate(Song $song, SongVersion $version)
    {
        // Ensure version belongs to song
        if ($version->song_id !== $song->id) {
            abort(404);
        }

        DB::beginTransaction();
        
        try {
            // Create new version
            $newVersion = SongVersion::create([
                'song_id' => $song->id,
                'version_label' => $version->version_label . ' (Copy)',
                'notes' => $version->notes,
                'is_default' => false,
                'created_by' => Auth::id()
            ]);

            // Copy structure
            $this->copyVersionStructure($version->id, $newVersion->id);

            DB::commit();

            return redirect()
                ->route('songs.versions.edit', [$song, $newVersion])
                ->with('success', 'Version duplicated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->with('error', 'Failed to duplicate version: ' . $e->getMessage());
        }
    }

    /**
     * Copy structure from one version to another
     */
    private function copyVersionStructure($fromVersionId, $toVersionId)
    {
        $fromVersion = SongVersion::with([
            'sections.lines.chordPositions'
        ])->findOrFail($fromVersionId);

        foreach ($fromVersion->sections as $section) {
            $newSection = SongSection::create([
                'song_version_id' => $toVersionId,
                'section_type' => $section->section_type,
                'section_order' => $section->section_order
            ]);

            foreach ($section->lines as $line) {
                $newLine = SongLine::create([
                    'section_id' => $newSection->id,
                    'line_order' => $line->line_order,
                    'lyrics_text' => $line->lyrics_text
                ]);

                foreach ($line->chordPositions as $chord) {
                    ChordPosition::create([
                        'line_id' => $newLine->id,
                        'char_index' => $chord->char_index,
                        'chord' => $chord->chord,
                        'display_order' => $chord->display_order
                    ]);
                }
            }
        }
    }

    /**
     * Create basic structure for new version
     */
    private function createBasicStructure($versionId)
    {
        $basicSections = [
            ['name' => 'Intro', 'order' => 1],
            ['name' => 'Verse 1', 'order' => 2],
            ['name' => 'Chorus', 'order' => 3],
            ['name' => 'Verse 2', 'order' => 4],
            ['name' => 'Chorus', 'order' => 5],
            ['name' => 'Bridge', 'order' => 6],
            ['name' => 'Chorus', 'order' => 7],
            ['name' => 'Outro', 'order' => 8],
        ];

        foreach ($basicSections as $sectionData) {
            $section = SongSection::create([
                'song_version_id' => $versionId,
                'section_type' => $sectionData['name'],
                'section_order' => $sectionData['order']
            ]);

            // Create placeholder line
            SongLine::create([
                'section_id' => $section->id,
                'line_order' => 1,
                'lyrics_text' => '(Add lyrics here)'
            ]);
        }
    }

    // ===== AJAX API Methods for Structure Editing =====
    
    /**
     * Store new section via AJAX
     */
    public function storeSection(Request $request, Song $song, SongVersion $version)
    {
        $validated = $request->validate([
            'section_type' => 'required|string|max:32',
            'section_order' => 'required|integer|min:1'
        ]);

        if ($version->song_id !== $song->id) {
            return response()->json(['error' => 'Version does not belong to song'], 404);
        }

        $section = SongSection::create([
            'song_version_id' => $version->id,
            'section_type' => $validated['section_type'],
            'section_order' => $validated['section_order']
        ]);

        // Create a default line
        $line = SongLine::create([
            'section_id' => $section->id,
            'line_order' => 1,
            'lyrics_text' => ''
        ]);

        return response()->json([
            'success' => true,
            'section' => $section->load('lines'),
            'message' => 'Section created successfully'
        ]);
    }

    /**
     * Update section via AJAX
     */
    public function updateSection(Request $request, SongSection $section)
    {
        $validated = $request->validate([
            'section_type' => 'required|string|max:32',
            'section_order' => 'sometimes|integer|min:1'
        ]);

        $section->update($validated);

        return response()->json([
            'success' => true,
            'section' => $section,
            'message' => 'Section updated successfully'
        ]);
    }

    /**
     * Delete section via AJAX
     */
    public function deleteSection(SongSection $section)
    {
        $section->delete(); // Will cascade delete lines and chord positions

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully'
        ]);
    }

    /**
     * Store new line via AJAX
     */
    public function storeLine(Request $request, SongSection $section)
    {
        $validated = $request->validate([
            'line_order' => 'required|integer|min:1',
            'lyrics_text' => 'nullable|string'
        ]);

        $line = SongLine::create([
            'section_id' => $section->id,
            'line_order' => $validated['line_order'],
            'lyrics_text' => $validated['lyrics_text'] ?? ''
        ]);

        return response()->json([
            'success' => true,
            'line' => $line->load('chordPositions'),
            'message' => 'Line created successfully'
        ]);
    }

    /**
     * Update line via AJAX
     */
    public function updateLine(Request $request, SongLine $line)
    {
        $validated = $request->validate([
            'lyrics_text' => 'nullable|string',
            'line_order' => 'sometimes|integer|min:1'
        ]);

        $line->update($validated);

        return response()->json([
            'success' => true,
            'line' => $line,
            'message' => 'Line updated successfully'
        ]);
    }

    /**
     * Delete line via AJAX
     */
    public function deleteLine(SongLine $line)
    {
        $line->delete(); // Will cascade delete chord positions

        return response()->json([
            'success' => true,
            'message' => 'Line deleted successfully'
        ]);
    }

    /**
     * Update chords for a line via AJAX
     */
    public function updateChords(Request $request, SongLine $line)
    {
        $validated = $request->validate([
            'chords' => 'required|string'
        ]);

        // Delete existing chord positions
        $line->chordPositions()->delete();

        // Parse chords string and create positions
        $chords = array_filter(explode(' ', $validated['chords']));
        $charIndex = 0;
        $displayOrder = 1;

        foreach ($chords as $chord) {
            if (trim($chord)) {
                ChordPosition::create([
                    'line_id' => $line->id,
                    'char_index' => $charIndex,
                    'chord' => trim($chord),
                    'display_order' => $displayOrder++
                ]);
                
                $charIndex += strlen($chord) + 1;
            }
        }

        $line->load('chordPositions');

        return response()->json([
            'success' => true,
            'line' => $line,
            'chords' => $line->chordPositions,
            'message' => 'Chords updated successfully'
        ]);
    }

    /**
     * Add a single chord position to a line via AJAX
     */
    public function addChordPosition(Request $request, SongLine $line)
    {
        $validated = $request->validate([
            'char_index' => 'required|integer|min:0',
            'chord' => 'required|string|max:10',
            'display_order' => 'nullable|integer'
        ]);

        // Get the highest display_order for this line and increment
        $maxDisplayOrder = ChordPosition::where('line_id', $line->id)
            ->max('display_order') ?? 0;

        $chordPosition = ChordPosition::create([
            'line_id' => $line->id,
            'char_index' => $validated['char_index'],
            'chord' => $validated['chord'],
            'display_order' => $validated['display_order'] ?? ($maxDisplayOrder + 1)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Chord added successfully',
            'chord_position' => $chordPosition
        ]);
    }

    /**
     * Update a chord position via AJAX
     */
    public function updateChordPosition(Request $request, ChordPosition $chordPosition)
    {
        $validated = $request->validate([
            'chord' => 'sometimes|string|max:10',
            'char_index' => 'sometimes|integer|min:0'
        ]);

        $chordPosition->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Chord updated successfully',
            'chord_position' => $chordPosition
        ]);
    }

    /**
     * Delete a chord position via AJAX
     */
    public function deleteChordPosition(ChordPosition $chordPosition)
    {
        $chordPosition->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chord deleted successfully'
        ]);
    }
}
