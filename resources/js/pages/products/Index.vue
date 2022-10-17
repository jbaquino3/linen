<template>
    <div>
        <v-btn color="primary" @click="product_dialog=true">Add Product</v-btn>
        <product-list></product-list>
        <product-dialog></product-dialog>
    </div>
</template>

<script>
    import { useProductStore } from '@/stores/product'
    import { storeToRefs } from 'pinia'

    export default {
        setup() {
            const productStore = useProductStore()
            const { product_dialog } = storeToRefs(productStore)

            return {
                product_dialog
            }
        },

        components: {
            ProductList: () => import('./ProductList'),
            ProductDialog: () => import('./ProductDialog')
        }
    }
</script>