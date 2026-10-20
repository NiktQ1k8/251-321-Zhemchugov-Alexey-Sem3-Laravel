<div>
    <label for="comment-body" class="mb-2 block font-medium">Текст комментария</label>
    <textarea id="comment-body" name="body" rows="4" required maxlength="5000" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2" @error('body') aria-invalid="true" aria-describedby="comment-body-error" @enderror>{{ old('body', $comment->body) }}</textarea>
    @error('body')
        <p id="comment-body-error" role="alert" class="mt-2 text-sm text-red-700">{{ $message }}</p>
    @enderror
</div>

<button type="submit" class="rounded-md bg-blue-700 px-5 py-3 font-medium text-white hover:bg-blue-800">{{ $comment->exists ? 'Сохранить' : 'Добавить комментарий' }}</button>
