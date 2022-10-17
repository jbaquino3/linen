import { defineStore } from 'pinia'
import { ref, computed, onMounted } from 'vue'
import * as materialApi from '@/api/material'
import { useProductStore } from '@/stores/product'

export const useMaterialStore = defineStore('material', () => {
    const productStore = useProductStore()

    const init = ref(false)
    const materials = ref([])
    const materials_loading = ref(false)
    const materials_error = ref(null)
    const dialog_loading = ref(false)
    const dialog_error = ref(null)
    const material_dialog = ref(false)
    const selected_material = ref(null)

    const computed_materials = computed(() => materials.value)

    const material_select_items = computed(() => {
        let items = []
        let filtered = materials.value.filter(mat => {
            return (productStore.selected_product && mat.stock_number == productStore.selected_product.material_stock_number) ||
            (mat.quantity_used < mat.quantity && !mat.archived_at)
        })

        filtered.forEach(stg => {
            items.push({
                text: "#" + stg.stock_number + " " + stg.description,
                value: stg.stock_number,
                unit: stg.unit,
                available: stg.quantity - stg.quantity_used + (
                    productStore.selected_product && stg.stock_number == productStore.selected_product.material_stock_number ?
                    productStore.selected_product.material_quantity : 0
                )
            })
        })
        return items
    })

    onMounted(() => {
        materials_loading.value = false
        materials_error.value = null
        dialog_loading.value = false
        dialog_error.value = null
        if(!init.value) {
            fetchMaterials()
            init.value = true
        }
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
            materials.value[index] = res.data
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
        material_select_items,
        fetchMaterials,
        updateMaterial,
        createMaterial,
        deleteMaterial
    }
}, { persist: true })