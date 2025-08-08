<?php

namespace App\Livewire\Memories\Timeline;

use App\Models\Memory;
use App\Models\Timeline;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class Show extends Component
{
    use WithFileUploads;

    public Timeline $timeline;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('nullable|image|max:8192')]
    public $image;

    #[Validate('required|date')]
    public string $date = '';

    public ?Memory $memory = null;

    public function mount(Timeline $timeline)
    {
        $this->timeline = $timeline;
    }

    public function openCreateModal()
    {
        $this->reset(['title', 'description', 'image']);
        $this->date = now()->format('Y-m-d');
        $this->modal('create-memory')->show();
    }

    public function createMemory()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            try {
                $imagePath = $this->image->store('memories', 'public');
            } catch (\Exception $e) {
                $this->addError('image', 'Failed to upload image: ' . $e->getMessage());
                return;
            }
        }

        $this->timeline->memories()->create([
            'title' => $this->title,
            'description' => $this->description,
            'image' => $imagePath,
            'date' => $this->date,
        ]);

        $this->reset(['title', 'description', 'image']);
        $this->date = now()->format('Y-m-d');
        $this->modal('create-memory')->close();
    }

    public function openEditModal(Memory $memory)
    {
        $this->modal("show-memory-{$memory->id}")->close();
        $this->title = $memory->title;
        $this->date = $memory->date->format('Y-m-d');
        $this->description = $memory->description;
        $this->image = null;
        $this->memory = $memory;
        $this->modal("edit-memory-{$memory->id}")->show();
    }

    public function editMemory()
    {
        $this->validate();

        $updates = [
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
        ];

        if ($this->image) {
            try {
                $updates['image'] = $this->image->store('memories', 'public');
                Storage::disk('public')->delete($this->memory->image);
            } catch (\Exception $e) {
                $this->addError('image', 'Failed to upload image: ' . $e->getMessage());
            }
        }

        $this->memory->update($updates);

        $this->modal("edit-memory-{$this->memory->id}")->close();
        $this->reset(['title', 'description', 'image', 'memory']);
        $this->date = now()->format('Y-m-d');
    }

    public function deleteMemory(Memory $memory)
    {
        Storage::disk('public')->delete($memory->image);
        $memory->delete();
        $this->modal("delete-modal-{$memory->id}")->close();
    }

    public function render()
    {
        $memories = $this->timeline->memories()
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.memories.timeline.show', [
            'memories' => $memories,
        ])->layout('components.layouts.app', ['title' => __('Memories') . " - {$this->timeline->title}"]);
    }
}
