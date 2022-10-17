import { defineStore } from 'pinia'
import { ref, computed, onMounted } from 'vue'
import * as locationApi from '@/api/location'

export const useLocationStore = defineStore('location', () => {
    const init = ref(false)
    const locations = ref([])
    const locations_loading = ref(false)
    const locations_error = ref(null)
    const dialog_loading = ref(false)
    const dialog_error = ref(null)
    const location_dialog = ref(false)
    const selected_location = ref(null)

    const computed_locations = computed(() => locations.value)

    onMounted(() => {
        locations_loading.value = false
        locations_error.value = null
        dialog_loading.value = false
        dialog_error.value = null
        if(!init.value) {
            fetchLocations()
            init.value = true
        }
    })

    async function fetchLocations() {
        locations_error.value = null
        locations_loading.value = true
        const res = await locationApi.index()
        if(res.status) {
            locations.value = res.data
        } else {
            locations_error.value = res.data
        }
        locations_loading.value = false
    }

    async function updateLocation(data, id) {
        dialog_error.value = null
        dialog_loading.value = true
        const res = await locationApi.update(data, id)
        if(res.status) {
            const index = locations.value.findIndex(m => m.id == id)
            locations.value[index] = data
            location_dialog.value = false
            selected_location.value = null
            locations.value = [...locations.value]
        } else {
            dialog_error.value = res.data
        }
        dialog_loading.value = false
    }

    async function createLocation(data) {
        dialog_error.value = null
        dialog_loading.value = true
        const res = await locationApi.store(data)
        if(res.status) {
            locations.value.unshift(res.data)
            location_dialog.value = false
            selected_location.value = null
        } else {
            dialog_error.value = res.data
        }
        dialog_loading.value = false
    }

    async function deleteLocation(id) {
        locations_error.value = null
        locations_loading.value = true
        const res = await locationApi.destroy(id)
        if(res.status) {
            const index = locations.value.findIndex(m => m.id == id)
            locations.value.splice(index, 1)
        } else {
            locations_error.value = res.data
        }
        locations_loading.value = false
    }
  
    return {
        computed_locations,
        locations_loading,
        locations_error,
        dialog_loading,
        dialog_error,
        location_dialog,
        selected_location,
        fetchLocations,
        updateLocation,
        createLocation,
        deleteLocation
    }
}, { persist: true })