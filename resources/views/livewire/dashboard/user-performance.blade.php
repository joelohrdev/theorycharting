<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;

new class extends Component {
    public string $dateRange = '7'; // days

    #[Computed]
    public function students(): \Illuminate\Database\Eloquent\Collection
    {
        $user = auth()->user();

        if ($user->isTeacher()) {
            return $user->students()
                ->withCount([
                    'vitals' => fn($query) => $query->where('created_at', '>=', now()->subDays((int) $this->dateRange)),
                    'intakes' => fn($query) => $query->where('created_at', '>=', now()->subDays((int) $this->dateRange)),
                    'adls' => fn($query) => $query->where('created_at', '>=', now()->subDays((int) $this->dateRange)),
                    'patients' => fn($query) => $query->where('created_at', '>=', now()->subDays((int) $this->dateRange)),
                ])
                ->get()
                ->map(function ($student) {
                    $student->total_entries = $student->vitals_count + $student->intakes_count + $student->adls_count;
                    return $student;
                })
                ->sortByDesc('total_entries');
        }

        return collect();
    }

    #[Computed]
    public function totalStudents(): int
    {
        return $this->students()->count();
    }

    #[Computed]
    public function activeStudents(): int
    {
        return $this->students()->filter(fn($student) => $student->total_entries > 0)->count();
    }

    #[Computed]
    public function totalEntries(): int
    {
        return $this->students()->sum('total_entries');
    }

    #[Computed]
    public function averageEntriesPerStudent(): float
    {
        $activeCount = $this->activeStudents();
        return $activeCount > 0 ? round($this->totalEntries() / $activeCount, 1) : 0;
    }

    #[Computed]
    public function topPerformers(): \Illuminate\Support\Collection
    {
        return $this->students()->take(5);
    }

    #[Computed]
    public function activityBreakdown(): array
    {
        $students = $this->students();

        return [
            'vitals' => $students->sum('vitals_count'),
            'intakes' => $students->sum('intakes_count'),
            'adls' => $students->sum('adls_count'),
            'patients' => $students->sum('patients_count'),
        ];
    }

    #[Computed]
    public function dailyActivity(): \Illuminate\Support\Collection
    {
        $user = auth()->user();
        $days = (int) $this->dateRange;

        if (!$user->isTeacher()) {
            return collect();
        }

        $studentIds = $user->students()->pluck('id');

        // For longer periods, show last 14 data points (weekly for 30/90 days)
        $groupByWeeks = $days > 14;
        $numDataPoints = $groupByWeeks ? 14 : $days;

        if ($groupByWeeks) {
            // Group by weeks for 30 and 90 day views (SQLite compatible)
            $vitals = DB::table('vitals')
                ->selectRaw("strftime('%Y-%W', created_at) as week, COUNT(*) as count")
                ->whereIn('user_id', $studentIds)
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('week')
                ->pluck('count', 'week');

            $intakes = DB::table('intakes')
                ->selectRaw("strftime('%Y-%W', created_at) as week, COUNT(*) as count")
                ->whereIn('user_id', $studentIds)
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('week')
                ->pluck('count', 'week');

            $adls = DB::table('adls')
                ->selectRaw("strftime('%Y-%W', created_at) as week, COUNT(*) as count")
                ->whereIn('user_id', $studentIds)
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('week')
                ->pluck('count', 'week');

            $dates = collect();
            for ($i = $numDataPoints - 1; $i >= 0; $i--) {
                $weekStart = now()->subWeeks($i)->startOfWeek();
                $weekKey = $weekStart->format('Y-W'); // Year-Week format matching SQLite strftime

                $dates->push([
                    'date' => $weekKey,
                    'display_date' => $weekStart->format('M j'),
                    'vitals' => $vitals->get($weekKey, 0),
                    'intakes' => $intakes->get($weekKey, 0),
                    'adls' => $adls->get($weekKey, 0),
                    'total' => $vitals->get($weekKey, 0) + $intakes->get($weekKey, 0) + $adls->get($weekKey, 0),
                ]);
            }
        } else {
            // Show daily for 7 and 14 day views
            $vitals = DB::table('vitals')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereIn('user_id', $studentIds)
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('date')
                ->pluck('count', 'date');

            $intakes = DB::table('intakes')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereIn('user_id', $studentIds)
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('date')
                ->pluck('count', 'date');

            $adls = DB::table('adls')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->whereIn('user_id', $studentIds)
                ->where('created_at', '>=', now()->subDays($days))
                ->groupBy('date')
                ->pluck('count', 'date');

            $dates = collect();
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $dates->push([
                    'date' => $date,
                    'display_date' => now()->subDays($i)->format('M j'),
                    'vitals' => $vitals->get($date, 0),
                    'intakes' => $intakes->get($date, 0),
                    'adls' => $adls->get($date, 0),
                    'total' => $vitals->get($date, 0) + $intakes->get($date, 0) + $adls->get($date, 0),
                ]);
            }
        }

        return $dates;
    }

    #[Computed]
    public function maxDailyTotal(): int
    {
        return $this->dailyActivity()->max('total') ?: 1;
    }

    #[Computed]
    public function activityChartLabel(): string
    {
        return ((int) $this->dateRange) > 14 ? 'Weekly Activity' : 'Daily Activity';
    }
}; ?>

<div class="space-y-6">
    @if(auth()->user()->isTeacher())
        {{-- Empty State for No Students --}}
        @if($this->totalStudents === 0)
            <div class="flex min-h-[400px] items-center justify-center rounded-xl border-2 border-dashed border-neutral-200 bg-neutral-50 p-12 dark:border-neutral-700 dark:bg-neutral-900">
                <div class="max-w-md text-center">
                    <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-neutral-200 dark:bg-neutral-800">
                        <svg class="size-8 text-neutral-400 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <flux:heading size="lg" class="mb-2">No Students Yet</flux:heading>
                    <p class="mb-6 text-neutral-600 dark:text-neutral-400">
                        You haven't invited any students yet. Once you invite students and they start documenting patient care, their performance data will appear here.
                    </p>
                    <flux:button href="{{ route('student.index') }}" variant="primary">
                        Invite Students
                    </flux:button>
                </div>
            </div>
        @else
            {{-- Header with filters --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <flux:heading size="xl" class="font-semibold">Student Performance</flux:heading>
                <div class="w-full sm:w-auto">
                    <flux:select wire:model.live="dateRange" variant="listbox" placeholder="Select date range...">
                        <flux:select.option value="7">Last 7 days</flux:select.option>
                        <flux:select.option value="14">Last 14 days</flux:select.option>
                        <flux:select.option value="30">Last 30 days</flux:select.option>
                        <flux:select.option value="90">Last 90 days</flux:select.option>
                    </flux:select>
                </div>
            </div>

            {{-- Summary Stats --}}
        <div class="grid gap-4 md:grid-cols-4" wire:loading.remove wire:target="dateRange">
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Students</div>
                <div class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ $this->totalStudents }}</div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Active Students</div>
                <div class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ $this->activeStudents }}</div>
                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                    {{ $this->totalStudents > 0 ? round(($this->activeStudents / $this->totalStudents) * 100) : 0 }}% of total
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Total Entries</div>
                <div class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ number_format($this->totalEntries) }}</div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="text-sm font-medium text-neutral-500 dark:text-neutral-400">Avg per Student</div>
                <div class="mt-2 text-3xl font-bold text-neutral-900 dark:text-white">{{ $this->averageEntriesPerStudent }}</div>
                <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">entries/student</div>
            </div>
        </div>

        {{-- Loading Skeleton for Stats --}}
        <flux:skeleton.group animate="shimmer" wire:loading wire:target="dateRange">
            <div class="grid gap-4 md:grid-cols-4">
                @foreach(range(1, 4) as $i)
                    <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                        <flux:skeleton.line class="w-1/2" />
                        <flux:skeleton.line size="lg" class="mt-2 w-3/4" />
                        @if($i === 2 || $i === 4)
                            <flux:skeleton.line class="mt-1 w-1/3" />
                        @endif
                    </div>
                @endforeach
            </div>
        </flux:skeleton.group>

        {{-- Activity Breakdown --}}
        <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800" wire:loading.remove wire:target="dateRange">
            <flux:heading size="lg" class="mb-4 font-semibold">Activity Breakdown</flux:heading>
            <div class="grid gap-4 md:grid-cols-4">
                @php
                    $breakdown = $this->activityBreakdown;
                    $total = array_sum($breakdown);
                @endphp

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Vitals</span>
                        <span class="text-sm font-bold text-neutral-900 dark:text-white">{{ number_format($breakdown['vitals']) }}</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                        <div class="h-full bg-blue-500" style="width: {{ $total > 0 ? ($breakdown['vitals'] / $total) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Intake/Output</span>
                        <span class="text-sm font-bold text-neutral-900 dark:text-white">{{ number_format($breakdown['intakes']) }}</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                        <div class="h-full bg-green-500" style="width: {{ $total > 0 ? ($breakdown['intakes'] / $total) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">ADLs</span>
                        <span class="text-sm font-bold text-neutral-900 dark:text-white">{{ number_format($breakdown['adls']) }}</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                        <div class="h-full bg-purple-500" style="width: {{ $total > 0 ? ($breakdown['adls'] / $total) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">New Patients</span>
                        <span class="text-sm font-bold text-neutral-900 dark:text-white">{{ number_format($breakdown['patients']) }}</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                        <div class="h-full bg-orange-500" style="width: {{ $total > 0 ? ($breakdown['patients'] / $total) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Loading Skeleton for Activity Breakdown --}}
        <flux:skeleton.group animate="shimmer" wire:loading wire:target="dateRange">
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <flux:skeleton.line class="mb-4 w-1/4" size="lg" />
                <div class="grid gap-4 md:grid-cols-4">
                    @foreach(range(1, 4) as $i)
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <flux:skeleton.line class="w-1/2" />
                                <flux:skeleton.line class="w-1/4" />
                            </div>
                            <flux:skeleton class="h-2 w-full rounded-full" />
                        </div>
                    @endforeach
                </div>
            </div>
        </flux:skeleton.group>

        <div class="grid gap-6 lg:grid-cols-2" wire:loading.remove wire:target="dateRange">
            {{-- Daily Activity Chart --}}
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <flux:heading size="lg" class="mb-4 font-semibold">{{ $this->activityChartLabel }}</flux:heading>
                <div class="space-y-2">
                    @forelse($this->dailyActivity as $day)
                        <div class="flex items-center gap-3">
                            <div class="w-16 text-xs text-neutral-600 dark:text-neutral-400">{{ $day['display_date'] }}</div>
                            <div class="flex-1">
                                <div class="flex h-8 overflow-hidden rounded bg-neutral-100 dark:bg-neutral-900">
                                    @if($day['vitals'] > 0)
                                        <div class="bg-blue-500" style="width: {{ ($day['vitals'] / $this->maxDailyTotal) * 100 }}%" title="Vitals: {{ $day['vitals'] }}"></div>
                                    @endif
                                    @if($day['intakes'] > 0)
                                        <div class="bg-green-500" style="width: {{ ($day['intakes'] / $this->maxDailyTotal) * 100 }}%" title="Intakes: {{ $day['intakes'] }}"></div>
                                    @endif
                                    @if($day['adls'] > 0)
                                        <div class="bg-purple-500" style="width: {{ ($day['adls'] / $this->maxDailyTotal) * 100 }}%" title="ADLs: {{ $day['adls'] }}"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="w-12 text-right text-sm font-medium text-neutral-900 dark:text-white">{{ $day['total'] }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-neutral-500">No activity data available</div>
                    @endforelse
                </div>
                <div class="mt-4 flex items-center justify-center gap-4 border-t border-neutral-200 pt-4 text-xs dark:border-neutral-700">
                    <div class="flex items-center gap-1">
                        <div class="size-3 rounded bg-blue-500"></div>
                        <span class="text-neutral-600 dark:text-neutral-400">Vitals</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="size-3 rounded bg-green-500"></div>
                        <span class="text-neutral-600 dark:text-neutral-400">I/O</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="size-3 rounded bg-purple-500"></div>
                        <span class="text-neutral-600 dark:text-neutral-400">ADLs</span>
                    </div>
                </div>
            </div>

            {{-- Top Performers --}}
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <flux:heading size="lg" class="mb-4 font-semibold">Top Performers</flux:heading>
                <div class="space-y-3">
                    @forelse($this->topPerformers as $index => $student)
                        <div class="flex items-center gap-4 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-900">
                            <div class="flex size-8 items-center justify-center rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' : ($index === 1 ? 'bg-neutral-200 text-neutral-700 dark:bg-neutral-700 dark:text-neutral-300' : ($index === 2 ? 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300' : 'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400')) }} font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-neutral-900 dark:text-white">{{ $student->name }}</div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ $student->vitals_count }} vitals • {{ $student->intakes_count }} I/O • {{ $student->adls_count }} ADLs
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-neutral-900 dark:text-white">{{ $student->total_entries }}</div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">entries</div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-neutral-500">No student data available</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Loading Skeleton for Charts --}}
        <flux:skeleton.group animate="shimmer" wire:loading wire:target="dateRange">
            <div class="grid gap-6 lg:grid-cols-2">
                {{-- Daily Activity Skeleton --}}
                <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <flux:skeleton.line class="mb-4 w-1/3" size="lg" />
                    <div class="space-y-2">
                        @foreach(range(1, 7) as $i)
                            <div class="flex items-center gap-3">
                                <flux:skeleton.line class="w-16" />
                                <flux:skeleton class="h-8 flex-1 rounded" />
                                <flux:skeleton.line class="w-12" />
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Top Performers Skeleton --}}
                <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <flux:skeleton.line class="mb-4 w-1/3" size="lg" />
                    <div class="space-y-3">
                        @foreach(range(1, 5) as $i)
                            <div class="flex items-center gap-4 rounded-lg bg-neutral-50 p-3 dark:bg-neutral-900">
                                <flux:skeleton class="size-8 rounded-full" />
                                <div class="flex-1">
                                    <flux:skeleton.line class="w-3/4" />
                                    <flux:skeleton.line class="mt-1 w-1/2" />
                                </div>
                                <div>
                                    <flux:skeleton.line class="w-12" />
                                    <flux:skeleton.line class="mt-1 w-12" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </flux:skeleton.group>

        {{-- All Students Table --}}
        <div class="rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800" wire:loading.remove wire:target="dateRange">
            <div class="p-6">
                <flux:heading size="lg" class="font-semibold">All Students</flux:heading>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Email</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Vitals</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">I/O</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">ADLs</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Patients</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                        @forelse($this->students as $student)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="font-medium text-neutral-900 dark:text-white">{{ $student->name }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400">
                                    {{ $student->email }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-neutral-900 dark:text-white">
                                    {{ number_format($student->vitals_count) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-neutral-900 dark:text-white">
                                    {{ number_format($student->intakes_count) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-neutral-900 dark:text-white">
                                    {{ number_format($student->adls_count) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-neutral-900 dark:text-white">
                                    {{ number_format($student->patients_count) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-bold text-neutral-900 dark:text-white">
                                    {{ number_format($student->total_entries) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-neutral-500">
                                    No students found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Loading Skeleton for Table --}}
        <flux:skeleton.group animate="shimmer" wire:loading wire:target="dateRange">
            <div class="rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
                <div class="p-6">
                    <flux:skeleton.line class="w-1/4" size="lg" />
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-t border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Email</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Vitals</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">I/O</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">ADLs</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Patients</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                            @foreach(range(1, 5) as $i)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <flux:skeleton.line class="w-3/4" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <flux:skeleton.line class="w-full" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <flux:skeleton.line class="ml-auto w-12" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <flux:skeleton.line class="ml-auto w-12" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <flux:skeleton.line class="ml-auto w-12" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <flux:skeleton.line class="ml-auto w-12" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <flux:skeleton.line class="ml-auto w-12" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </flux:skeleton.group>
        @endif
    @else
        <div class="rounded-xl border border-neutral-200 bg-white p-8 text-center dark:border-neutral-700 dark:bg-neutral-800">
            <flux:heading size="lg" class="mb-2">Performance Dashboard</flux:heading>
            <p class="text-neutral-500 dark:text-neutral-400">This section is only available for teachers.</p>
        </div>
    @endif
</div>
