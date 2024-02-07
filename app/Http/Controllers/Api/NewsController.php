<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\News;
use App\Transformers\NewsTransformer;
use Illuminate\Http\Request;
use League\Fractal;

class NewsController extends Controller
{
    public function get(News $news)
    {
        $n = $news->all();

        return fractal()->collection($n)->transformWith(new NewsTransformer)->toArray();
    }
}
