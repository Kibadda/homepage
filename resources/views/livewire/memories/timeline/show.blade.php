<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ $timeline->title }}</flux:heading>

        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">
            {{ __('Add Memory') }}
        </flux:button>
    </div>

    <flux:text>
        {{ $timeline->description }}
    </flux:text>

    <div class="relative">
        <div class="absolute transform -translate-x-1/4 w-0.5 bg-zinc-200 dark:bg-zinc-700 h-full"></div>

        @foreach($memories as $memory)
            <div class="flex items-center mb-8 w-full" wire:key="{{ $memory->id }}">
                <div class="w-4 h-4 bg-blue-500 transform -translate-x-1/2 rounded-full border-4 border-white dark:border-zinc-800 shadow-lg z-10"></div>

                <div class="w-full flex pl-4 jusitfy-start" >
                    <flux:modal.trigger :name="'show-memory-'.$memory->id">
                        <div class="relative bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm hover:shadow-md transition-shadow w-full cursor-pointer">
                            <div class="flex items-start gap-4">
                                @if($memory->image)
                                    <div class="flex-shrink-0 flex flex-col items-center">
                                        <img src="{{ Storage::url($memory->image) }}" alt="{{ $memory->title }}" class="w-24 h-24 object-cover rounded-lg" />
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <flux:heading size="lg">{{ $memory->title }}</flux:heading>

                                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">
                                        @php
                                            $diff = floor($memory->date->diffInDays(now()));
                                        @endphp

                                        {{ $memory->date->format('d.m.Y') }} @if($diff > 0) - {{ trans_choice('timediff', $diff, ['days' => $diff]) }} @endif
                                    </flux:text>

                                    @if($memory->description)
                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $memory->description }}
                                        </flux:text>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </flux:modal.trigger>
                </div>

                <flux:modal :name="'show-memory-'.$memory->id" variant="flyout" class="w-3/8">
                    <flux:heading size="lg" class="mb-4">
                        {{ $memory->title }}

                        <flux:button icon="pencil-square" variant="subtle" size="xs" class="cursor-pointer" wire:click="openEditModal({{ $memory }})"></flux:button>

                        <flux:modal.trigger :name="'delete-memory-'.$memory->id">
                            <flux:button icon="trash" variant="danger" size="xs" class="cursor-pointer"></flux:button>
                        </flux:modal.trigger>
                    </flux:heading>

                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                        {{ $memory->date->format('d.m.Y') }}
                    </flux:text>

                    @if($memory->image)
                        <img src="{{ Storage::url($memory->image) }}" alt="{{ $memory->title }}" class="object-cover rounded-lg mb-4" />
                    @endif

                    @if($memory->description)
                        <flux:text class="text-zinc-500 dark:text-zinc-400">
                            {{ $memory->description }}
                        </flux:text>
                    @endif
                </flux:modal>

                <flux:modal :name="'edit-memory-'.$memory->id" variant="flyout">
                    <div class="flex items-center justify-between mb-6">
                        <flux:heading size="lg">{{ __('Edit Memory') }}</flux:heading>
                    </div>

                    <form wire:submit="editMemory" class="space-y-6" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $memory->id }}" />

                        <flux:input
                            wire:model="title"
                            :label="__('Title')"
                        />

                        <flux:textarea
                            wire:model="description"
                            :label="__('Description')"
                            rows="4"
                        />

                        <div wire:ignore>
                            <flux:input
                                wire:model="image"
                                :label="__('Image')"
                                type="file"
                                accept="image/jpeg,image/png,image/jpg,image/gif"
                            />
                        </div>
                        <div wire:loading wire:target="image" class="text-sm text-blue-600">
                            {{ __('Uploading image') }}
                        </div>
                        @error('image')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror

                        <flux:input
                            wire:model="date"
                            :label="__('Date')"
                            type="date"
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

                <flux:modal :name="'delete-memory-'.$memory->id">
                    <div class="flex items-center justify-between mb-6">
                        <flux:heading size="lg">{{ __('Delete Memory') }}</flux:heading>
                    </div>

                    <flux:text class="mt-2">
                        {{ $memory->title }}
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

                        <flux:button variant="danger" wire:click="deleteMemory({{ $memory }})">
                            {{ __('Delete') }}
                        </flux:button>
                    </div>
                </flux:modal>
            </div>
        @endforeach
    </div>

    <flux:modal name="create-memory" variant="flyout">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="lg">{{ __('Add Memory') }}</flux:heading>
        </div>

        <form wire:submit="createMemory" class="space-y-6" enctype="multipart/form-data">
            <flux:input
                wire:model="title"
                :label="__('Title')"
            />

            <flux:textarea
                wire:model="description"
                :label="__('Description')"
                rows="4"
            />

            <div wire:ignore>
                <flux:input
                    wire:model="image"
                    :label="__('Image')"
                    type="file"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                />
            </div>
            <div wire:loading wire:target="image" class="text-sm text-blue-600">
                {{ __('Uploading image') }}
            </div>
            @error('image')
                <flux:error>{{ $message }}</flux:error>
            @enderror

            <flux:input
                wire:model="date"
                :label="__('Date')"
                type="date"
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
