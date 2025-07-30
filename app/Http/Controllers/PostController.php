<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBlogEtcPostRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use WebDevEtc\BlogEtc\Events\BlogPostAdded;
use WebDevEtc\BlogEtc\Events\UploadedImage;
use WebDevEtc\BlogEtc\Helpers;
use WebDevEtc\BlogEtc\Interfaces\LegacyGetImageFileInterface;
use WebDevEtc\BlogEtc\Models\Post;
use WebDevEtc\BlogEtc\Models\UploadedPhoto;
//use WebDevEtc\BlogEtc\Requests\CreateBlogEtcPostRequest;
use App\Http\Requests\UploadBlogImageRequest;
//use WebDevEtc\BlogEtc\Services\UploadsService;
use App\Http\Requests\CreateBlogEtcPostRequest;
use App\Http\Requests\BaseBlogEtcPostRequest;
use App\Services\UploadsService;


use Exception;
use File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Image;
use Intervention\Image\Constraint;
use RuntimeException;
use Storage;
use WebDevEtc\BlogEtc\Requests\DeleteBlogEtcPostRequest;
use WebDevEtc\BlogEtc\Requests\SearchRequest;
use WebDevEtc\BlogEtc\Services\CaptchaService;
use WebDevEtc\BlogEtc\Services\CategoriesService;
use WebDevEtc\BlogEtc\Services\PostsService;

class PostController extends Controller
{
    /**
     * @var UploadsService
     */
    private $uploadsService;
    /** @var PostsService */
    private $postsService;

    /** @var CategoriesService */
    private $categoriesService;

    /** @var CaptchaService */
    private $captchaService;

    public function __construct(UploadsService $uploadsService,PostsService $postsService,
                                CategoriesService $categoriesService,
                                CaptchaService $captchaService)
    {
        $this->middleware('auth')->except('show');;
        $this->uploadsService = $uploadsService;
        $this->postsService = $postsService;
        $this->categoriesService = $categoriesService;
        $this->captchaService = $captchaService;
    }

    /**
     * View all posts.
     *
     * @return mixed
     */
    public function index()
    {
        $posts = Post::orderBy('posted_at', 'desc')->paginate(10);

        return view('blogetc_admin::index', ['posts' => $posts]);
    }

    /**
     * Show form for creating new post.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('blogetc_admin::posts.add_post');
    }

    /**
     * @deprecated - use create() instead
     */
    public function create_post()
    {
        return $this->create();
    }

    public function store(CreateBlogEtcPostRequest $request)
    {

        $editUrl = $this->legacyStorePost($request);

        return redirect($editUrl);
    }

    public function legacyStorePost(CreateBlogEtcPostRequest $request)
    {

        $new_blog_post = new Post($request->all());
        $this->legacyProcessUploadedImages($request, $new_blog_post);

        if (!$new_blog_post->posted_at) {
            $new_blog_post->posted_at = Carbon::now();
        }

        $new_blog_post->user_id = Auth::user()->id;
        $new_blog_post->save();

        $new_blog_post->categories()->sync($request->categories());

        Helpers::flashMessage('New post Added !!');
        event(new BlogPostAdded($new_blog_post));

        return $new_blog_post->editUrl();
    }

    public function legacyProcessUploadedImages(LegacyGetImageFileInterface $request, Post $new_blog_post)
    {
        if (!config('blogetc.image_upload_enabled')) {
            return;
        }

        $this->increaseMemoryLimit();

        $uploaded_image_details = [];

        foreach ((array)config('blogetc.image_sizes') as $size => $image_size_details) {
            if ($image_size_details['enabled'] && $photo = $request->get_image_file($size)) {
                $uploaded_image = $this->legacyUploadAndResize($new_blog_post, $new_blog_post->title, $image_size_details,
                    $photo);

                $new_blog_post->$size = $uploaded_image['filename'];
                $uploaded_image_details[$size] = $uploaded_image;
            }
        }

        // todo: link this to the blogetc_post row.
        if (count(array_filter($uploaded_image_details)) > 0) {
            UploadedPhoto::create([
                'source' => 'BlogFeaturedImage',
                'uploaded_images' => $uploaded_image_details,
            ]);
        }
    }

    public function increaseMemoryLimit(): void
    {
        // increase memory - change this setting in config file
        if (config('blogetc.memory_limit')) {
            ini_set('memory_limit', config('blogetc.memory_limit'));
        }
    }

    protected function legacyUploadAndResize(Post $new_blog_post = null, $suggested_title, $image_size_details, $photo)
    {
        $image_filename = $this->legacyGetImageFilename($suggested_title, $image_size_details, $photo);
        $destinationPath = $this->image_destination_path();

        $resizedImage = Image::make($photo->getRealPath());

        if (is_array($image_size_details)) {
            $w = $image_size_details['w'];
            $h = $image_size_details['h'];

            if (isset($image_size_details['crop']) && $image_size_details['crop']) {
                $resizedImage = $resizedImage->fit($w, $h);
            } else {
                $resizedImage = $resizedImage->resize($w, $h, static function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        } elseif ('fullsize' === $image_size_details) {
            $w = $resizedImage->width();
            $h = $resizedImage->height();
        } else {
            throw new Exception('Invalid image_size_details value');
        }

        $resizedImage->save($destinationPath . '/' . $image_filename, config('blogetc.image_quality', 80));

        event(new UploadedImage($image_filename, $resizedImage, $new_blog_post, __METHOD__));

        return [
            'filename' => $image_filename,
            'w' => $w,
            'h' => $h,
        ];
    }

    public function legacyGetImageFilename(string $suggested_title, $image_size_details, UploadedFile $photo)
    {
        $base = $this->generate_base_filename($suggested_title);

        // $wh will be something like "-1200x300"
        $wh = $this->getDimensions($image_size_details);
        $ext = '.' . $photo->getClientOriginalExtension();

        for ($i = 1; $i <= 10; $i++) {
            // add suffix if $i>1
            $suffix = $i > 1 ? '-' . str_random(5) : '';

            $attempt = str_slug($base . $suffix . $wh) . $ext;

            if (!File::exists($this->image_destination_path() . '/' . $attempt)) {
                // filename doesn't exist, let's use it!
                return $attempt;
            }
        }

        // too many attempts...
        throw new RuntimeException("Unable to find a free filename after $i attempts - aborting now.");
    }


    public function image_destination_path()
    {
        $path = public_path('/' . config('blogetc.blog_upload_dir'));
        $this->check_image_destination_path_is_writable($path);

        return $path;
    }

    protected function check_image_destination_path_is_writable($path)
    {
        if (!is_writable($path)) {
            throw new RuntimeException("Image destination path is not writable ($path)");
        }
    }

    protected function generate_base_filename(string $suggested_title)
    {
        $base = substr($suggested_title, 0, 100);
        if (!$base) {
            // if we have an empty string then we should use a random one:
            $base = 'image-' . str_random(5);

            return $base;
        }

        return $base;
    }

    protected function getDimensions($imageSize): string
    {
        if (is_array($imageSize)) {
            return '-' . $imageSize['w'] . 'x' . $imageSize['h'];
        }

        return '-' . Str::slug(substr($imageSize, 0, 30));
    }

    public function blogImageStore(UploadBlogImageRequest $request)
    {

        // Uses some legacy code - this will be refactored and fixed soon!
        $processed_images = $this->legacyProcessUploadedImagesSingle($request);

        return view('blogetc_admin::imageupload.uploaded', ['images' => $processed_images]);

    }

    public function legacyProcessUploadedImagesSingle(UploadBlogImageRequest $request)
    {
        $this->increaseMemoryLimit();
        $photo = $request->file('upload');

        $uploaded_image_details = [];

        $sizes_to_upload = $request->get('sizes_to_upload');

        // now upload a full size - this is a special case, not in the config file. We only store full size images in this class, not as part of the featured blog image uploads.
        if (isset($sizes_to_upload['blogetc_full_size']) && 'true' === $sizes_to_upload['blogetc_full_size']) {
            $uploaded_image_details['blogetc_full_size'] = $this->legacyUploadAndResize(null, $request->get('image_title'),
                'fullsize', $photo);
        }

        foreach ((array)config('blogetc.image_sizes') as $size => $image_size_details) {
            if (!isset($sizes_to_upload[$size]) || !$sizes_to_upload[$size] || !$image_size_details['enabled']) {
                continue;
            }

            $uploaded_image_details[$size] = $this->legacyUploadAndResize(null, $request->get('image_title'),
                $image_size_details, $photo);
        }

        UploadedPhoto::create([
            'image_title' => $request->get('image_title'),
            'source' => 'ImageUpload',
            'uploader_id' => (int)Auth::id(),
            'uploaded_images' => $uploaded_image_details,
        ]);

        return $uploaded_image_details;
    }


    public function update(UpdateBlogEtcPostRequest $request, $blogPostId)
    {

        $editUrl = $this->uploadsService->legacyUpdatePost($request, $blogPostId);

        return redirect($editUrl);
    }

    /**
     * Delete a post.
     *
     * @param $blogPostId
     *
     * @return mixed
     */
    public function destroy(DeleteBlogEtcPostRequest $request, $blogPostId)
    {
        $deletedPost = $this->uploadsService->legacyDestroyPost($request, $blogPostId);

        return view('blogetc_admin::posts.deleted_post')
            ->withDeletedPost($deletedPost);
    }

    /**
     * Show form to edit post.
     *
     * @param $blogPostId
     *
     * @return mixed
     */
    public function edit($blogPostId)
    {
        $post = Post::findOrFail($blogPostId);

        return view('blogetc_admin::posts.edit_post', ['post' => $post]);
    }


    /**
     * View a single post and (if enabled) comments.
     */
    public function show(Request $request, string $postSlug): \Illuminate\View\View
    {
        $blogPost = $this->postsService->findBySlug($postSlug);

        $usingCaptcha = $this->captchaService->getCaptchaObject();

        if (null !== $usingCaptcha && method_exists($usingCaptcha, 'runCaptchaBeforeShowingPosts')) {
            $usingCaptcha->runCaptchaBeforeShowingPosts($request, $blogPost);
        }

        return view(
            'blogetc::single_post',
            [
                'post' => $blogPost,
                'captcha' => $usingCaptcha,
                'comments' => $blogPost->comments->load('user'),
            ]
        );
    }
}
