<template>
    <div>
        <v-btn color="primary" @click="location_dialog=true">Add Location</v-btn>
        <location-list></location-list>
        <location-dialog></location-dialog>
    </div>
</template>

<script>
    import { useLocationStore } from '@/stores/location'
    import { storeToRefs } from 'pinia'

    export default {
        setup() {
            const locationStore = useLocationStore()
            const { location_dialog } = storeToRefs(locationStore)

            return {
                location_dialog
            }
        },

        components: {
            LocationList: () => import('./LocationList'),
            LocationDialog: () => import('./LocationDialog')
        }
    }
</script>