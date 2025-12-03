<x-layouts.app :title="$patient->name">
    <div class="flex justify-between items-center mb-5">
        <div class="flex items-center gap-4">
            <a wire:navigate href="{{ route('patient.index') }}" class="p-2 -ml-2 text-gray-400 hover:text-gray-900 rounded-full hover:bg-gray-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left" aria-hidden="true"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
            </a>
            <div>
                <flux:heading size="xl" class="font-extrabold">{{ $patient->name }}</flux:heading>
                <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    {{ $patient->gender }}, {{ \Carbon\Carbon::parse($patient->birth_date)->age }}y
                </span>
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <span>{{ $patient->mrn }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-xs border border-gray-200">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Patient Identifiers</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-xs font-medium text-gray-900">MRN</span><span class="text-xs text-gray-600 font-mono">{{ $patient->mrn }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs font-medium text-gray-900">DOB</span><span class="text-xs text-gray-600">{{ $patient->birth_date->format('m/d/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs font-medium text-gray-900">Age/Sex</span><span class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($patient->birth_date)->age }} / {{ $patient->gender }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs font-medium text-gray-900">Room</span><span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $patient->room }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-xs border border-gray-200">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Clinical Status</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-xs font-medium text-gray-900">Attending</span><span class="text-xs text-gray-600">{{ $patient->attending_md }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs font-medium text-gray-900">Diagnosis</span><span class="text-xs text-gray-600">{{ $patient->diagnosis }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs font-medium text-gray-900">Admitted</span><span class="text-xs text-gray-600">{{ $patient->admission_date->format('m/d/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs font-medium text-gray-900">Procedure</span><span class="text-xs text-gray-600">{{ $patient->procedure }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-xs border border-gray-200">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Care Plan</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-gray-900 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-utensils text-gray-400" aria-hidden="true"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path><path d="M7 2v20"></path><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path></svg>
                        Diet Order
                    </div>
                    <p class="text-xs text-gray-600 pl-6">{{ $patient->diet_order }}</p>
                </div>
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-gray-900 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity text-gray-400" aria-hidden="true"><path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"></path></svg>
                        Activity Level
                    </div>
                    <p class="text-xs text-gray-600 pl-6">{{ $patient->activity_level }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-xs border border-gray-200">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Indicators</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity text-yellow-500" aria-hidden="true"><path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"></path></svg>
                        <span class="text-xs font-medium text-gray-900">Status</span>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">{{ $patient->status }}</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield text-blue-500" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path></svg>
                        <span class="text-xs font-medium text-gray-900">Isolation</span>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">{{ $patient->isolation }}</span>
                </div>
                <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-syringe text-purple-500" aria-hidden="true"><path d="m18 2 4 4"></path><path d="m17 7 3-3"></path><path d="M19 9 8.7 19.3c-1 1-2.5 1-3.4 0l-.6-.6c-1-1-1-2.5 0-3.4L15 5"></path><path d="m9 11 4 4"></path><path d="m5 19-3 3"></path><path d="m14 4 6 6"></path></svg>
                        <span class="text-xs font-medium text-gray-900">Unit</span>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">{{ $patient->unit }}</span>
                </div>
            </div>
        </div>
    </div>


    <div x-data="{
        activeTab: 'vital-signs',
        columns: [
            { time: '1100', data: {} },
            { time: '1200', data: {} },
            { time: '1300', data: {} },
            { time: '1400', data: {} }
        ],
        addColumn() {
            const lastTime = this.columns[this.columns.length - 1].time;
            const nextTime = String(parseInt(lastTime) + 100).padStart(4, '0');
            this.columns.push({ time: nextTime, data: {} });
        },
        removeColumn(index) {
            if (this.columns.length > 1) {
                this.columns.splice(index, 1);
            }
        }
    }">
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-1 inline-flex gap-1 mb-4">
            <button @click="activeTab = 'vital-signs'"
                    :class="activeTab === 'vital-signs' ? 'bg-white dark:bg-gray-700 shadow' : 'text-gray-600 dark:text-gray-400'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Vital Signs
            </button>
            <button @click="activeTab = 'intake-output'"
                    :class="activeTab === 'intake-output' ? 'bg-white dark:bg-gray-700 shadow' : 'text-gray-600 dark:text-gray-400'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Intake/Output
            </button>
            <button @click="activeTab = 'restraints'"
                    :class="activeTab === 'restraints' ? 'bg-white dark:bg-gray-700 shadow' : 'text-gray-600 dark:text-gray-400'"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors">
                ADLs
            </button>
        </div>

        <div x-show="activeTab === 'vital-signs'">
            <livewire:patient.vitals-form :$patient />
        </div>

        <div x-show="activeTab === 'intake-output'" class="text-gray-500 dark:text-gray-400 text-center">
            <livewire:patient.intake-output :$patient />
        </div>

        <div x-show="activeTab === 'restraints'" class="text-gray-500 dark:text-gray-400 text-center">
            <livewire:patient.adl :$patient />
        </div>
    </div>
</x-layouts.app>
