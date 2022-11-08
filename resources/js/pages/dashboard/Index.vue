<template>
    <div>
        <Location></Location>
        <request-summary class="mt-2"></request-summary>
        <issued-products class="mt-2"></issued-products>
    </div>
</template>

<script>
    import Location from './Location'
    import RequestSummary from '@/pages/requests/RequestSummary'
    import IssuedProducts from './IssuedProducts'

    export default {
        setup() {
            
        },

        components: {
            Location,
            RequestSummary,
            IssuedProducts
        }
    }
</script>