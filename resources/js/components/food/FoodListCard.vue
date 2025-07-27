<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import InputUnit from '@/components/InputUnit.vue';
import { Apple, Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export interface FoodProduct {
  id: string | null
  name: string
  image?: string
  hasImage?: boolean
  unit?: string
  quantity?: number
}

const props = defineProps<{
	product: FoodProduct;
}>();

const quantity = ref(props.product.quantity || 0);

const addFood = () => {
	router.post(route('fridge.addFood'), {
		openfoodfacts_id: props.product.id,
		name: props.product.name,
		image_url: props.product.image,
		unit: props.product.unit,
		quantity: quantity.value,
	});
};

</script>
<template>
	<Card :key="product.id || product.name" class="overflow-hidden hover:shadow-md transition-shadow">
		<CardContent class="p-0 flex flex-col">
			<div class="w-full h-40 flex items-center justify-center overflow-hidden">
				<img
					v-if="product.hasImage"
					:src="product.image"
					:alt="product.name"
					loading="lazy"
					class="w-full h-full object-contain"
				/>
				<Apple v-else class="h-12 w-12 text-gray-400" />
			</div>
			<div class="p-4 flex flex-col gap-3 flex-1">
				<h3 class="font-medium text-sm line-clamp-2 min-h-[2.5rem]">
					{{ product.name || 'Nom non disponible' }}
				</h3>
				<div class="flex flex-col items-center gap-2">
					<InputUnit v-model="quantity" :unit="product.unit" />
					<Button @click="addFood">
						<Plus class="h-4 w-4" />
						Ajouter au réfrigérateur
					</Button>
				</div>
			</div>
		</CardContent>
	</Card>
</template>