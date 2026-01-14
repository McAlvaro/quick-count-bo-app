<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="flex flex-col gap-8">

        <!-- HEADER & FILTERS -->
        <div class="flex flex-col xl:flex-row gap-6 justify-between items-start xl:items-end">
            <div class="flex flex-col gap-1">
                <h2 class="text-2xl font-bold tracking-tight text-text-primary dark:text-white">Panel de Control</h2>
                <p class="text-text-secondary dark:text-slate-400">Resultados en tiempo real - Última actualización: {{ $lastUpdate }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 w-full xl:w-auto bg-surface-light dark:bg-surface-dark p-4 rounded-xl border border-border-light dark:border-border-dark shadow-sm">
                <div class="flex flex-col gap-1.5 min-w-[200px]">
                    <label class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Recinto</label>
                    <div class="relative">
                        <select wire:model.live="precinctFilter" class="w-full h-10 pl-3 pr-8 rounded-lg border border-border-light bg-background-light dark:bg-slate-800 dark:border-border-dark text-sm focus:ring-2 focus:ring-primary focus:border-transparent appearance-none">
                            <option value="all">Todos los Recintos</option>
                            @foreach($this->precincts as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-2 top-2 text-text-secondary pointer-events-none text-lg">expand_more</span>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5 min-w-[160px]">
                    <label class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Mesa</label>
                    <div class="relative">
                        <select wire:model.live="tableFilter" class="w-full h-10 pl-3 pr-8 rounded-lg border border-border-light bg-background-light dark:bg-slate-800 dark:border-border-dark text-sm focus:ring-2 focus:ring-primary focus:border-transparent appearance-none">
                            <option value="all">Todas</option>
                            @foreach($this->tables as $id => $number)
                                <option value="{{ $id }}">Mesa {{ $number }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-2 top-2 text-text-secondary pointer-events-none text-lg">expand_more</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPIS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- KPI 1: Mesas Escrutadas -->
            <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-border-light dark:border-border-dark shadow-sm flex flex-col justify-between gap-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-text-secondary text-sm font-medium mb-1">Mesas Escrutadas</p>
                        <h3 class="text-3xl font-bold text-text-primary dark:text-white">{{ number_format($scopePct, 1) }}%</h3>
                    </div>
                    <div class="p-2 bg-primary/10 rounded-lg text-primary">
                        <span class="material-symbols-outlined">analytics</span>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="w-full h-2 bg-background-light dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full" style="width: {{ $scopePct }}%"></div>
                    </div>
                    <p class="text-xs text-text-secondary text-right">{{ number_format($scopeScrutinized) }} de {{ number_format($scopeTotalTables) }} mesas</p>
                </div>
            </div>

            <!-- KPI 2: Votos Totales -->
            <div class="bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-border-light dark:border-border-dark shadow-sm flex flex-col justify-between gap-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-text-secondary text-sm font-medium mb-1">Votos Totales</p>
                        <h3 class="text-3xl font-bold text-text-primary dark:text-white">{{ number_format($totalVotes) }}</h3>
                    </div>
                    <div class="p-2 bg-green-100 text-green-700 rounded-lg dark:bg-green-900/30 dark:text-green-400">
                        <span class="material-symbols-outlined">how_to_vote</span>
                    </div>
                </div>
            </div>

            <!-- KPI 3: Abstencion -->
            <div class="hidden lg:flex bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-border-light dark:border-border-dark shadow-sm flex-col justify-between gap-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-text-secondary text-sm font-medium mb-1">Abstención Estimada</p>
                        <h3 class="text-3xl font-bold text-text-primary dark:text-white">{{ number_format($abstentionPct, 1) }}%</h3>
                    </div>
                    <div class="p-2 bg-orange-100 text-orange-700 rounded-lg dark:bg-orange-900/30 dark:text-orange-400">
                        <span class="material-symbols-outlined">person_off</span>
                    </div>
                </div>
                <p class="text-sm text-text-secondary font-medium">
                    Proyección final
                </p>
            </div>
        </div>

        <!-- RESULTS GRID -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- COLUMN 1: PRESIDENCIALES -->
            <div class="flex flex-col bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden">
                <div class="p-6 border-b border-border-light dark:border-border-dark flex justify-between items-center">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Presidenciales
                    </h3>
                </div>
                <div class="p-6 flex flex-col gap-8">
                    <div class="flex flex-col gap-4">
                        <h4 class="text-xs font-bold text-text-secondary uppercase tracking-widest border-b border-border-light dark:border-border-dark pb-2">Resultados</h4>

                        @foreach($presidentResults as $result)
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    @if($result->photo_path)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($result->photo_path) }}" class="w-10 h-10 rounded-full object-cover border-2" style="border-color: {{ $result->color ?? 'transparent' }}" alt="{{ $result->candidate_name }}">
                                    @else
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background-color: {{ $result->color ?? '#ccc' }}">
                                            {{ substr($result->party_name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div class="flex flex-col">
                                        <span class="font-bold text-base leading-tight">{{ $result->candidate_name }}</span>
                                        <span class="text-xs text-text-secondary">{{ $result->party_name }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block font-bold text-lg leading-tight">{{ number_format($result->percentage, 1) }}%</span>
                                    <span class="block text-xs text-text-secondary">{{ number_format($result->votes) }} votos</span>
                                </div>
                            </div>
                            <div class="w-full h-3 bg-background-light dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="width: {{ $result->percentage }}%; background-color: {{ $result->color ?? '#135bec' }}"></div>
                            </div>
                        </div>
                        @endforeach

                        @if($presidentResults->isEmpty())
                            <p class="text-center text-text-secondary py-4">No hay datos registrados aún.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- COLUMN 2: DIPUTADOS -->
            <div class="flex flex-col bg-surface-light dark:bg-surface-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden">
                <div class="p-6 border-b border-border-light dark:border-border-dark flex justify-between items-center">
                    <h3 class="text-xl font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">groups</span>
                        Diputados
                    </h3>
                </div>
                <div class="p-6 flex flex-col gap-8">
                    <div class="flex flex-col gap-4">
                        <h4 class="text-xs font-bold text-text-secondary uppercase tracking-widest border-b border-border-light dark:border-border-dark pb-2">Resultados por Partido</h4>

                        @foreach($deputyResults as $result)
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background-color: {{ $result->color ?? '#ccc' }}">
                                        {{ $result->acronym }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-base leading-tight">{{ $result->party_name }}</span>
                                        <span class="text-xs text-text-secondary">Lista {{ $result->id ?? '' }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block font-bold text-lg leading-tight">{{ number_format($result->percentage, 1) }}%</span>
                                    <span class="block text-xs text-text-secondary">{{ number_format($result->votes) }} votos</span>
                                </div>
                            </div>
                            <div class="w-full h-3 bg-background-light dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="width: {{ $result->percentage }}%; background-color: {{ $result->color ?? '#135bec' }}"></div>
                            </div>
                        </div>
                        @endforeach

                        @if($deputyResults->isEmpty())
                            <p class="text-center text-text-secondary py-4">No hay datos registrados aún.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
