<template>
    <div>
        <table-filters
            :filterable="filterable"
            :actions="[
                {text: 'Add Ward/Office', color: 'primary', emit: 'add', icon: mdiPlus}
            ]"
            v-model="filters"
            @search="s => search=s"
            @reload="reload"
            @add="location_dialog=true"
        ></table-filters>

        <v-alert v-if="locations_error" type="error" text class="mt-2">
            {{locations_error}}
        </v-alert>
        
        <v-card class="mt-2" flat>
            <v-data-table :headers="headers" :items="computed_locations" :search="search" :disabled="locations_loading" :loading="locations_loading">
                <template v-slot:[`item.type`]="{ item }">
                    <v-chip label dark class="mr-2" :color="item.type == 'WARD' ? 'green' : 'blue'">{{item.type}}</v-chip>
                </template>

                <template v-slot:[`item.name`]="{ item }">
                    <div :class="($vuetify.theme.dark ? 'yellow--text' : ' font-weight-medium') + ' subtitle-1 font-italic'">
                        {{item.name.toUpperCase()}}
                    </div>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <div class="d-flex">
                        <div class="mr-1">
                            <table-edit-button @click="openEdit(item)"></table-edit-button>
                        </div>
                        <div class="mr-1">
                            <table-delete-button :disabled="item.transaction_count > 0" @delete="destroy(item)"></table-delete-button>
                        </div>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
    import { onMounted, ref } from 'vue'
    import { useLocationStore } from '@/stores/location'
    import { storeToRefs } from 'pinia'
    import { mdiPlus } from '@mdi/js'

    export default {
        setup() {
            const locationStore = useLocationStore()
            const {
                computed_locations,
                locations_loading,
                location_dialog,
                locations_error,
                selected_location,
                filters,
                filterable
            } = storeToRefs(locationStore)
            const search = ref("")

            onMounted(() => {
                reload()
            })

            function reload() {
                locationStore.fetchLocations()
            }

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
                location_dialog,
                headers,
                search,
                filters,
                filterable,
                openEdit,
                destroy,
                reload,
                ...icons
            }
        },
    }

    const icons = {
        mdiPlus
    }

    const headers = [
        {text: "Type", value: "type"},
        {text: "Name", value: "name"},
        {text: "Actions", value: "actions"},
    ]
</script>