<?php

namespace App\Transformers;

use App\Models\Admin\ListNews;
use League\Fractal\TransformerAbstract;
use Str;

class NewsTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(ListNews $news)
    {
        return [
            'id' => $news->id,
            'title' => $news->title,
            'slug' => $news->slug,
            'content' => $news->content,
            // 'description' => Str::limit($news->content, 150),
            'image' => asset('assets/news/images/' . $news->image),
            'category_name' => $news->category_id,
        ];
    }
}
