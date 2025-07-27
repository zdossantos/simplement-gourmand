<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
	unit?: string;
	placeholder?: string;
	disabled?: boolean;
}>();

const quantity = defineModel<number>();

const displayValue = computed({
	get: () => {
		if (!quantity.value) return '';
		return quantity.value.toString().replace('.', ',');
	},
	set: (value: string) => {
		if (!value) {
			quantity.value = undefined;
			return;
		}
		
		// Convertir virgule en point et limiter à 2 décimales
		let numStr = value.replace(',', '.');
		const parts = numStr.split('.');
		if (parts[1] && parts[1].length > 2) {
			numStr = parts[0] + '.' + parts[1].slice(0, 2);
		}
		
		const num = parseFloat(numStr);
		quantity.value = isNaN(num) ? undefined : num;
	}
});

const handleInput = (event: Event) => {
	const target = event.target as HTMLInputElement;
	let value = target.value.replace('.', ',');
	
	// Bloquer si format invalide (max 2 décimales)
	if (!/^\d*,?\d{0,2}$/.test(value)) {
		target.value = displayValue.value;
		return;
	}
	
	target.value = value;
	displayValue.value = value;
};
</script>

<template>
	<div class="flex items-stretch border border-input rounded-md bg-background overflow-hidden shadow-xs focus-within:ring-1 focus-within:ring-ring">
		<input 
			v-model="displayValue"
			:placeholder="placeholder"
			:disabled="disabled"
			class="flex-1 px-3 py-2 text-sm bg-transparent border-0 outline-none ring-0 focus:ring-0 disabled:cursor-not-allowed disabled:opacity-50 min-w-0"
			type="text"
			inputmode="decimal"
			@input="handleInput"
		/>
		<div 
			v-if="props.unit" 
			class="flex items-center px-2 py-2 bg-muted text-muted-foreground text-sm font-medium border-l border-input"
		>
			{{ props.unit }}
		</div>
	</div>
</template>