<template>
    <v-card flat class="no-print">
        <v-card-actions>
            <v-select
                label="Ward/Office"
                class="shrink mr-2"
                outlined
                dense
                hide-details
                :items="location_select_items"
                v-model="selected_transaction.location_id"
                :disabled="!!selected_transaction.id"
            ></v-select>
        </v-card-actions>
    </v-card>
</template>

<script>
    import { useLocationStore } from '@/stores/location'
    import { useTransactionStore } from '@/stores/transaction'
    import { storeToRefs } from 'pinia'

    export default {
        setup() {
            const { location_select_items } = storeToRefs(useLocationStore())
            const { selected_transaction } = storeToRefs(useTransactionStore())

            return {
                location_select_items,
                selected_transaction
            }
        },
    }
</script>