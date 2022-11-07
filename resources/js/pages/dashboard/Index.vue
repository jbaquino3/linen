<template>
    <div>
        <Location></Location>
        <request-summary class="mt-2"></request-summary>
    </div>
</template>

<script>
    import Location from './Location'
    import RequestSummary from '@/pages/requests/RequestSummary'

    export default {
        setup() {
            
        },

        components: {
            Location,
            RequestSummary
        }
    }
</script>