<?php

namespace App\Livewire\Memories\Timeline;

use App\Models\Timeline;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Index extends Component
{
    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string')]
    public string $description = '';

    public ?Timeline $timeline = null;

    public function openCreateModal()
    {
        $this->reset(['title', 'description']);
        $this->modal('create-timeline')->show();
    }

    public function createTimeline()
    {
        $this->validate();

        Timeline::create([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->reset(['title', 'description']);
        $this->modal('create-timeline')->close();
    }

    public function openEditModal(Timeline $timeline)
    {
        $this->timeline = $timeline;
        $this->title = $timeline->title;
        $this->description = $timeline->description;
        $this->modal("edit-timeline-{$timeline->id}")->show();
    }

    public function editTimeline()
    {
        $this->validate();

        $this->timeline->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->reset(['title', 'description']);
        $this->modal("edit-timeline-{$this->timeline->id}")->close();
    }

    public function deleteTimeline(Timeline $timeline)
    {
        $timeline->delete();
        $this->modal("delete-timeline-{$timeline->id}")->close();
    }

    public function render()
    {
        return view('livewire.memories.timeline.index', [
            'timelines' => Timeline::paginate(10),
        ])->layout('components.layouts.app', ['title' => __('Memories')]);
    }
}
