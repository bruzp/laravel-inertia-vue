<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    post: {
        type: Object,
        default: () => ({}),
    },
})
</script>

<template>

    <Head title="Post Detail" />

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

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-12 mb-12">
            <div class="card w-full bg-base-100 shadow-xl">
                <figure><img :src="post.featured_image ? '/storage/' + post.featured_image : '/images/no-preview.jpg'"
                        alt="Shoes" /></figure>
                <div class="card-body">
                    <h2 class="card-title">{{ post.title }}</h2>
                    <p>{{ post.content }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
