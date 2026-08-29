<div class="space-y-2 max-h-[28rem] overflow-y-auto">
    @forelse ($activities as $activity)
        @php
            $attributes = $activity->properties['attributes'] ?? [];
            $old = $activity->properties['old'] ?? [];
            $name = $activity->properties['employee_name'] ?? '(tidak diketahui)';

            $badgeStyle = match ($activity->event) {
                'created' => 'background-color:#dcfce7;color:#15803d;',
                'updated' => 'background-color:#fef9c3;color:#a16207;',
                'deleted' => 'background-color:#fee2e2;color:#b91c1c;',
                default => 'background-color:#f3f4f6;color:#374151;',
            };

            $badgeLabel = match ($activity->event) {
                'created' => 'Create',
                'updated' => 'Update',
                'deleted' => 'Remove',
                default => $activity->event,
            };
        @endphp

        <div class="border rounded-lg p-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded"
                        style="{{ $badgeStyle }}">{{ $badgeLabel }}</span>
                    <span class="text-sm font-medium">{{ $name }}</span>
                </div>
                <span class="text-xs text-gray-500">{{ $activity->created_at->format('d/m/Y H:i') }}</span>
            </div>

            @if ($activity->event === 'updated')
                <ul class="text-xs mt-2 space-y-1 text-gray-600">
                    @foreach ($attributes as $key => $newValue)
                        @php
                            $oldValue = $old[$key] ?? null;
                            if ($oldValue == $newValue) {
                                continue;
                            }

                            $label = match ($key) {
                                'employee_code' => 'Kode Karyawan',
                                'name' => 'Nama',
                                'department_id' => 'Department',
                                'jabatan_id' => 'Jabatan',
                                'email' => 'Email',
                                'status' => 'Status',
                                default => $key,
                            };

                            $displayOld = $oldValue;
                            $displayNew = $newValue;

                            if ($key === 'department_id') {
                                $displayOld = $oldValue ? \App\Models\Department::find($oldValue)?->name ?? '-' : '-';
                                $displayNew = $newValue ? \App\Models\Department::find($newValue)?->name ?? '-' : '-';
                            } elseif ($key === 'jabatan_id') {
                                $displayOld = $oldValue ? \App\Models\Jabatan::find($oldValue)?->name ?? '-' : '-';
                                $displayNew = $newValue ? \App\Models\Jabatan::find($newValue)?->name ?? '-' : '-';
                            } else {
                                $displayOld = $oldValue ?? '-';
                            }
                        @endphp
                        <li><strong>{{ $label }}</strong>: {{ $displayOld }} → {{ $displayNew }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @empty
        <p class="text-sm text-gray-500">Belum ada riwayat perubahan.</p>
    @endforelse
</div>
