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

        <v-row class="mt-2" >
            <v-col :cols="selected_location ? '8' : '12'">
                <v-card flat>
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
                                <!-- <div class="mr-1">
                                    <v-btn :dark="item.transaction_count > 0" :disabled="item.transaction_count == 0" small color="success" @click="view_issued(item)">
                                        View Issued Products
                                    </v-btn>
                                </div> -->
                            </div>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
            <v-col cols="4" v-if="selected_location">
                <v-card flat>
                    <v-card-title class="subtitle-1">
                        Products Issued to {{selected_location.name}}
                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
        
        
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

            function view_issued(item) {
                selected_location.value = Object.assign({}, item)
            }

            return {
                computed_locations,
                locations_loading,
                locations_error,
                location_dialog,
                selected_location,
                headers,
                search,
                filters,
                filterable,
                openEdit,
                destroy,
                reload,
                view_issued,
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