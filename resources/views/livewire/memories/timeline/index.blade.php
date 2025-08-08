<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Memories') }}</flux:heading>

        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">
            {{ __('Add Timeline') }}
        </flux:button>
    </div>

    <flux:table :paginate="$timelines">
        <flux:table.columns>
            <flux:table.column>{{ __('Title') }}</flux:table.column>
            <flux:table.column>{{ __('Description') }}</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows class="cursor-pointer">
            @foreach ($timelines as $timeline)
                <flux:table.row :key="$timeline->id">
                    <flux:table.cell :href="route('timelines.show', $timeline)" wire:navigate>{{ $timeline->title }}</flux:table.cell>
                    <flux:table.cell :href="route('timelines.show', $timeline)" wire:navigate>{{ Str::limit($timeline->description, 50) }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button icon="pencil-square" variant="primary" size="xs" class="cursor-pointer" wire:click="openEditModal({{ $timeline }})"></flux:button>
                        <flux:modal.trigger :name="'delete-timeline-'.$timeline->id">
                            <flux:button icon="trash" variant="danger" size="xs" class="cursor-pointer"></flux:button>
                        </flux:modal.trigger>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    @foreach($timelines as $timeline)
        <flux:modal :name="'edit-timeline-'.$timeline->id" variant="flyout">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Edit Timeline') }}</flux:heading>
            </div>

            <form wire:submit="editTimeline" class="space-y-6">
                <input type="hidden" name="id" value="{{ $timeline->id }}" />

                <flux:input
                    wire:model="title"
                    :label="__('Title')"
                />

                <flux:textarea
                    wire:model="description"
                    :label="__('Description')"
                    rows="4"
                />

                <div class="flex justify-end gap-3 pt-4">
                    <flux:modal.close>
                        <flux:button
                            type="button"
                            variant="ghost"
                        >
                            {{ __('Cancel') }}
                        </flux:button>
                    </flux:modal.close>

                    <flux:button
                        type="submit"
                        variant="primary"
                    >
                        {{ __('Save') }}
                    </flux:button>
                </div>
            </form>
        </flux:modal>

        <flux:modal :name="'delete-timeline-'.$timeline->id">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Delete Timeline') }}</flux:heading>
            </div>

            <flux:text class="mt-2">
                {{ $timeline->title }}
            </flux:text>

            <div class="flex justify-end gap-3 pt-4">
                <flux:modal.close>
                    <flux:button
                        type="button"
                        variant="ghost"
                    >
                        {{ __('Cancel') }}
                    </flux:button>
                </flux:modal.close>

                <flux:button variant="danger" wire:click="deleteTimeline({{ $timeline }})">
                    {{ __('Delete') }}
                </flux:button>
            </div>
        </flux:modal>
    @endforeach

    <flux:modal name="create-timeline" variant="flyout">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="lg">{{ __('Add Timeline') }}</flux:heading>
        </div>

        <form wire:submit="createTimeline" class="space-y-6">
            <flux:input
                wire:model="title"
                :label="__('Title')"
            />

            <flux:textarea
                wire:model="description"
                :label="__('Description')"
                rows="4"
            />

            <div class="flex justify-end gap-3 pt-4">
                <flux:modal.close>
                    <flux:button
                        type="button"
                        variant="ghost"
                    >
                        {{ __('Cancel') }}
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    variant="primary"
                >
                    {{ __('Save') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
