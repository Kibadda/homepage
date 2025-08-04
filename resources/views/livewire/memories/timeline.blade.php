<div class="flex h-full w-full flex-1 flex-col gap-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Memories Timeline</flux:heading>

        <flux:modal.trigger name="create-memory">
            <flux:button variant="primary" icon="plus" wire:click="openCreateModal">
                Add Memory
            </flux:button>
        </flux:modal.trigger>
    </div>

    @if($memories->isEmpty())
        <div class="text-center py-12">
            <div class="mx-auto h-24 w-24 text-zinc-300 dark:text-zinc-600 mb-4">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                </svg>
            </div>
            <flux:heading size="lg" class="text-zinc-500 dark:text-zinc-400 mb-2">
                No memories yet
            </flux:heading>
            <flux:text class="text-zinc-500 dark:text-zinc-400 mb-6">
                Start capturing your precious moments by creating your first memory.
            </flux:text>
            <flux:modal.trigger name="create-memory">
                <flux:button variant="primary">
                    Create Your First Memory
                </flux:button>
            </flux:modal.trigger>
        </div>
    @else
        <div class="max-w-6xl mx-auto relative">
            <!-- Central timeline line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 w-0.5 bg-zinc-200 dark:bg-zinc-700 h-full"></div>
            
            @foreach($memories as $memory)
                <div class="relative flex items-center mb-8">
                    @if($loop->iteration % 2 == 1)
                        <!-- Left side memory (odd items) -->
                        <div class="w-1/2 pr-4 flex justify-end">
                            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm cursor-pointer hover:shadow-md transition-shadow w-full max-w-lg" 
                                 wire:click="showMemory({{ $memory->id }})">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 flex flex-col items-center">
                                        @if($memory->image)
                                            <img src="{{ Storage::url($memory->image) }}"
                                                 alt="{{ $memory->title }}"
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-zinc-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <flux:text class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 text-center">
                                            {{ $memory->date->format('M j, Y') }}
                                        </flux:text>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <flux:heading size="lg" class="mb-2 truncate">{{ $memory->title }}</flux:heading>
                                        
                                        @if($memory->description)
                                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-3">
                                                {{ Str::limit($memory->description, 150) }}
                                            </flux:text>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Timeline dot -->
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white dark:border-zinc-800 shadow-lg z-10"></div>
                        
                        <!-- Badge on right side -->
                        <div class="absolute left-1/2 ml-6 flex items-center">
                            <flux:badge variant="zinc" size="sm">
                                @if($memory->date->isToday())
                                    Today
                                @else
                                    {{ $memory->date->diffInDays(now()) }} {{ $memory->date->diffInDays(now()) === 1 ? 'day' : 'days' }} ago
                                @endif
                            </flux:badge>
                        </div>
                        
                        <!-- Empty right side -->
                        <div class="w-1/2"></div>
                    @else
                        <!-- Empty left side -->
                        <div class="w-1/2"></div>
                        
                        <!-- Timeline dot -->
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white dark:border-zinc-800 shadow-lg z-10"></div>
                        
                        <!-- Badge on left side -->
                        <div class="absolute right-1/2 mr-6 flex items-center justify-end">
                            <flux:badge variant="zinc" size="sm">
                                @if($memory->date->isToday())
                                    Today
                                @else
                                    {{ $memory->date->diffInDays(now()) }} {{ $memory->date->diffInDays(now()) === 1 ? 'day' : 'days' }} ago
                                @endif
                            </flux:badge>
                        </div>
                        
                        <!-- Right side memory (even items) -->
                        <div class="w-1/2 pl-4 flex justify-start">
                            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm cursor-pointer hover:shadow-md transition-shadow w-full max-w-lg" 
                                 wire:click="showMemory({{ $memory->id }})">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 flex flex-col items-center">
                                        @if($memory->image)
                                            <img src="{{ Storage::url($memory->image) }}"
                                                 alt="{{ $memory->title }}"
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-zinc-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <flux:text class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 text-center">
                                            {{ $memory->date->format('M j, Y') }}
                                        </flux:text>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <flux:heading size="lg" class="mb-2 truncate">{{ $memory->title }}</flux:heading>
                                        
                                        @if($memory->description)
                                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-3">
                                                {{ Str::limit($memory->description, 150) }}
                                            </flux:text>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Create Memory Modal -->
    <flux:modal name="create-memory" variant="flyout" class="space-y-6">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="lg">Create New Memory</flux:heading>
            <flux:modal.close>
                <flux:button variant="ghost" size="sm" icon="x-mark"></flux:button>
            </flux:modal.close>
        </div>

        <form wire:submit="createMemory" class="space-y-6" enctype="multipart/form-data">
            <flux:input
                wire:model="title"
                label="Title"
                placeholder="Give your memory a title..."
                required
            />

            <flux:textarea
                wire:model="description"
                label="Description"
                placeholder="Tell the story behind this memory..."
                rows="4"
            />

            <div wire:ignore>
                <flux:input
                    wire:model="image"
                    label="Image"
                    type="file"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                />
            </div>
            <div wire:loading wire:target="image" class="text-sm text-blue-600">
                Uploading image...
            </div>
            @error('image')
                <flux:error>{{ $message }}</flux:error>
            @enderror

            <flux:input
                wire:model="date"
                label="Date"
                type="date"
                required
            />

            <div class="flex justify-end gap-3 pt-4">
                <flux:modal.close>
                    <flux:button
                        type="button"
                        variant="ghost"
                    >
                        Cancel
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    variant="primary"
                >
                    Create Memory
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Memory Detail Modal -->
    @if($selectedMemory)
        <flux:modal name="memory-detail" class="max-w-4xl">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="xl">{{ $selectedMemory->title }}</flux:heading>
            </div>

            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <flux:text class="text-lg text-zinc-600 dark:text-zinc-300">
                        {{ $selectedMemory->date->format('F j, Y') }}
                    </flux:text>
                    <flux:badge variant="zinc">
                        @if($selectedMemory->date->isToday())
                            Today
                        @else
                            {{ $selectedMemory->date->diffInDays(now()) }} {{ $selectedMemory->date->diffInDays(now()) === 1 ? 'day' : 'days' }} ago
                        @endif
                    </flux:badge>
                </div>

                <div class="rounded-lg overflow-hidden">
                    @if($selectedMemory->image)
                        <img src="{{ Storage::url($selectedMemory->image) }}" 
                             alt="{{ $selectedMemory->title }}"
                             class="w-full h-auto max-h-[60vh] object-contain bg-zinc-50 dark:bg-zinc-800">
                    @else
                        <div class="w-full h-64 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-zinc-400 dark:text-zinc-500 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Z" />
                                </svg>
                                <flux:text class="text-zinc-500 dark:text-zinc-400">No image</flux:text>
                            </div>
                        </div>
                    @endif
                </div>

                @if($selectedMemory->description)
                    <div class="prose dark:prose-invert max-w-none">
                        <flux:text class="text-zinc-700 dark:text-zinc-300 leading-relaxed text-base">
                            {{ $selectedMemory->description }}
                        </flux:text>
                    </div>
                @endif
            </div>
        </flux:modal>
    @endif
</div>
