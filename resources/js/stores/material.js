import { defineStore } from 'pinia'
import { ref, computed, onMounted } from 'vue'
import * as materialApi from '@/api/material'

export const useMaterialStore = defineStore('material', () => {
    const materials = ref([])
    const materials_loading = ref(false)
    const materials_error = ref(null)
    const dialog_loading = ref(false)
    const dialog_error = ref(null)
    const material_dialog = ref(false)
    const selected_material = ref(null)

    const computed_materials = computed(() => materials.value)

    onMounted(() => {
        materials_loading.value = false
        materials_error.value = null
        dialog_loading.value = false
        dialog_error.value = null
    })

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

    async function updateMaterial(data, stock_number) {
        dialog_error.value = null
        dialog_loading.value = true
        const res = await materialApi.update(data, stock_number)
        if(res.status) {
            const index = materials.value.findIndex(m => m.stock_number == stock_number)
            materials.value[index] = data
            material_dialog.value = false
            selected_material.value = null
            materials.value = [...materials.value]
        } else {
            dialog_error.value = res.data
        }
        dialog_loading.value = false
    }

    async function createMaterial(data) {
        dialog_error.value = null
        dialog_loading.value = true
        const res = await materialApi.store(data)
        if(res.status) {
            materials.value.unshift(res.data)
            material_dialog.value = false
            selected_material.value = null
        } else {
            dialog_error.value = res.data
        }
        dialog_loading.value = false
    }

    async function deleteMaterial(stock_number) {
        materials_error.value = null
        materials_loading.value = true
        const res = await materialApi.destroy(stock_number)
        if(res.status) {
            const index = materials.value.findIndex(m => m.stock_number == stock_number)
            materials.value.splice(index, 1)
        } else {
            materials_error.value = res.data
        }
        materials_loading.value = false
    }
  
    return {
        computed_materials,
        materials_loading,
        materials_error,
        dialog_loading,
        dialog_error,
        material_dialog,
        selected_material,
        fetchMaterials,
        updateMaterial,
        createMaterial,
        deleteMaterial
    }
}, { persist: true })