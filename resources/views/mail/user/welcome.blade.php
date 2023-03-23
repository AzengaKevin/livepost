<x-mail::message>
# Hello {{ $user->name }}

Welcome to {{ config('app.name') }}.
We offer a robust live blogging application.
We are happy to have on board.

<x-mail::button :url="route('welcome')">
Get Started
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
