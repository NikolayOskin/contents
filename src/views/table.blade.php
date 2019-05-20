<ul>
    @foreach($contents as $key => $header)
        <li><a href="#header-{{$header['count']}}">{{$header['header']}}</a></li>
        @if (!empty($header['childs']))
            @include('nikolay-oskin.contents.table', ['contents' => $header['childs']])
        @endif
    @endforeach
</ul>