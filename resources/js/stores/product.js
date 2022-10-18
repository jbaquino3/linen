import { defineStore } from 'pinia'
import { ref, computed, onMounted, reactive } from 'vue'
import * as productApi from '@/api/product'
import { applyFilter, getOptions } from '@/plugins/filter'

export const useProductStore = defineStore('product', () => {
    const init = ref(false)
    const products = ref([])
    const products_loading = ref(false)
    const products_error = ref(null)
    const dialog_loading = ref(false)
    const dialog_error = ref(null)
    const product_dialog = ref(false)
    const selected_product = ref(null)
    const filterable = ref([
        {text: 'Available', value: 'available', type: 'boolean'},
        {text: 'Storage', value: 'storage_name', type: 'distinct'},
        {text: 'Material', value: 'material_name', type: 'distinct'},
    ])
    const filters = reactive({
        available: null,
        storage_name: null,
        material_name: null
    })

    const computed_products = computed(() => applyFilter(products.value, filterable.value, filters))

    onMounted(async () => {
        products_loading.value = false
        products_error.value = null
        dialog_loading.value = false
        dialog_error.value = null
        if(!init.value) {
            await fetchProducts()
            init.value = true
        }

        // init filter items
        filterable.value.forEach(f => {
            f.items = getOptions(products.value, f.value, f.type)
        })
    })

    async function fetchProducts() {
        products_error.value = null
        products_loading.value = true
        const res = await productApi.index()
        if(res.status) {
            products.value = loadProducts(res.data)
        } else {
            products_error.value = res.data
        }
        products_loading.value = false
    }

    function loadProducts(data) {
        let items = []
        data.forEach(item => {
            item.available = item.quantity-item.quantity_issued
            item.storage_name = item.storage.storage_name
            item.material_name = item.material.description
            items.push(item)
        })
        return items
    }

    async function updateProduct(data, bulk_id) {
        dialog_error.value = null
        dialog_loading.value = true
        const res = await productApi.update(data, bulk_id)
        if(res.status) {
            const index = products.value.findIndex(m => m.bulk_id == bulk_id)
            products.value[index] = res.data
            product_dialog.value = false
            selected_product.value = null
            products.value = [...products.value]
        } else {
            dialog_error.value = res.data
        }
        dialog_loading.value = false
    }

    async function createProduct(data) {
        dialog_error.value = null
        dialog_loading.value = true
        const res = await productApi.store(data)
        if(res.status) {
            products.value.unshift(res.data)
            product_dialog.value = false
            selected_product.value = null
        } else {
            dialog_error.value = res.data
        }
        dialog_loading.value = false
    }

    async function deleteProduct(bulk_id) {
        products_error.value = null
        products_loading.value = true
        const res = await productApi.destroy(bulk_id)
        if(res.status) {
            const index = products.value.findIndex(m => m.bulk_id == bulk_id)
            products.value.splice(index, 1)
        } else {
            products_error.value = res.data
        }
        products_loading.value = false
    }
  
    return {
        computed_products,
        products_loading,
        products_error,
        dialog_loading,
        dialog_error,
        product_dialog,
        selected_product,
        filterable,
        filters,
        fetchProducts,
        updateProduct,
        createProduct,
        deleteProduct
    }
})