import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import type { AddFoodParams, UpdateFoodParams } from "@/types/food";

export const useFridge = () => {
    const isLoading = ref(false);
    const errors = ref<Record<string, string>>({});

    const addFood = async (data: AddFoodParams): Promise<boolean> => {
        if (isLoading.value) return false;
        
        isLoading.value = true;
        errors.value = {};

        return new Promise((resolve) => {
            router.post(route('fridge.addFood'), {
                ...data,
                quantity: data.quantity || 1,
                unit: data.unit || 'pièce',
            }, {
                onSuccess: () => resolve(true),
                onError: (errorBag) => {
                    errors.value = errorBag;
                    resolve(false);
                },
                onFinish: () => {
                    isLoading.value = false;
                }
            });
        });
    };

    const updateFood = async ({ id, quantity, unit }: UpdateFoodParams): Promise<boolean> => {
        if (isLoading.value) return false;
        
        isLoading.value = true;
        errors.value = {};

        return new Promise((resolve) => {
            router.put(route('fridge.updateFood', { id }), {
                quantity: Math.max(0, quantity),
                ...(unit && { unit }),
            }, {
                onSuccess: () => resolve(true),
                onError: (errorBag) => {
                    errors.value = errorBag;
                    resolve(false);
                },
                onFinish: () => {
                    isLoading.value = false;
                }
            });
        });
    };

    const deleteFood = async (id: number): Promise<boolean> => {
        if (isLoading.value) return false;
        
        isLoading.value = true;
        errors.value = {};

        return new Promise((resolve) => {
            router.delete(route('fridge.deleteFood', { id }), {
                onSuccess: () => resolve(true),
                onError: (errorBag) => {
                    errors.value = errorBag;
                    resolve(false);
                },
                onFinish: () => {
                    isLoading.value = false;
                }
            });
        });
    };

    return {
        addFood,
        updateFood,
        deleteFood,
        isLoading,
        errors,
    };
};