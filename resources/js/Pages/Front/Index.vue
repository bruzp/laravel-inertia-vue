<script setup>
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import { ref, onMounted, reactive } from 'vue';
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    posts: {
        type: Object,
        default: () => ({}),
    },
})

const load_more_intersect = ref(null);

const all_posts = ref(props.posts.data);

const initial_url = ref(usePage().url.value);

onMounted(() => {
    const observer = new IntersectionObserver(entries => entries.forEach(entry => entry.isIntersecting && loadMorePosts(), {
        rootMargin: "-200px 0px 0px 0px"
    }));

    observer.observe(load_more_intersect.value)
})

function loadMorePosts() {
    if (props.posts.next_page_url === null) {
        return
    }

    Inertia.get(props.posts.next_page_url, {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['posts'],
        onSuccess: () => {
            all_posts.value = [...all_posts.value, ...props.posts.data]
            window.history.replaceState({}, '', initial_url.value)
        }
    })
}

</script>

<template>

    <Head title="Posts" />

    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="$page.props.canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</Link>

                <Link v-if="$page.props.canRegister" :href="route('register')"
                    class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</Link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div v-for="post in all_posts" :key="post.id" class="card lg:card-side bg-base-100 shadow-xl mb-6"
                data-theme="coffee">
                <figure style="max-width: 400px; max-height: 400px;"><img class="w-full"
                        :src="post.featured_image ? '/storage/' + post.featured_image : '/images/no-preview.jpg'">
                </figure>
                <div class="card-body">
                    <h2 class="card-title">{{ post.title }}</h2>
                    <p>{{ post.content }}</p>
                    <div class="card-actions justify-end">
                        <Link :href="route('front.posts.show', post.slug)" class="btn btn-primary">View more...
                        </Link>
                    </div>
                </div>
            </div>
            <div v-if="all_posts.length" ref="load_more_intersect" class="text-center mb-6"><button
                    class="btn btn-ghost loading"></button>
            </div>
            <div v-else>
                <div class="alert alert-warning shadow-lg">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Warning: No Posts Yet!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
