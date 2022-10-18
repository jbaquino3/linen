<template>
    <div>
        <location-list></location-list>
        <location-dialog></location-dialog>
    </div>
</template>

<script>
    export default {
        setup() {

            return {
                
            }
        },

        components: {
            LocationList: () => import('./LocationList'),
            LocationDialog: () => import('./LocationDialog')
        }
    }
</script>