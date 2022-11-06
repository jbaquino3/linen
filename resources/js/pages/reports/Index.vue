<template>
    <div>
        <report-list></report-list>
    </div>
</template>

<script>
    export default {
        setup() {

            return {
                
            }
        },

        components: {
            ReportList: () => import('./ReportList')
        }
    }
</script>