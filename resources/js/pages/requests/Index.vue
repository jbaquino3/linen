<template>
    <div>
        <request-list></request-list>
        <request-dialog></request-dialog>
    </div>
</template>

<script>
    export default {
        setup() {

            return {
                
            }
        },

        components: {
            RequestList: () => import('./RequestList'),
            RequestDialog: () => import('./RequestDialog')
        }
    }
</script>