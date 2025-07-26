import { useSearch } from './useSearch'
import { router } from '@inertiajs/vue3'

interface FoodSearchOptions {
  debounceMs?: number
  preserveState?: boolean
  preserveScroll?: boolean
}

export function useFoodSearchWeb(options: FoodSearchOptions = {}) {
  const {
    debounceMs = 500,
    preserveState = true,
    preserveScroll = true
  } = options

  const searchComposable = useSearch({
    debounceMs,
    onSearch: async (query: string) => {
      try {
        searchComposable.setLoading(true)
        searchComposable.setError(null)
        
        await router.get(route('food.index'), 
          { search: query },
          {
            preserveState,
            preserveScroll,
            onError: (errors) => {
              searchComposable.setError(errors.search || 'Erreur lors de la recherche')
            }
          }
        )
      } catch (err) {
        searchComposable.setError('Une erreur inattendue est survenue')
        console.error('Search error:', err)
      } finally {
        searchComposable.setLoading(false)
      }
    },
    onClear: () => {
      router.get(route('food.index'))
    }
  })

  return {
    searchQuery: searchComposable.searchQuery,
    isLoading: searchComposable.isLoading,
    error: searchComposable.error,
    searchProducts: searchComposable.search,
    clearSearch: searchComposable.clearSearch,
    reset: searchComposable.reset
  }
} 