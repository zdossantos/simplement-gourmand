<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type AppPageProps } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import { Refrigerator } from 'lucide-vue-next';

interface Food {
	id: string | null
	name: string
	quantity: number
	unit: string
	image_url: string
}


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

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4 overflow-x-auto">

                <div v-if="food.length > 0" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <Card v-for="food in food" :key="food.id || food.name" class="overflow-hidden hover:shadow-md transition-shadow">
                        <CardContent class="p-0">
                            <div class="aspect-square bg-gray-100 flex items-center justify-center">
                                <Refrigerator class="h-12 w-12 text-gray-400" />
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-sm line-clamp-2 min-h-[2.5rem]">
                                    {{ food.name || 'Nom non disponible' }}
                                </h3>
                                <p v-if="food.id" class="text-xs text-muted-foreground mt-1">
                                    ID: {{ food.id }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
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
    </AppLayout>
</template>