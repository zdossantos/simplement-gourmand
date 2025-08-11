<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import InputUnit from '@/components/InputUnit.vue';
import { Apple, Plus, Save, Trash, Check } from 'lucide-vue-next';
import { ref } from 'vue';
import Badge from '../ui/badge/Badge.vue';
import { useFridge } from '@/composables/queries/useFridge';
import type { Food } from '@/types/food';

const props = defineProps<{
	food: Food;
}>();

const quantity = ref(props.food.quantity || 0);

const { addFood, updateFood, deleteFood, isLoading } = useFridge();

</script>
<template>
	<TooltipProvider>
		<Card 
			:key="food.openfoodfacts_id || food.name" 
			class="group overflow-hidden relative transition-all duration-300"
		>
			<div v-if="food.fridge_id" class="absolute top-3 right-3 z-10">
				<Badge variant="default">
					<Check class="h-3 w-3" />
					Dans le frigo
				</Badge>
			</div>

			<CardContent class="p-0 flex flex-col h-full">
				<div class="relative w-full h-40 flex items-center justify-center overflow-hidden">
					<img
						v-if="food.has_image"
						:src="food.image!"
						:alt="food.name"
						loading="lazy"
						class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
					/>
					<div v-else class="flex flex-col items-center gap-2">
						<Apple class="h-12 w-12" />
						<span class="text-xs">Pas d'image</span>
					</div>
				</div>

				<div class="p-4 flex flex-col gap-4 flex-1">
					<h3 class="font-semibold text-sm line-clamp-2 min-h-[2.5rem] leading-tight">
						{{ food.name || 'Nom non disponible' }}
					</h3>

					<div class="flex justify-center">
						<InputUnit 
							v-model="quantity" 
							:unit="food.unit || undefined"
							class="w-full max-w-32"
						/>
					</div>

					<div class="flex flex-col gap-2 mt-auto">
						<div v-if="food.fridge_id" class="flex items-center justify-between gap-2">
							<Tooltip>
								<TooltipTrigger asChild>
									<Button 
										@click="deleteFood(food.fridge_id)" 
										variant="outline" 
										size="sm"
										:disabled="isLoading"
										class="flex-1 border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300"
									>
										<Trash class="h-4 w-4" />
									</Button>
								</TooltipTrigger>
								<TooltipContent>
									<p>Retirer du frigo</p>
								</TooltipContent>
							</Tooltip>

							<Tooltip>
								<TooltipTrigger asChild>
									<Button 
										@click="updateFood({id: food.fridge_id, quantity: quantity})" 
										size="sm"
										:disabled="isLoading"
										class="flex-1"
										variant="outline"
									>
										<Save class="h-4 w-4" />
									</Button>
								</TooltipTrigger>
								<TooltipContent>
									<p>Mettre à jour la quantité</p>
								</TooltipContent>
							</Tooltip>
						</div>
						<Button 
							@click="addFood({
								openfoodfacts_id: food.openfoodfacts_id,
								name: food.name,
								image: food.image || undefined,
								unit: food.unit || undefined,
								quantity: quantity,
							})" 
							v-else
							:disabled="isLoading"
							variant="outline"
						>
							<Plus class="h-4 w-4 mr-2" />
							{{ isLoading ? 'Ajout...' : 'Ajouter au frigo' }}
						</Button>
					</div>
				</div>
			</CardContent>
		</Card>
	</TooltipProvider>
</template>