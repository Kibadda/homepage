<?php

namespace App\Livewire\Memories;

use App\Models\Memory;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

class Timeline extends Component
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('nullable|image|max:8192')]
    public $image;

    #[Validate('required|date')]
    public string $date = '';

    public ?Memory $memory = null;

    public function openCreateModal()
    {
        $this->reset(['title', 'description', 'image']);
        $this->date = now()->format('Y-m-d');
        $this->modal('create-memory')->show();
    }

    public function closeCreateModal()
    {
        $this->reset(['title', 'description', 'image']);
        $this->modal('create-memory')->close();
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

        auth()->user()->memories()->create([
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
        $memories = auth()->user()->memories()
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.memories.timeline', [
            'memories' => $memories,
        ])->layout('components.layouts.app', ['title' => 'Memories Timeline']);
    }
}
