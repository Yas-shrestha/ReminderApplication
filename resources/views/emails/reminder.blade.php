<x-mail::message>
    # Reminder: {{ $reminder->title }}

    @if ($reminder->note)
        **Note:** {{ $reminder->note }}
    @endif

    **Scheduled for:** {{ $reminder->remind_at->format('d M Y, h:i A') }}

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
