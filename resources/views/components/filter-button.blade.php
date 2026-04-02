@props(['count' => 0])

<a {{ $attributes->merge(['class' => 'btn rounded-lg px-4']) }}>
    {{ $slot }} <span class="text-xs pl-3">{{ $count }}</span>
</a>
