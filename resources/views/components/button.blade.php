@props([
    "href" => null,
    "outline" => null,
    "block" => null,
    "info" => null,
    "ghost" => null,
])

@php
    $tag = $href ? "a" : "button"
@endphp

<{{ $tag }} @if ($href) href="{{ $href }}" @endif {{ $attributes->class([
    "btn btn-primary",
    "btn-outline" => $outline,
    "btn-wide" => $block,
    "btn-info" => $info,
    "btn-ghost" => $ghost
]) }}>{{ $slot }}</{{ $tag }}>
