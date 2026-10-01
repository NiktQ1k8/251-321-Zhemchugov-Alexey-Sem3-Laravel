@if ($errors->any())
    <div role="alert" class="rounded-md bg-red-50 p-4 text-red-700">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label for="name" class="mb-2 block font-medium">Название</label>
    <input id="name" name="name" type="text" value="{{ old('name', $article->name) }}" required maxlength="255" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2">
</div>

<div>
    <label for="short_desc" class="mb-2 block font-medium">Краткое описание (необязательно)</label>
    <textarea id="short_desc" name="short_desc" rows="3" maxlength="1000" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2">{{ old('short_desc', $article->short_desc) }}</textarea>
</div>

<div>
    <label for="body" class="mb-2 block font-medium">Текст статьи</label>
    <textarea id="body" name="body" rows="8" required maxlength="20000" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2">{{ old('body', $article->body) }}</textarea>
</div>

<div>
    <label for="published_at" class="mb-2 block font-medium">Дата публикации</label>
    <input id="published_at" name="published_at" type="date" value="{{ old('published_at', $article->published_at?->format('Y-m-d')) }}" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2">
</div>

<div>
    <label for="preview_image" class="mb-2 block font-medium">Превью (необязательно)</label>
    <select id="preview_image" name="preview_image" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2">
        <option value="">Без изображения</option>
        <option value="preview.jpg" @selected(old('preview_image', $article->preview_image) === 'preview.jpg')>Тигры</option>
        <option value="preview_2.jpg" @selected(old('preview_image', $article->preview_image) === 'preview_2.jpg')>Автомобиль</option>
    </select>
</div>

<div>
    <label for="full_image" class="mb-2 block font-medium">Полное изображение (необязательно)</label>
    <select id="full_image" name="full_image" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2">
        <option value="">Без изображения</option>
        <option value="full.jpeg" @selected(old('full_image', $article->full_image) === 'full.jpeg')>Тигры</option>
        <option value="full_2.jpeg" @selected(old('full_image', $article->full_image) === 'full_2.jpeg')>Автомобиль</option>
    </select>
</div>

<div class="flex flex-wrap items-center gap-4">
    <button type="submit" class="rounded-md bg-blue-700 px-5 py-3 font-medium text-white hover:bg-blue-800">Сохранить</button>
    <a href="{{ $article->exists ? route('articles.show', $article) : route('home') }}" class="text-blue-700 underline underline-offset-4">Отмена</a>
</div>
