<?php

namespace App\Livewire\Memories;

use App\Models\Memory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Flux\Flux;

class Timeline extends Component
{
    use WithFileUploads;


    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('nullable|image|max:2048')]
    public $image;

    #[Validate('required|date')]
    public string $date = '';

    public ?Memory $selectedMemory = null;

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function openCreateModal()
    {
        $this->reset(['title', 'description', 'image']);
        $this->date = now()->format('Y-m-d');
        Flux::modal('create-memory')->show();
    }

    public function closeCreateModal()
    {
        $this->reset(['title', 'description', 'image']);
        Flux::modal('create-memory')->close();
    }

    public function showMemory(Memory $memory)
    {
        $this->selectedMemory = $memory;
        Flux::modal('memory-detail')->show();
    }

    public function closeMemoryDetail()
    {
        $this->selectedMemory = null;
        Flux::modal('memory-detail')->close();
    }

    public function updatedImage()
    {
        $this->validateOnly('image', [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
        ]);
    }

    public function createMemory()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'date' => 'required|date',
        ]);

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
        Flux::modal('create-memory')->close();
        $this->dispatch('memory-created');
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
