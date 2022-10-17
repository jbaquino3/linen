<template>
    <div>
        <v-alert v-if="locations_error" type="error" text class="mb-2">
            {{locations_error}}
        </v-alert>
        
        <v-data-table :headers="headers" :items="computed_locations" :search="search" :disabled="locations_loading" :loading="locations_loading">
            <template v-slot:[`item.type`]="{ item }">
                <v-chip label dark class="mr-2" :color="item.type == 'WARD' ? 'green' : 'blue'">{{item.type}}</v-chip>
            </template>

            <template v-slot:[`item.name`]="{ item }">
                <div :class="($vuetify.theme.dark ? 'yellow--text' : ' font-weight-medium') + ' title'">
                    {{item.name}}
                </div>
            </template>

            <template v-slot:[`item.actions`]="{ item }">
                <div class="d-flex">
                    <div class="mr-1">
                        <table-edit-button @click="openEdit(item)"></table-edit-button>
                    </div>
                    <div class="mr-1">
                        <table-delete-button @delete="destroy(item)"></table-delete-button>
                    </div>
                </div>
            </template>
        </v-data-table>
    </div>
</template>

<script>
    import { onMounted, ref } from 'vue'
    import { useLocationStore } from '@/stores/location'
    import { storeToRefs } from 'pinia'

    export default {
        setup() {
            const locationStore = useLocationStore()
            const {
                computed_locations,
                locations_loading,
                location_dialog,
                locations_error,
                selected_location
            } = storeToRefs(locationStore)
            const search = ref("")

            onMounted(() => {
                locationStore.fetchLocations()
            })

            function openEdit(item) {
                selected_location.value = Object.assign({}, item)
                location_dialog.value = true
            }

            function destroy(item) {
                locationStore.deleteLocation(item.id)
            }

            return {
                computed_locations,
                locations_loading,
                locations_error,
                headers,
                search,
                openEdit,
                destroy
            }
        },
    }

    const headers = [
        {text: "Type", value: "type"},
        {text: "Name", value: "name"},
        {text: "Actions", value: "actions"},
    ]
</script>