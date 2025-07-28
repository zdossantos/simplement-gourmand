export interface Food {
    openfoodfacts_id: string
    name: string
    code?: string | null
    brands?: string | null
    nutriscore_grade?: string | null
    image?: string | null
    hasImage: boolean
    quantity?: number | null
    unit?: string | null
    fridge_id?: number | null
    user_id?: number | null
}

export interface AddFoodParams {
    openfoodfacts_id: string
    name: string
    image?: string | null
    quantity?: number
    unit?: string
}

export interface UpdateFoodParams {
    id: number
    quantity: number
    unit?: string
} 