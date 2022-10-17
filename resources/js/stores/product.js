import { defineStore } from 'pinia'
import { ref, computed, onMounted } from 'vue'
import * as productApi from '@/api/product'

export const useProductStore = defineStore('product', () => {
    const init = ref(false)
    const products = ref([])
    const products_loading = ref(false)
    const products_error = ref(null)
    const dialog_loading = ref(false)
    const dialog_error = ref(null)
    const product_dialog = ref(false)
    const selected_product = ref(null)

    const computed_products = computed(() => products.value)

    onMounted(() => {
        products_loading.value = false
        products_error.value = null
        dialog_loading.value = false
        dialog_error.value = null
        if(!init.value) {
            fetchProducts()
            init.value = true
        }
    })

    async function fetchProducts() {
        products_error.value = null
        products_loading.value = true
        const res = await productApi.index()
        if(res.status) {
            products.value = res.data
        } else {
            products_error.value = res.data
        }
        products_loading.value = false
    }

    async function updateProduct(data, bulk_id) {
        dialog_error.value = null
        dialog_loading.value = true
        const res = await productApi.update(data, bulk_id)
        if(res.status) {
            const index = products.value.findIndex(m => m.bulk_id == bulk_id)
            products.value[index] = data
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
        fetchProducts,
        updateProduct,
        createProduct,
        deleteProduct
    }
}, { persist: true })