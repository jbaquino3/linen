<template>
    <v-card class="mt-5" flat>
        <v-data-table :headers="headers" :items="computed_stock_rooms" :loading="stock_rooms_loading" disable-pagination hide-default-footer>
            <template v-slot:[`item.actions`]="{ item }">
                <div class="d-flex">
                    <!-- <div class="mr-1">
                        <table-edit-button @click="openEdit(item)"></table-edit-button>
                    </div> -->
                    <div>
                        <v-btn small :dark="!selected_stock_room || selected_stock_room.id != item.id" class="success" @click="selectStockRoom(item)" :disabled="selected_stock_room && selected_stock_room.id == item.id">
                            View Storage List
                        </v-btn>
                    </div>
                </div>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div class="primary--text title font-weight-bold" v-if="selected_stock_room && selected_stock_room.id == item.id">
                    {{item.name.toUpperCase()}}
                </div>

                <div :class="($vuetify.theme.dark ? 'grey--text text--lighten-5' : '')" v-else>
                    {{item.name.toUpperCase()}}
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    import { onMounted } from 'vue'
    import { storeToRefs } from 'pinia'
    import { useStockRoomStore } from '@/stores/stock_room'

    export default {
        setup() {
            const stockRoomStore = useStockRoomStore()
            const { stock_room_dialog, computed_stock_rooms, stock_rooms_loading, selected_stock_room } = storeToRefs(stockRoomStore)

            onMounted(() => {
                stockRoomStore.fetchStockRooms()
            })

            function openEdit(item) {
                selected_stock_room.value = Object.assign({}, item)
                stock_room_dialog.value = true
            }

            function selectStockRoom(item) {
                selected_stock_room.value = Object.assign({}, item)
            }

            return {
                headers,
                computed_stock_rooms,
                stock_rooms_loading,
                selected_stock_room,
                openEdit,
                selectStockRoom
            }
        },
    }

    const headers = [
        {text: "Name", value: "name"},
        {text: "Actions", value: "actions"}
    ]
</script>