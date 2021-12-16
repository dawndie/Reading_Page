@if (count($historys) > 0)
<div style="clear: both;"></div>
<div class="container" id="truyen-slide">
    <div class="list list-thumbnail col-xs-12">
        <div class="title-list"><h2><a href="" title="Truyện full">Truyện đã đọc</a></h2><a href="" title=""><span class="glyphicon glyphicon-menu-right"></span></a></div>
        <div class="row">
            @if($historys)
                @foreach($historys as $item)
                    <div class="col-xs-4 col-sm-3 col-md-2">
                            <a href="{{route('story.show', $item->story->alias)}}" title="{{$item->story->name}}">
                                <img src="{{asset($item->story->image)}}" alt="{{$item->story->name}}">
                            </a>
                            <div class="caption">
                                <a href="{{route('story.show', $item->story->alias)}}" title="{{$item->story->name}}">
                                    <h3>{{$item->story->name}}</h3>
                                </a>
                                <br>
                                <small class="btn-xs label-primary">
                                    Đọc tiếp <a href="{{route('chapter.show', [$item->story->alias, $item->chapter->alias])}}" style="color: #ffffff">{{ $item->chapter->subname}}</a>
                                </small>
                            </div>

                    </div>

                @endforeach
            @else
                <p>Không có bài viết nào ở đây !</p>
            @endif
        </div>

    </div>
</div>
@endif
