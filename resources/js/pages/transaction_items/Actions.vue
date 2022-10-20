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
            ></v-select>

            <v-text-field
                label="Date"
                class="shrink mr-2"
                outlined
                dense
                hide-details
                readonly
            ></v-text-field>

            <v-select
                label="Type"
                class="shrink mr-2"
                outlined
                dense
                hide-details
                :items="['ISSUANCE', 'CONDEMN', 'RETURN', 'LOST']"
            ></v-select>
        </v-card-actions>
    </v-card>
</template>

<script>
    import { useLocationStore } from '@/stores/location'
    import { storeToRefs } from 'pinia'

    export default {
        setup() {
            const { location_select_items } = storeToRefs(useLocationStore())

            return {
                location_select_items
            }
        },
    }
</script>