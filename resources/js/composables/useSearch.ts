import { ref, watch, onUnmounted } from 'vue'

interface SearchOptions {
  debounceMs?: number
  onSearch?: (query: string) => void
  onClear?: () => void
}

export function useSearch(options: SearchOptions = {}) {
  const {
    debounceMs = 500,
    onSearch,
    onClear
  } = options

  const searchQuery = ref('')
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  let debounceTimeout: ReturnType<typeof setTimeout> | null = null

  const search = (query: string): void => {
    if (!query.trim()) {
      clearSearch()
      return
    }

    if (onSearch) {
      onSearch(query)
    }
  }

  const debouncedSearch = (query: string): void => {
    if (debounceTimeout) {
      clearTimeout(debounceTimeout)
    }

    debounceTimeout = setTimeout(() => {
      search(query)
    }, debounceMs)
  }

  const clearSearch = (): void => {
    searchQuery.value = ''
    error.value = null
    
    if (debounceTimeout) {
      clearTimeout(debounceTimeout)
      debounceTimeout = null
    }
    
    if (onClear) {
      onClear()
    }
  }

  const reset = (): void => {
    searchQuery.value = ''
    isLoading.value = false
    error.value = null
    
    if (debounceTimeout) {
      clearTimeout(debounceTimeout)
      debounceTimeout = null
    }
  }

  const setLoading = (loading: boolean): void => {
    isLoading.value = loading
  }

  const setError = (errorMessage: string | null): void => {
    error.value = errorMessage
  }

  watch(searchQuery, (newQuery) => {
    debouncedSearch(newQuery)
  })

  onUnmounted(() => {
    if (debounceTimeout) {
      clearTimeout(debounceTimeout)
    }
  })

  return {
    searchQuery,
    isLoading,
    error,
    search,
    clearSearch,
    reset,
    setLoading,
    setError
  }
} 