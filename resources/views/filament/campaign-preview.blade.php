<div class="space-y-4">
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h4 class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Campaign Information</h4>
        <dl class="grid grid-cols-2 gap-2 text-sm">
            <dt class="text-gray-600 dark:text-gray-400">Name:</dt>
            <dd class="font-medium">{{ $campaign->name }}</dd>

            <dt class="text-gray-600 dark:text-gray-400">Company:</dt>
            <dd class="font-medium">{{ $campaign->company_name }}</dd>

            <dt class="text-gray-600 dark:text-gray-400">Status:</dt>
            <dd>
                @if($campaign->is_active)
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>
                @else
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Inactive</span>
                @endif
            </dd>
        </dl>
    </div>

    @if(!empty($variables))
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
        <h4 class="text-sm font-semibold mb-2 text-blue-700 dark:text-blue-300">Dynamic Variables</h4>
        <div class="flex flex-wrap gap-2">
            @foreach($variables as $variable)
                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    [{{ $variable }}]
                </span>
            @endforeach
        </div>
        <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">These variables need to be provided when sending a message</p>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <h4 class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Message Preview</h4>
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 font-mono text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $preview }}</div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            Characters: {{ strlen($preview) }} |
            Words: {{ str_word_count($preview) }}
        </p>
    </div>

    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
        <h4 class="text-sm font-semibold mb-2 text-yellow-700 dark:text-yellow-300">Original Template</h4>
        <div class="bg-white dark:bg-gray-900 rounded-lg p-3 font-mono text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap border border-yellow-200 dark:border-yellow-800">{{ $campaign->template }}</div>
    </div>
</div>
