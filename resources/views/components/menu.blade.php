<ul id="sidebar_menu">
    @foreach(config('menu') as $item)
        <li class="">
            <a  href=" @if(!is_null($item['submenu'])) # @else {{$item['url']}} @endif" class=" @if(!is_null($item['submenu'])) has-arrow @endif"  aria-expanded="false">
                <div class="nav_icon_small">
                    {!! $item['icon'] !!}
                </div>
                <div class="nav_title">
                    <span>{{$item['name']}}</span>
                </div>
            </a>
            @if(!is_null($item['submenu']))
            <ul>
                @foreach($item['submenu'] as $subItem)
                    <li><a href="{{$subItem['url']}}">{{$subItem['name']}}</a></li>
                @endforeach


            </ul>
            @endif
        </li>
    @endforeach




</ul>
