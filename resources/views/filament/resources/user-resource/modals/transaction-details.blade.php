<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaction ID</p>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $transaction->uuid }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</p>
            <p class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $transaction->type === 'deposit' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                    {{ ucfirst($transaction->type) }}
                </span>
            </p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</p>
            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                {{ 'IDR ' . number_format($transaction->amount, 0, ',', '.') }}
            </p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
            <p class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    {{ $transaction->confirmed ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                    {{ $transaction->confirmed ? 'Confirmed' : 'Pending' }}
                </span>
            </p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</p>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                {{ $transaction->created_at->format('d M Y, H:i:s') }}
            </p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Wallet ID</p>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">
                {{ $transaction->wallet_id }}
            </p>
        </div>
    </div>

    @if($transaction->meta && count($transaction->meta) > 0)
        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Additional Information</p>
            <div class="mt-2 space-y-2">
                @foreach($transaction->meta as $key => $value)
                    <div class="flex">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 min-w-[120px]">{{ ucfirst($key) }}:</span>
                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
