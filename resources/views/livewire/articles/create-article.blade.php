<div>
    <form wire:submit="create">
        {{ $this->form }}


        <div class="flex justify-end mt-6">
            <x-primary-button>
                {{ __('Submit') }}
            </x-primary-button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
