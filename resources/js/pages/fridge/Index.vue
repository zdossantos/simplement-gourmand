<script setup lang="ts">
import { type AppPageProps, type BreadcrumbItem } from '@/types';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import FoodCard from '@/components/food/FoodCard.vue';
import type { Food } from '@/types/food';

interface FridgePageProps extends AppPageProps {
  food: Food[]
}


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
	{
		title: 'Réfrigérateur',
		href: '/fridge',
	},
];

const page = usePage<FridgePageProps>()
const food = computed(() => page.props.food || [])

</script>

<template>
    <Head title="Recherche de produits" />

    <AppSidebarLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4 overflow-x-auto">

                <div v-if="food.length > 0" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <FoodCard v-for="food in food" :key="food.openfoodfacts_id || food.name" :food="food" />
                </div>

                <Card v-else>
                    <CardContent class="p-8 text-center">
                        <Fridge class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                        <p class="text-muted-foreground">
                            Aucun aliment trouvé dans votre réfrigérateur.
                        </p>
                        <p class="text-sm text-muted-foreground mt-2">
                            Vous n'avez pas d'aliment dans votre réfrigérateur.
                        </p>
                    </CardContent>
                </Card>
            </div>
    </AppSidebarLayout>
</template>