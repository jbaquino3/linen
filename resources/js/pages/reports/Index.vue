<template>
    <div>
        <report-list></report-list>
        <report-dialog></report-dialog>
    </div>
</template>

<script>
    export default {
        setup() {

            return {
                
            }
        },

        components: {
            ReportList: () => import('./ReportList'),
            ReportDialog: () => import('./ReportDialog')
        }
    }
</script>