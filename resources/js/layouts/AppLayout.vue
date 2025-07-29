<script setup lang="ts">
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const { auth } = usePage().props as any;
</script>

<template>
    <div>
        <AppLayout :breadcrumbs="breadcrumbs" :auth="auth">
            <slot />
        </AppLayout>
    </div>
</template>
