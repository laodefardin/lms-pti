<div class="fade-in">
    <div class="topbar flex justify-between items-center mb-6">
        <div>
            <h1 class="section-title">Audit Log</h1>
            <p class="section-sub text-sm" style="color: var(--text-muted)">Rekam jejak aktivitas sistem.</p>
        </div>
    </div>

    <div class="card mb-6">
        <div class="table-wrap">
            <table class="lms-table w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left py-3 px-4">Waktu</th>
                        <th class="text-left py-3 px-4">Aktor (User)</th>
                        <th class="text-left py-3 px-4">Aksi</th>
                        <th class="text-left py-3 px-4">Target Model</th>
                        <th class="text-left py-3 px-4">Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-b hover:bg-gray-50 transition-colors" style="border-color: var(--border);">
                            <td class="py-3 px-4 whitespace-nowrap text-gray-500">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3 px-4 font-medium" style="color: var(--text-primary)">
                                @if($log->causer)
                                    {{ $log->causer->name }}
                                    <span class="text-xs text-gray-400 block">{{ $log->causer->role }}</span>
                                @else
                                    <span class="text-gray-400 italic">Sistem</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $log->description === 'created' ? 'badge-green' : ($log->description === 'updated' ? 'badge-teal' : ($log->description === 'deleted' ? 'badge-red' : 'badge-gray')) }}">
                                    {{ ucfirst($log->description) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                {{ class_basename($log->subject_type) ?? '-' }}
                                @if($log->subject_id)
                                    <span class="text-xs text-gray-400">(ID: {{ $log->subject_id }})</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="max-w-xs overflow-x-auto">
                                    <pre class="text-xs bg-gray-100 p-2 rounded text-gray-700 whitespace-pre-wrap font-mono">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">Belum ada data audit log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
