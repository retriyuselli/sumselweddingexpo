@php
    $record = $getRecord();
@endphp

<div class="fi-fo-field-wrp">
    <div class="fi-fo-field-wrp-label">
        <label class="fi-fo-field-wrp-label-text text-sm font-medium text-gray-950 dark:text-white">
            Account Information
        </label>
    </div>
    
    @if($record)
        <div class="space-y-2 text-sm bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <div class="flex items-center gap-2">
                <span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                @if($record->isVerified())
                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">
                        <x-heroicon-o-check-circle class="inline w-3 h-3" /> Verified
                    </span>
                @else
                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">
                        <x-heroicon-o-exclamation-triangle class="inline w-3 h-3" /> Unverified
                    </span>
                @endif
            </div>
            
            <div class="flex items-center gap-2">
                <span class="font-medium text-gray-700 dark:text-gray-300">Role:</span>
                <span class="text-gray-600 dark:text-gray-400">{{ $record->role_name }}</span>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="font-medium text-gray-700 dark:text-gray-300">Member Since:</span>
                <span class="text-gray-600 dark:text-gray-400">{{ $record->created_at->format('d M Y') }}</span>
            </div>
        </div>
    @else
        <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            Account information will appear after creation
        </div>
    @endif
</div>
