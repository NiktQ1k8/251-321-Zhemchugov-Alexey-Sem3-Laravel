<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SaveArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $article = $this->route('article');

        Gate::authorize($article ? 'update' : 'create', $article ?? Article::class);

        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'short_desc' => ['nullable', 'string', 'max:1000'],
            'body' => ['required', 'string', 'max:20000'],
            'published_at' => ['required', 'date_format:Y-m-d'],
            'preview_image' => ['nullable', 'required_with:full_image', Rule::in(['preview.jpg', 'preview_2.jpg'])],
            'full_image' => ['nullable', 'required_with:preview_image', Rule::in(['full.jpeg', 'full_2.jpeg'])],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Поле «:attribute» обязательно.',
            'required_with' => 'Выберите и превью, и полное изображение.',
            'string' => 'Поле «:attribute» должно быть строкой.',
            'max' => 'Поле «:attribute» должно содержать не более :max символов.',
            'date_format' => 'Укажите корректную дату публикации.',
            'in' => 'Выберите изображение из списка.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'название',
            'short_desc' => 'краткое описание',
            'body' => 'текст статьи',
            'published_at' => 'дата публикации',
            'preview_image' => 'превью',
            'full_image' => 'полное изображение',
        ];
    }
}
