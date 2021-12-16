@extends('user.layouts.app')
@section('title', $story->name . ' - ' . $chapter->subname . ' :' . $chapter->name)
@section('breadcrumb')
    {!! showBreadcrumb($breadcrumb) !!}
@endsection
@section('content')
    <div class="container chapter" id="chapterBody" style="margin-top: 0px;">
        <div class="row">
            <div class="col-xs-12">
                <button type="button" class="btn btn-responsive btn-success toggle-nav-open">
                    <span class="glyphicon glyphicon-menu-up"></span>
                </button>

                <a class="truyen-title" href="{{ route('story.show', $story->alias)  }}" title="{{ $story->name }}">{{ $story->name }}</a>
                <h2>
                    <a class="chapter-title" href="{{ route('chapter.show', [$story->alias, $chapter->alias]) }}" title="{{ $story->name }} - {{ $chapter->subname }}: {{ $chapter->name }}">
                        <span class="chapter-text">{{ $chapter->subname }}</span>: {{ $chapter->name }}
                    </a>
                </h2>
                <hr class="chapter-start">
                @include('user.partials.chapter')
                <hr class="chapter-end">

                <div class="chapter-content">
                    {!! ($chapter->content) !!}
                </div>

                <div class="ads container">
                    {!! \App\Models\Option::getvalue('ads_chapter') !!}
                </div>

                <hr class="chapter-end">
                <div class="chapter-nav" id="chapter-nav-bot">
                    @include('user.partials.chapter')
                    {{--<div class="text-center">--}}
                        {{--<button type="button" class="btn btn-warning" id="chapter_error" chapter-id="{{ $chapter->id }}"><span class="glyphicon glyphicon-exclamation-sign"></span> Báo lỗi chương</button>--}}
                        {{--<button type="button" class="btn btn-info" id="chapter_comment"><span class="glyphicon glyphicon-comment"></span> Bình luận</button>--}}
                    {{--</div>--}}
                    {{--<div class="bg-info text-center visible-md visible-lg box-notice">Bình luận văn minh lịch sự là động lực cho tác giả. Nếu gặp chương bị lỗi hãy "Báo lỗi chương" để BQT xử lý!</div>--}}
                    <div class="col-xs-12">
                        <div class="row" id="fb-comment-chapter">
                            <div class="fb-comments fb_iframe_widget" data-href="{{ route('chapter.show', [$story->alias, $chapter->alias]) }}" data-width="832" data-numposts="5" data-colorscheme="light" fb-xfbml-state="rendered"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
