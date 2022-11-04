<template>
    <div>
        <request-list></request-list>
        <request-dialog></request-dialog>
        <remark-dialog></remark-dialog>
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
            RequestDialog: () => import('./RequestDialog'),
            RemarkDialog: () => import('./RemarkDialog')
        }
    }
</script>