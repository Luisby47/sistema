@props([
    'eventName' => null,
    'name' => 'default',
    'async' => false,
    'asyncUrl' => '',
    'wide' => $isWide ?? false,
    'open' => $isOpen ?? false,
    'auto' => true,
    'autoClose' => $isAutoClose ?? false,
    'closeOutside' => $isCloseOutside ?? true,
    'title' => '',
    'outerHtml' => null
])
@moonShineAssets

<div x-data="modal_loading()" x-init="init()" {{ $attributes }}>

    <template x-teleport="body">
        <div class="modal-template ">
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-10"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-10"
                aria-modal="true"
                role="dialog"
                {{ $attributes->merge(['class' => 'modal']) }}
            >
                <div class="modal-dialog @if($wide) modal-dialog-xl @elseif($auto) modal-dialog-auto @endif">
                    <div class="modal-content  ">
                        <div class="modal-body">
                            <div class=" flex justify-center">

                                 <x-moonshine::spinner size="xl" color="primary" class="auto-rows-auto align-middle"/>

                            </div>
                            {{ $slot ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="open" x-transition.opacity class="modal-backdrop "></div>
        </div>
    </template>
</div>

