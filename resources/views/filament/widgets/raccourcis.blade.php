<x-filament-widgets::widget>
    <x-filament::section icon="heroicon-o-bolt" heading="Raccourcis">
        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <x-filament::button tag="a" :href="$ajouterRealisation" icon="heroicon-m-plus">
                Ajouter une réalisation
            </x-filament::button>

            <x-filament::button tag="a" :href="$profil" icon="heroicon-m-user-circle" color="gray">
                Modifier mon profil
            </x-filament::button>

            @if ($site)
                <x-filament::button tag="a" :href="$site" target="_blank" icon="heroicon-m-arrow-top-right-on-square" color="success">
                    Voir mon site
                </x-filament::button>
            @endif
        </div>

        <div style="margin-top: 1.25rem; display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
            @if ($cv)
                <x-filament::badge color="success" icon="heroicon-m-check-circle">CV en ligne</x-filament::badge>
                <span>{{ $cv->nom_original }}</span>
                <x-filament::link :href="$cv->url" target="_blank" icon="heroicon-m-arrow-down-tray">
                    Télécharger
                </x-filament::link>
            @else
                <x-filament::badge color="danger" icon="heroicon-m-exclamation-triangle">Aucun CV en ligne</x-filament::badge>
                <span>Les visiteurs ne peuvent pas télécharger ton CV.</span>
                <x-filament::link :href="$gererCv">Ajouter un CV</x-filament::link>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
