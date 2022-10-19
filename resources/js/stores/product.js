import { defineStore } from 'pinia'
import { computed, reactive, watchEffect, toRefs } from 'vue'
import * as productApi from '@/api/product'
import { updateArrayByProperty } from '@/plugins/helpers'
import useFilters from '../plugins/filter'

const productFilters = useFilters()

export const useProductStore = defineStore('product', () => {
    const products = reactive(productsObject)
    const dialog = reactive(dialogObject)
    const filter = getFilterObject()
    const computed_products = computed(() => productFilters.applyFilter(products.data, filter.filterable, filter.filters))

    watchEffect(() => { filter.updateFilters(products.data) })

    async function fetchProducts() {
        products.init()
        const res = await productApi.index()
        res.status ? products.success(loadProducts(res.data)) : products.error(res.data)
    }

    async function updateProduct(data, bulk_id) {
        dialog.init()
        const res = await productApi.update(data, bulk_id)
        if(res.status) {
            products.update(bulk_id, loadProducts([res.data])[0])
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function createProduct(data) {
        dialog.init()
        const res = await productApi.store(data)
        if(res.status) {
            products.insert(loadProducts([res.data])[0])
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function deleteProduct(bulk_id) {
        products.init()
        const res = await productApi.destroy(bulk_id)
        if(res.status) {
            products.delete(bulk_id)
        } else {
            products.error(res.data)
        }
    }
  
    return {
        ...toRefs(products),
        ...toRefs(dialog),
        ...toRefs(filter),
        computed_products,
        fetchProducts,
        updateProduct,
        createProduct,
        deleteProduct
    }
})

const productsObject = {
    data: [],
    products_loading: false,
    products_error: null,
    selected_product: null,
    init: function () {
        this.selected_product = null
        this.products_loading = true
        this.products_error = null
    },
    error: function(err) {
        this.products_loading = false
        this.products_error = err
    },
    success: function(data) {
        this.products_loading = false
        this.data = data
    },
    update: function(bulk_id, data) {
        this.selected_product = null
        this.success([...updateArrayByProperty(this.data, 'bulk_id', bulk_id, data)])
    },
    insert: function(data) {
        this.data.unshift(data)
        this.selected_product = null
    },
    delete: function(bulk_id) {
        const index = this.data.findIndex(m => m.bulk_id == bulk_id)
        this.data.splice(index, 1)
        this.products_loading = false
    }
}

const dialogObject = {
    product_dialog: false,
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
        this.product_dialog = false
    }
}

function getFilterObject() {
    const ownFilterObject = Object.assign({}, productFilters.filtersObject)
    ownFilterObject.addFilterable({text: 'Available', value: 'available', type: 'boolean'})
    ownFilterObject.addFilterable({text: 'Storage', value: 'storage_name', type: 'distinct'})
    ownFilterObject.addFilterable({text: 'Material', value: 'material_name', type: 'distinct'})
    return reactive(Object.assign({}, ownFilterObject))
}

function loadProducts(data) {
    let items = []
    data.forEach(item => {
        item.available = item.quantity-item.quantity_issued+item.quantity_returned
        item.quantity_issued = item.quantity_issued-item.quantity_condemned-item.quantity_lost
        item.storage_name = item.storage.storage_name
        item.material_name = item.material.description
        item.material_unit = item.material.unit ? item.material.unit : ""
        item.material_unit_cost = item.material.unit_cost
        items.push(item)
    })
    return items
}