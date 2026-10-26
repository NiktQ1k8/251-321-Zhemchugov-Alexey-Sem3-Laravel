<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const article = ref(null);

onMounted(() => {
    window.Echo?.channel('articles').listen('NewArticleEvent', (event) => {
        article.value = event.article;
    });
});

onUnmounted(() => window.Echo?.leaveChannel('articles'));
</script>

<template>
    <div aria-live="polite" aria-atomic="true">
        <div
            v-if="article"
            role="status"
            class="fixed top-4 right-4 left-4 z-50 rounded-lg border border-blue-200 bg-white p-5 shadow-lg sm:left-auto sm:w-96"
        >
            <div class="flex items-start gap-4">
                <div class="min-w-0 flex-1">
                    <p class="mb-2 text-sm text-slate-600">Добавлена новая статья</p>
                    <a :href="article.url" class="font-semibold wrap-break-word text-blue-700 underline hover:text-blue-900">
                        {{ article.name }}
                    </a>
                </div>
                <button
                    type="button"
                    aria-label="Закрыть уведомление"
                    class="shrink-0 rounded px-2 text-xl text-slate-500 hover:bg-slate-100 hover:text-slate-900"
                    @click="article = null"
                >×</button>
            </div>
        </div>
    </div>
</template>
