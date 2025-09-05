@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
<nav class="breadcrumb">
    <ol class="breadcrumb-list">
        @foreach($breadcrumbs as $breadcrumb)
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                @if(!$loop->last && isset($breadcrumb['url']))
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                @else
                    {{ $breadcrumb['title'] }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif