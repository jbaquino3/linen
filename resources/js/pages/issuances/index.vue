<template>
    <div>
        <transaction-list></transaction-list>
    </div>
</template>

<script>
    export default {
        setup() {

            return {
                
            }
        },

        components: {
            TransactionList: () => import('./TransactionList')
        }
    }
</script>