<template>
    <div>
        <material-list></material-list>
        <material-dialog></material-dialog>
    </div>
</template>

<script>
    export default {
        setup() {
            return {
                
            }
        },

        components: {
            MaterialList: () => import('./MaterialList'),
            MaterialDialog: () => import('./MaterialDialog')
        }
    }
</script>