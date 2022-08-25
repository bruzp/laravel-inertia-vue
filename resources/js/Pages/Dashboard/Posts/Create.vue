<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import BreezeButton from "@/Components/Button.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref, watch } from 'vue';

const props = defineProps({
    posts: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    title: '',
    slug: '',
    content: '',
    featured_image: null,
});

const submit = () => {
    form.post(route("posts.store"));
};

const temp_image = ref('/images/no-preview.jpg');

function fileChange(event) {
    temp_image.value = URL.createObjectURL(event.target.files[0]);
}

</script>

<template>

    <Head title="Post Create" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Post Create
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <input type="checkbox" id="my-error-modal" class="modal-toggle" v-model="form.hasErrors" />
                        <div class="modal">
                            <div class="modal-box relative">
                                <label for="my-error-modal"
                                    class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                                <h3 class="text-lg font-bold">Error Message!</h3>
                                <p class="py-4">Something went wrong!</p>
                            </div>
                        </div>

                        <form @submit.prevent="submit">
                            <div class="avatar">
                                <div class="w-44 rounded">
                                    <img :src="temp_image" />
                                </div>
                            </div>
                            <div class="mb-6">
                                <div>
                                    <progress class="progress progress-success w-44" v-if="form.progress"
                                        :value="form.progress.percentage" max="100">
                                        {{ form.progress.percentage }}%
                                    </progress>
                                </div>
                                <button @click="$refs.featured_image.click()" type="button"
                                    class="btn btn-outline btn-primary btn-sm w-44">Featured Image</button>
                                <input ref="featured_image" style="display:none" type="file" @change="fileChange"
                                    @input="form.featured_image = $event.target.files[0]" />
                            </div>
                            <div class="mb-6">
                                <label for="Title"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Title</label>
                                <input type="text" v-model="form.title" name="title"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" />
                                <div v-if="form.errors.title" class="text-sm text-red-600">
                                    {{ form.errors.title }}
                                </div>
                            </div>
                            <div class="mb-6">
                                <label for="Slug"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Slug</label>
                                <input type="text" v-model="form.slug" name="title"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="" />
                                <div v-if="form.errors.slug" class="text-sm text-red-600">
                                    {{ form.errors.slug }}
                                </div>
                            </div>
                            <div class="mb-6">
                                <label for="slug"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Content</label>
                                <textarea type="text" v-model="form.content" name="content" id=""
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>

                                <div v-if="form.errors.content" class="text-sm text-red-600">
                                    {{ form.errors.content }}
                                </div>
                            </div>
                            <button type="submit"
                                class="text-white bg-blue-700  focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                                :disabled="form.processing" :class="{ 'opacity-25': form.processing }">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>