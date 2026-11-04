<?php

namespace Modules\Auth\Http\Controllers\API\V1;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Modules\Auth\Facades\Unsplash;

#[Group('Unsplash')]
class UnsplashController
{
    #[Endpoint(title: 'Get Background CSS')]
    #[Response(200, 'Background css retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Background css retrieved.', 'data' => ['css' => '',
        'error' => null,
        'photo' => '',
        'author' => '',
        'authorURL' => '',
        'utm' => '',]]])]
    public function getBackgroundCSS()
    {
        return apiResponse('Background css retrieved', Unsplash::returnBackground());
    }

    #[Endpoint(title: 'Get Background Image')]
    #[Response(200, 'Background image retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Background image retrieved.', 'data' => [
        [
            'id' => '',
            'alternative_slugs' => [],
            'created_at' => '',
            'updated_at' => '',
            'promoted_at' => '',
            'width' => '',
            'height' => '',
            'color' => '',
            'blur_hash' => '',
            'description' => '',
            'breadcrumbs' => [],
            'urls' => [],
            'likes' => 37,
            'liked_by_user' => false,
            'bookmarked' => false,
            'current_user_collections' => [],
            'sponsorship' => null,
            'topic_submissions' => [],
            'asset_type' => 'photo',
            'slug' => '',
            'alt_description' => '',
            'short_description' => null,
            'seo_meta_description' => null,
            'links' => [],
            'user' => [],
            'exif' => [],
            'location' => [],
            'views' => 0,
            'downloads' => 0
        ]
    ]]])]
    public function getBackgroundImage()
    {
        return apiResponse('Background image retrieved', Unsplash::getRandomUnsplashImage());
    }

    #[Endpoint(title: 'Get UTM Source')]
    #[Response(200, 'UTM retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'UTM retrieved.', 'data' => ['utm' => '']]])]
    public function getUTM()
    {
        return apiResponse('UTM retrieved', ['utm' => Unsplash::getUTM()]);
    }
}
