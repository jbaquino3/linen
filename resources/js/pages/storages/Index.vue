<template>
    <div>
        <v-alert v-if="stock_rooms_error" type="error" text>
            {{stock_rooms_error}}
        </v-alert>
        <v-alert v-if="storages_error" type="error" text>
            {{storages_error}}
        </v-alert>

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
    </div>
</template>

<script>
    import { useStockRoomStore } from '@/stores/stock_room'
    import { useStorageStore } from '@/stores/storage'
    import { storeToRefs } from 'pinia'

    export default {
        setup() {
            const { stock_rooms_error } = storeToRefs(useStockRoomStore())
            const { storages_error } = storeToRefs(useStorageStore())

            return {
                stock_rooms_error, storages_error
            }
        },

        components: {
            StockRoom: () => import('./StockRoom.vue'),
            CreateStockRoom: () => import('./CreateStockRoom.vue'),
            Storage: () => import('./Storage.vue'),
            CreateStorage: () => import('./CreateStorage.vue'),
        }
    }
</script>