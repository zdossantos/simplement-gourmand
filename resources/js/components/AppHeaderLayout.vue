<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import type { BreadcrumbItemType } from '@/types';
import AppNavBar from '@/components/AppNavBar.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    auth?: Object;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="header">
        <AppNavBar :auth="auth" />
        <AppContent class="overflow-x-hidden">
            <slot />
        </AppContent>
    </AppShell>
</template>
