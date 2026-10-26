@auth
    @php($unreadNotifications = auth()->user()->unreadNotifications)
    <details>
        <summary class="cursor-pointer hover:text-blue-700">
            Уведомления ({{ $unreadNotifications->count() }})
        </summary>
        <ul class="absolute inset-x-0 top-full z-40 mt-3 max-h-80 overflow-y-auto rounded-lg border border-slate-200 bg-white py-2 shadow-lg sm:right-0 sm:left-auto sm:w-80">
            @forelse ($unreadNotifications as $notification)
                <li>
                    <form action="{{ route('notifications.read', $notification->id) }}" method="post">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-3 text-left wrap-break-word text-blue-700 hover:bg-slate-50 hover:underline">
                            {{ $notification->data['article_name'] }}
                        </button>
                    </form>
                </li>
            @empty
                <li class="px-4 py-3 text-slate-600">Непрочитанных уведомлений нет.</li>
            @endforelse
        </ul>
    </details>
@endauth
