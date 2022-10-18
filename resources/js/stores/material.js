import { defineStore } from 'pinia'
import { computed, reactive, toRefs, watchEffect } from 'vue'
import * as materialApi from '@/api/material'
import { useProductStore } from '@/stores/product'
import { updateArrayByProperty } from '@/plugins/helpers'
import useFilters from '../plugins/filter'

export const useMaterialStore = defineStore('material', () => {
    const productStore = useProductStore()
    const materials = reactive(materialsObject)
    const dialog = reactive(dialogObject)
    const filter = getFilterObject()
    const computed_materials = computed(() => materialFilters.applyFilter(materials.data, filter.filterable, filter.filters))

    watchEffect(() => { filter.updateFilters(materials.data) })

    const material_select_items = computed(() => {
        if(materials.data.length == 0) {
            fetchMaterials()
        }

        let items = []
        let filtered = materials.data.filter(mat => {
            return (productStore.selected_product && mat.stock_number == productStore.selected_product.material_stock_number) ||
            (mat.quantity_used < mat.quantity && !mat.archived_at)
        })

        filtered.forEach(stg => {
            items.push(Object.assign({}, {
                text: "#" + stg.stock_number + " " + stg.description,
                value: stg.stock_number,
                unit: stg.unit,
                available: stg.quantity - stg.quantity_used + parseFloat(
                    productStore.selected_product && stg.stock_number == productStore.selected_product.material_stock_number ?
                    productStore.selected_product.material_quantity : 0
                )
            }))
        })
        return items
    })

    async function fetchMaterials() {
        materials.init()
        const res = await materialApi.index()
        res.status ? materials.success(loadMaterials(res.data)) : materials.error(res.data)
    }

    async function updateMaterial(data, stock_number) {
        dialog.init()
        const res = await materialApi.update(data, stock_number)
        if(res.status) {
            materials.update(stock_number, loadMaterials([res.data])[0])
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function createMaterial(data) {
        dialog.init()
        const res = await materialApi.store(data)
        if(res.status) {
            materials.insert(loadMaterials([res.data])[0])
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function deleteMaterial(stock_number) {
        materials.init()
        const res = await materialApi.destroy(stock_number)
        if(res.status) {
            materials.delete(stock_number)
        } else {
            materials.error(res.data)
        }
    }
  
    return {
        ...toRefs(materials),
        ...toRefs(dialog),
        ...toRefs(filter),
        computed_materials,
        material_select_items,
        fetchMaterials,
        updateMaterial,
        createMaterial,
        deleteMaterial
    }
})

const materialFilters = useFilters()

const materialsObject = {
    data: [],
    materials_loading: false,
    materials_error: null,
    selected_material: null,
    init: function () {
        this.selected_material = null
        this.materials_loading = true
        this.materials_error = null
    },
    error: function(err) {
        this.materials_loading = false
        this.materials_error = err
    },
    success: function(data) {
        this.materials_loading = false
        this.data = data
    },
    update: function(stock_number, data) {
        this.selected_material = null
        this.success([...updateArrayByProperty(this.data, 'stock_number', stock_number, data)])
    },
    insert: function(data) {
        this.data.unshift(data)
        this.selected_material = null
    },
    delete: function(stock_number) {
        const index = this.data.findIndex(m => m.stock_number == stock_number)
        this.data.splice(index, 1)
        this.materials_loading = false
    }
}

const dialogObject = {
    material_dialog: false,
    dialog_loading: false,
    dialog_error: null,
    init: function () {
        this.dialog_loading = true
        this.dialog_error = null
    },
    error: function(err) {
        this.dialog_loading = false
        this.dialog_error = err
    },
    success: function() {
        this.dialog_loading = false
        this.material_dialog = false
    }
}

function getFilterObject() {
    const ownFilterObject = Object.assign({}, materialFilters.filtersObject)
    ownFilterObject.addFilterable({text: 'Available', value: 'available', type: 'boolean'})
    ownFilterObject.addFilterable({text: 'Storage', value: 'storage_name', type: 'distinct'})
    return reactive(Object.assign({}, ownFilterObject))
}

function loadMaterials(data) {
    let items = []
    data.forEach(item => {
        item.available = item.archived_at ? 0 : item.quantity-item.quantity_used
        item.storage_name = item.storage.storage_name
        items.push(item)
    })
    return items
}