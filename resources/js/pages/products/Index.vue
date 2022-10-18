<template>
    <div>
        <product-list></product-list>
        <product-dialog></product-dialog>
    </div>
</template>

<script>
    export default {
        setup() {
            return {
                
            }
        },

        components: {
            ProductList: () => import('./ProductList'),
            ProductDialog: () => import('./ProductDialog')
        }
    }
</script>