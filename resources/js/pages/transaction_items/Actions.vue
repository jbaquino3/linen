<template>
    <v-card flat>
        <v-card-actions>
            <v-select
                label="Ward/Office"
                class="shrink mr-2"
                outlined
                dense
                hide-details
                :items="location_select_items"
                v-model="selected_transaction.location_id"
            ></v-select>

            <v-select
                label="Type"
                class="shrink mr-2"
                outlined
                dense
                hide-details
                :items="['ISSUANCE', 'CONDEMN', 'RETURN', 'LOST']"
                v-model="selected_transaction.type"
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