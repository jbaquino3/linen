<template>
    <div>
        <v-btn color="primary" @click="material_dialog=true">Add Material</v-btn>
        <material-list></material-list>
        <material-dialog></material-dialog>
    </div>
</template>

<script>
    import { useMaterialStore } from '@/stores/material'
    import { storeToRefs } from 'pinia'

    export default {
        setup() {
            const materialStore = useMaterialStore()
            const { material_dialog } = storeToRefs(materialStore)

            return {
                material_dialog
            }
        },

        components: {
            MaterialList: () => import('./MaterialList'),
            MaterialDialog: () => import('./MaterialDialog')
        }
    }
</script>