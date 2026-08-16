<div class="relative" x-data="{ open: @entangle('isOpen') }" @click.outside="open = false">
    <!-- Bell Button -->
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-500 transition-colors focus:outline-none">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 block h-4 w-4 rounded-full bg-red-500 text-xs text-white text-center flex items-center justify-center font-bold" style="font-size: 0.65rem;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 overflow-hidden border border-gray-200 dark:border-gray-700"
         style="display: none;">
        
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-teal-600 dark:text-teal-400 hover:text-teal-800 dark:hover:text-teal-300 font-medium">Tandai semua dibaca</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notif)
                <div wire:click="markAsRead({{ $notif->id }})" class="cursor-pointer px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !$notif->is_read ? 'bg-teal-50/30 dark:bg-teal-900/10' : '' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 text-2xl mr-3">
                            {{ $notif->icon }}
                        </div>
                        <div class="w-0 flex-1">
                            <p class="text-sm font-medium {{ !$notif->is_read ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300' }}">
                                {{ $notif->judul }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                {{ $notif->pesan }}
                            </p>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                {{ $notif->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if(!$notif->is_read)
                            <div class="flex-shrink-0 ml-2">
                                <span class="inline-block h-2 w-2 rounded-full bg-teal-500"></span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada notifikasi.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
