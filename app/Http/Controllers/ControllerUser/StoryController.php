<?php

namespace App\Http\Controllers\ControllerUser;

use Faker\Provider\Image;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoryRequest;
use App\Models\Story;
use App\Models\Chapter;
use App\Models\Category;
use App\Models\Author;
use Auth;

class StoryController extends Controller
{
    /**
     * Index List
     */
    // Truyện mới
    public function getListNewStory(Request $request)
    {
        $stories = ($request->get('filter') == 'full') ? Story::where(['status' => 1, 'active' => 1])->orderBy('updated_at', 'DESC')->paginate(25) : Story::where('active', 1)->orderBy('updated_at', 'DESC')->paginate(25);
        if(!$stories) abort(404);
        $data     = [
            'title'  => 'Truyện mới',
            'description' => 'Truyện mới',
            'keyword' => '',
            'alias'  => route('danhsach.truyenmoi'),
            'stories' => $stories,
        ];
        $breadcrumb = [[route('danhsach.truyenmoi'), 'Truyện mới']];
        return view('user.list_story', compact('data', 'breadcrumb'));
    }

    // Truyện Hot
    public function getListHotStory(Request $r  )
    {
        $stories = ($r->get('filter') == 'full') ? Story::where(['status' => 1, 'active' => 1])->orderBy('view', 'DESC')->paginate(25) : Story::where('active', 1)->orderBy('view', 'DESC')->paginate(25);
        if(!$stories) abort(404);
        $data     = [
            'title'  => 'Truyện Hot',
            'description' => 'Truyện Hot',
            'keyword' => '',
            'alias'  => route('danhsach.truyenhot'),
            'stories' => $stories,
        ];
        $breadcrumb = [[route('danhsach.truyenhot'), 'Truyện Hot']];
        return view('user.list_story', compact('data', 'breadcrumb'));
    }

    // Truyện full
    public function getListFullStory()
    {
        $stories = Story::where(['status' => 1, 'active' => 1])->orderBy('updated_at', 'DESC')->paginate(25);
        if(!$stories) abort(404);
         $data     = [
            'title'  => 'Danh sách truyện full',
             'description' => 'Truyện full',
             'keyword' => '',
            'alias'  => route('danhsach.truyenfull'),
            'stories' => $stories,
        ];
        $breadcrumb = [[route('danhsach.truyenfull'), 'Danh sách truyện full']];
        return view('user.list_story', compact('data', 'breadcrumb'));
    }

    // Truyện theo the loại
    public function getListByCategory(Request $request, $category)
    {

        $categorys = Category::where('alias', $category)->first();
        if(!$categorys) abort(404);
        $story    = ($request->get('filter') == 'full') ? $category->stories()->where('status', 1)->orderBy('updated_at', 'DESC')->paginate(25) : $categorys->stories()->orderBy('updated_at', 'DESC')->paginate(25);
        $data     = [
            'title'  => $categorys->name,
            'alias'  => $categorys->alias,
            'keyword'=> $categorys->keyword,
            'description' => $categorys->description,
            'stories' => $story,
        ];

        $breadcrumb = [[route('category.list.index', $categorys->alias), $categorys->name]];
        return view('user.list_story', compact('data', 'breadcrumb'));
    }

    // Truyện theo tac gia
    public function getListByAuthor($alias, Request $r)
    {
        $author = Author::where('alias', $alias)->first();
        if(!$author) abort(404);
        $story    = ($r->get('filter') == 'full') ? $author->stories()->where('status', 1)->paginate(25) : $author->stories()->paginate(25);
        $data     = [
            'title'  => $author->name,
            'alias'  => $author->alias,
            'keyword'=> $author->keyword,
            'description' => $author->description,
            'stories' => $story,
        ];
        $breadcrumb = [[route('author.list.index', $author->alias), $author->name]];
        return view('user.list_story', compact('data', 'breadcrumb'));
    }
    // tìm kiếm
    public function getListBySearch(Request $r)
    {
        $q = '%' . $r->get('q') . '%';

        $story    = Story::where('name', 'like', $q)->orderBy('updated_at', 'DESC')->paginate(25);
        $data     = [
            'title'  => 'Tìm kiếm: '. $r->get('q') . ' ('. $story->count() .')',
            'alias'  => null,
            'keyword'=> '',
            'description' => '',
            'stories' => $story,
        ];

        $breadcrumb = [[route('danhsach.search'), 'Tìm kiếm: '. $r->get('q')]];
        return view('user.list_story', compact('data', 'breadcrumb'));
    }

    // Hiển thị truyện
    public function showInfoStory($alias, Request $r)
    {

        $story = Story::with('user')->where(['alias'=> $alias, 'active' => 1])->first();
        if(!$story) abort(404);
        $breadcrumb = [[route('story.show', $story->alias), $story->name]];
        if(!$r->session()->has('viewStory' . $story->id)) {
            $story->view = $story->view + 1;
            $story->timestamps = false;
            $story->save();
            $r->session()->put('viewStory' . $story->id, true);
        }

        return view('user.show_story', compact('story','breadcrumb'));
    }

    // Hiển thị chương truyện
    public function showInfoChapter($alias, $aliasChapter, Request $r)
    {
        $story = Story::where('alias', $alias)->where('active', 1)->first();
        if(!$story) abort(404);
        $chapter = $story->chapters()->where('active', 1)->where('alias', $aliasChapter)->where('active', 1)->first();
        $totalChapters = $story->chapters()->count();
        $currentChapter = (int) str_replace('chuong-', '', $aliasChapter);
        if(!$chapter) abort(404);

        $viewed = new \App\Models\Viewed();
        $viewed->addToListReading($story->id, $chapter->id, 'nd');

        if(!$r->session()->has('viewChapter' . $chapter->id))
        {
            $story->view = $story->view+1;
            $story->timestamps = false;
            $story->save();
            $chapter->view = $chapter->view+1;
            $chapter->timestamps = false;
            $chapter->save();
            $r->session()->put('viewChapter' . $chapter->id, true);
        }

        $chapterNav = [
            'nextChapter' => ($currentChapter != $totalChapters) ? $story
                ->chapters()
                ->select('subname','alias')
                ->where('alias', 'chuong-' . ($currentChapter + 1))
                ->first() : false,
            'previousChapter' => ($currentChapter > 1) ? $story
                ->chapters()
                ->select('subname','alias')
                ->where('alias', 'chuong-' . ($currentChapter - 1))
                ->first() : false,
        ];
        $breadcrumb = [[route('story.show', $story->alias), $story->name], [route('chapter.show', [$story->alias, $chapter->alias]), $chapter->subname]];
        return view('user.show_chapter', compact('story', 'chapter', 'chapterNav', 'breadcrumb'));
    }

    // AJAX
    public function getAjaxListNewStories(Request $r)
    {
        $categoryID = $r->get('categoryID');
        return Story::getListNewStories($categoryID);
    }
    public function getAjaxListHotStories(Request $r)
    {
        $categoryID = $r->get('categoryID');
        return Story::getListHotStories($categoryID);
    }

}
