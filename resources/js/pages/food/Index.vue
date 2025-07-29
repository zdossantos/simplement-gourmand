<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type AppPageProps } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { useFoodSearchWeb } from '@/composables/useFoodSearchWeb';
import Input from '@/components/ui/input/Input.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import { Search, Package, X, Apple } from 'lucide-vue-next';

interface FoodProduct {
  id: string | null
  name: string
  image?: string
  hasImage?: boolean
}


interface ProductsPageProps extends AppPageProps {
  products: FoodProduct[]
  search: string
  count: number
}

const page = usePage<ProductsPageProps>()
const products = computed(() => page.props.products || [])
const search = computed(() => page.props.search || '')
const count = computed(() => page.props.count || 0)

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Aliments',
        href: '/food',
    },
];

const { searchQuery, isLoading, clearSearch } = useFoodSearchWeb()

onMounted(() => {
  searchQuery.value = search.value
})
</script>

<template>
    <Head title="Recherche de produits" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4 overflow-x-auto">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Search class="h-5 w-5" />
                        Recherche de produits alimentaires
                    </CardTitle>
                    <CardDescription>
                        Recherchez des produits dans la base de données OpenFoodFacts
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="relative">
                        <Input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Tapez au moins 2 caractères pour rechercher..."
                            class="pr-10"
                        />
                        <button
                            v-if="searchQuery"
                            @click="clearSearch"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                    <div v-if="search && !isLoading" class="mt-3 text-sm text-muted-foreground">
                        {{ count }} produit(s) trouvé(s) pour "{{ search }}"
                    </div>
                </CardContent>
            </Card>

            <div v-if="search || isLoading">
                <div v-if="isLoading" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <Card v-for="i in 8" :key="`skeleton-${i}`">
                        <CardContent class="p-4">
                            <Skeleton class="h-32 w-full mb-4 rounded" />
                            <Skeleton class="h-4 w-3/4 mb-2" />
                            <Skeleton class="h-3 w-1/2" />
                        </CardContent>
                    </Card>
                </div>

                <div v-else-if="products.length > 0" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <Card v-for="product in products" :key="product.id || product.name" class="overflow-hidden hover:shadow-md transition-shadow">
                        <CardContent class="p-0">
                            <div class="aspect-square bg-gray-100 flex items-center justify-center">
                                <img
                                    v-if="product.hasImage"
                                    :src="product.image"
                                    :alt="product.name"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                />
                                <Apple v-else class="h-12 w-12 text-gray-400" />
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-sm line-clamp-2 min-h-[2.5rem]">
                                    {{ product.name || 'Nom non disponible' }}
                                </h3>
                                <p v-if="product.id" class="text-xs text-muted-foreground mt-1">
                                    ID: {{ product.id }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <Card v-else-if="!isLoading && search">
                    <CardContent class="p-8 text-center">
                        <Package class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                        <p class="text-muted-foreground">
                            Aucun produit trouvé pour "{{ search }}"
                        </p>
                        <p class="text-sm text-muted-foreground mt-2">
                            Essayez avec d'autres mots-clés
                        </p>
                    </CardContent>
                </Card>
            </div>
            <Card v-else>
                <CardContent class="p-8 text-center">
                    <Search class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                    <p class="text-muted-foreground">
                        Commencez à taper pour rechercher des produits alimentaires
                    </p>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
