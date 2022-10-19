<template>
    <v-row>
        <v-col cols="6" class="d-flex flex-column">
            <create-stock-room></create-stock-room>
            <stock-room></stock-room>
        </v-col>
        <v-col cols="6" class="d-flex flex-column">
            <create-storage></create-storage>
            <storage></storage>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        setup() {
            
        },

        components: {
            StockRoom: () => import('./StockRoom.vue'),
            CreateStockRoom: () => import('./CreateStockRoom.vue'),
            Storage: () => import('./Storage.vue'),
            CreateStorage: () => import('./CreateStorage.vue'),
        }
    }
</script>