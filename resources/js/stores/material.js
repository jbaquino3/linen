import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import * as materialApi from '@/api/material'

export const useMaterialStore = defineStore('material', () => {
    const materials = ref([])
    const materials_loading = ref(false)
    const materials_error = ref(null)
    const material_dialog = ref(false)
    const selected_material = ref(null)

    const computed_materials = computed(() => materials.value)

    async function fetchMaterials() {
        materials_error.value = null
        materials_loading.value = true
        const res = await materialApi.index()
        if(res.status) {
            materials.value = res.data
        } else {
            materials_error.value = res.data
        }
        materials_loading.value = false
    }
  
    return {
        computed_materials,
        materials_loading,
        materials_error,
        material_dialog,
        selected_material,
        fetchMaterials
    }
})