<template>
    <div>
        <Location></Location>
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