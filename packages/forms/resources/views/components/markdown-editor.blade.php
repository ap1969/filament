<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $statePath = $getStatePath();
    @endphp

    @unless ($isDisabled())
        <div
            ax-load="visible"
            ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('markdown-editor', 'filament/forms') }}"
            x-data="markdownEditorFormComponent({
                        isLiveDebounced: @js($isLiveDebounced()),
                        isLiveOnBlur: @js($isLiveOnBlur()),
                        liveDebounce: @js($getNormalizedLiveDebounce()),
                        placeholder: @js($getPlaceholder()),
                        state: $wire.{{ $applyStateBindingModifiers("entangle('{$statePath}')", isOptimisticallyLive: false) }},
                        toolbarButtons: @js($getToolbarButtons()),
                        translations: @js(__('filament-forms::components.markdown_editor')),
                        uploadFileAttachmentUsing: async (file, onSuccess, onError) => {
                            $wire.upload(`componentFileAttachments.{{ $statePath }}`, file, () => {
                                $wire
                                    .getFormComponentFileAttachmentUrl('{{ $statePath }}')
                                    .then((url) => {
                                        if (! url) {
                                            return onError()
                                        }

                                        onSuccess(url)
                                    })
                            })
                        },
                    })"
            x-ignore
            wire:ignore
            {{
                $attributes
                    ->merge($getExtraAttributes(), escape: false)
                    ->merge($getExtraAlpineAttributes(), escape: false)
                    ->class([
                        'filament-forms-markdown-editor-component overflow-hidden rounded-lg bg-white font-mono text-base text-gray-950 shadow-sm ring-1 focus-within:ring-2 dark:bg-gray-900 dark:text-white sm:text-sm',
                        'ring-gray-950/10 focus-within:ring-primary-600 dark:ring-white/20 dark:focus-within:ring-primary-600' => ! $errors->has($statePath),
                        'ring-danger-600 focus-within:ring-danger-600 dark:ring-danger-400 dark:focus-within:ring-danger-400' => $errors->has($statePath),
                    ])
            }}
        >
            <textarea x-ref="editor" class="hidden"></textarea>
        </div>
    @else
        <div
            class="prose block w-full max-w-none rounded-lg bg-gray-50 px-3 py-3 text-gray-500 shadow-sm ring-1 ring-gray-950/10 dark:prose-invert dark:bg-gray-950 dark:text-gray-400 dark:ring-white/20 sm:text-sm"
        >
            {!! str($getState())->markdown()->sanitizeHtml() !!}
        </div>
    @endunless
</x-dynamic-component>
