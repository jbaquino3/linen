import { defineStore } from 'pinia'
import { ref, computed, onMounted } from 'vue'
import * as storageApi from '@/api/storage'

export const useStorageStore = defineStore('storage', () => {
    const storages = ref([])
    const storages_loading = ref(false)
    const storages_error = ref(null)
    const storage_dialog = ref(false)
    const selected_storage = ref(null)
    const computed_storages = computed(() => storages.value)

    onMounted(() => {
        storages_loading.value = false
        storages_error.value = null
    })

    const storage_select_items = computed(() => {
        let items = []
        storages.value.forEach(stg => {
            items.push({
                text: stg.stock_room_name + " - " + stg.name,
                value: stg.id
            })
        })
        return items
    })

    async function fetchStorages() {
        storages_error.value = null
        storages_loading.value = true
        const res = await storageApi.index()
        if(res.status) {
            storages.value = res.data
        } else {
            storages_error.value = res.data
        }
        storages_loading.value = false
    }
  
    return {
        computed_storages,
        storages_loading,
        storages_error,
        storage_dialog,
        selected_storage,
        storage_select_items,
        fetchStorages
    }
}, { persist: true })