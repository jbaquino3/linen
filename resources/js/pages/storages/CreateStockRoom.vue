<template>
    <v-card flat :loading="loading" :disabled="loading">
        <v-card-title>Add Stock Room</v-card-title>

        <v-card-text>
            <v-text-field
                label="Stock Room Name"
                outlined
                dense
                v-model="name"
            ></v-text-field>

            <v-btn @click="save" color="primary" block>Add Stock Room</v-btn>
        </v-card-text>
    </v-card>
</template>

<script>
    import { ref } from 'vue'
    import { useStockRoomStore } from '@/stores/stock_room'

    export default {
        setup() {
            const name = ref("")
            const loading = ref(false)
            const stockRoomStore = useStockRoomStore()

            async function save() {
                loading.value = true
                await stockRoomStore.createStockRoom({name: name.value})
                loading.value = false
            }

            return {
                name, save, loading
            }
        },
    }
</script>