<template>
    <v-card class="mt-5" flat v-if="selected_stock_room">
        <v-data-table :headers="headers" :items="computed_storages" :loading="storages_loading" disable-pagination hide-default-footer>
            <template v-slot:[`item.name`]="{ item }">
                <div :class="($vuetify.theme.dark ? 'grey--text text--lighten-5' : '')">
                    {{item.name.toUpperCase()}}
                </div>
            </template>

            <template v-slot:[`item.actions`]="{  }">
                <div class="d-flex">
                    <!-- <div class="mr-1">
                        <table-edit-button @click="openEdit(item)"></table-edit-button>
                    </div> -->
                </div>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
    import { onMounted } from 'vue'
    import { storeToRefs } from 'pinia'
    import { useStorageStore } from '@/stores/storage'
    import { useStockRoomStore } from '@/stores/stock_room'

    export default {
        setup() {
            const storageStore = useStorageStore()
            const { storage_dialog, computed_storages, storages_loading, selected_storage } = storeToRefs(storageStore)
            const { selected_stock_room } = storeToRefs(useStockRoomStore())

            onMounted(() => {
                storageStore.fetchStorages()
            })

            function openEdit(item) {
                selected_storage.value = Object.assign({}, item)
                storage_dialog.value = true
            }

            return {
                headers,
                computed_storages,
                storages_loading,
                selected_storage,
                selected_stock_room,
                openEdit
            }
        },
    }

    const headers = [
        {text: "Name", value: "name"},
        {text: "Actions", value: "actions"}
    ]
</script>