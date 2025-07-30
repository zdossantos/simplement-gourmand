
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
        <AppContent class="overflow-x-hidden container mx-auto w-full px-4 sm:px-6 lg:px-8 max-w-full sm:max-w-[540px] md:max-w-[720px] lg:max-w-[960px] xl:max-w-[1400px]">
            <slot />
        </AppContent>
    </AppShell>
</template>
