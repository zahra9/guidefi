<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use WebDevEtc\BlogEtc\Interfaces\LegacyGetImageFileInterface;
use App\Http\Requests\BaseBlogEtcPostRequest;
use WebDevEtc\BlogEtc\Requests\Traits\HasCategoriesTrait;
use WebDevEtc\BlogEtc\Requests\Traits\HasImageUploadTrait;

class CreateBlogEtcPostRequest extends BaseBlogEtcPostRequest implements LegacyGetImageFileInterface
{
    use HasCategoriesTrait;
    use HasImageUploadTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $return = $this->baseBlogPostRules();
        $return['slug'][] = Rule::unique('blog_etc_posts', 'slug');

        return $return;
    }
}
