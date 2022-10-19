<template>
    <v-card flat v-if="selected_stock_room" :loading="loading" :disabled="loading">
        <v-card-title>Add Storage to {{selected_stock_room.name}}</v-card-title>

        <v-card-text>
            <v-text-field
                label="Storage Name"
                outlined
                dense
                v-model="name"
            ></v-text-field>

            <v-btn @click="save" color="primary" block>Add Storage</v-btn>
        </v-card-text>
    </v-card>
</template>

<script>
    import { ref } from 'vue'
    import { storeToRefs } from 'pinia'
    import { useStockRoomStore } from '@/stores/stock_room'
    import { useStorageStore } from '@/stores/storage'

    export default {
        setup() {
            const name = ref("")
            const loading = ref(false)
            const storageStore = useStorageStore()
            const { selected_stock_room } = storeToRefs(useStockRoomStore())

            async function save() {
                loading.value = true
                await storageStore.createStorage({
                    name: name.value,
                    stock_room_id: selected_stock_room.value.id
                })
                loading.value = false
            }

            return {
                name, save, loading, selected_stock_room
            }
        },
    }
</script>