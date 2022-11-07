<template>
    <div class="display-1 d-flex font-italic">
        <v-icon x-large color="green">{{mdiOfficeBuilding}}</v-icon>
        {{authStore.user.location_name}} ({{authStore.user.location_type}})
    </div>
</template>

<script>
    import { mdiOfficeBuilding } from '@mdi/js'
    import { useAuthStore } from '@/stores/auth'

    export default {
        setup() {
            const authStore = useAuthStore()
            
            return {
                authStore,
                ...icons
            }
        },
    }

    const icons = {
        mdiOfficeBuilding
    }
</script>