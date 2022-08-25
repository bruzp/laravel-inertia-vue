<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import BreezeButton from "@/Components/Button.vue";
import Pagination from "@/Components/Pagination.vue";
import SortArrowUp from "@/Components/SortArrowUp.vue";
import SortArrowDown from "@/Components/SortArrowDown.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref, watch, reactive, onMounted } from 'vue';

const props = defineProps({
    posts: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    }
});

const form = useForm();

const params = reactive({
    search: props.filters.search,
    field: props.filters.field,
    direction: props.filters.direction
});

watch(params, () => {
    let p = params;

    Object.keys(p).forEach(key => {
        if (p[key] == '') {
            delete p[key];
        }
    });

    Inertia.get(route('posts.index'), p, { preserveState: true, preserveScroll: true })
});

function sort(field) {
    params.field = field;
    params.direction = params.direction === 'asc' ? 'desc' : 'asc';
}

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route("posts.destroy", id));
    }
}

function determineSortDirection(field, direction) {
    return params.field === field && params.direction === direction;
}

function publish(post, is_active) {
    Inertia.put(route("posts.publish", post), {
        is_active: is_active
    }, {
        preserveState: true,
        preserveScroll: true
    });
}

</script>

<template>

    <Head title="Posts" />
    <BreezeAuthenticatedLayout> <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800"> Posts Index </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="$page.props.flash.message" class="alert alert-success shadow-lg mb-5">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $page.props.flash.message }}</span>
                    </div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-2">
                            <Link class="btn btn-accent" :href="route('posts.create')">Add Post</Link>
                        </div>
                        <div class="relative">
                            <div class="mt-6 mb-6">
                                <label for="default-search"
                                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-gray-300">Search</label>
                                <input id="default-search" type="search" v-model.lazy="params.search"
                                    class="input w-full max-w-xs" placeholder="Search...">
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table table-normal w-full text-center">
                                    <thead
                                        class=" text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 ">
                                        <tr>
                                            <th>Featured Image</th>
                                            <th scope="col" class="px-6 py-3" @click="sort('id')">
                                                <span class="inline-flex px-6 py-3 w-full justify-between">
                                                    #
                                                    <SortArrowUp v-if="determineSortDirection('id', 'asc')">
                                                    </SortArrowUp>
                                                    <SortArrowDown v-if="determineSortDirection('id', 'desc')">
                                                    </SortArrowDown>
                                                </span>
                                            </th>
                                            <th scope="col" class="px-6 py-3" @click="sort('title')">
                                                <span class="inline-flex px-6 py-3 w-full justify-between">
                                                    Title
                                                    <SortArrowUp v-if="determineSortDirection('title', 'asc')">
                                                    </SortArrowUp>
                                                    <SortArrowDown v-if="determineSortDirection('title', 'desc')">
                                                    </SortArrowDown>
                                                </span>
                                            </th>
                                            <th scope="col" class="px-6 py-3" @click="sort('username')">
                                                <span class="inline-flex px-6 py-3 w-full justify-between">
                                                    Username
                                                    <SortArrowUp v-if="determineSortDirection('username', 'asc')">
                                                    </SortArrowUp>
                                                    <SortArrowDown v-if="determineSortDirection('username', 'desc')">
                                                    </SortArrowDown>
                                                </span>
                                            </th>
                                            <th scope="col" class="px-6 py-3" @click="sort('slug')">
                                                <span class="inline-flex px-6 py-3 w-full justify-between">
                                                    Slug
                                                    <SortArrowUp v-if="determineSortDirection('slug', 'asc')">
                                                    </SortArrowUp>
                                                    <SortArrowDown v-if="determineSortDirection('slug', 'desc')">
                                                    </SortArrowDown>
                                                </span>
                                            </th>
                                            <th scope="col" class="px-6 py-3">Publish</th>
                                            <th scope="col" class="px-6 py-3">Edit</th>
                                            <th scope="col" class="px-6 py-3">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="post in posts.data" :key="post.id"
                                            class=" bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                                            <td scope="row"
                                                class=" px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap ">
                                                <div class="avatar">
                                                    <div class="w-20 rounded">
                                                        <img :src="post.featured_image ? '/storage/' + post.featured_image : '/images/no-preview.jpg'"
                                                            alt="Tailwind-CSS-Avatar-component" />
                                                    </div>
                                                </div>
                                            </td>
                                            <td scope="row"
                                                class=" px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap ">
                                                {{ post.id }} </td>
                                            <td scope="row"
                                                class=" px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap ">
                                                {{ post.title }} </td>
                                            <td scope="row"
                                                class=" px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap ">
                                                {{ post.username }} </td>
                                            <td scope="row"
                                                class=" px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap ">
                                                {{ post.slug }} </td>
                                            <td scope="row"
                                                class=" px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap ">
                                                <div v-if="post.permissions.publish && post.permissions.unpublish">
                                                    <input @change="publish(post.id, post.is_active)" type="checkbox"
                                                        true-value="1" false-value="0" v-model="post.is_active"
                                                        class="checkbox checkbox-md checkbox-accent" />
                                                </div>
                                            </td>
                                            <td scope="row"
                                                class=" px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap ">
                                                <div v-if="post.permissions.edit">
                                                    <Link :href="route('posts.edit', post.id)" class="btn btn-warning">
                                                    Edit</Link>
                                                </div>
                                            </td>
                                            <td scope="row"
                                                class=" px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap ">
                                                <button v-if="post.permissions.delete" @click="destroy(post.id)"
                                                    class="btn btn-error">Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <Pagination class="mt-6" :links="posts.links" />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>