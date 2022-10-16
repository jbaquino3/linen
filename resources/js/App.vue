<template>
    <div>{{authStore.user.name}}</div>
</template>

<script>
    import { useAuthStore } from '@/stores/auth'

    export default {
        setup() {
            const authStore = useAuthStore()

            return {
                authStore
            }
        },
    }
</script>