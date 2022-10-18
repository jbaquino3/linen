<template>
    <div>
        <v-dialog v-model="location_dialog" persistent max-width="400">
            <v-card :loading="dialog_loading" :disabled="dialog_loading">
                <v-card-title>
                    <v-container class="d-flex" fluid>
                        {{selected_location ? "Update Location" : "Create Location"}}
                        <v-spacer></v-spacer>
                        <v-icon @click="closeDialog">{{mdiClose}}</v-icon>
                    </v-container>
                </v-card-title>

                <v-card-text>
                    <v-container fluid>
                        <v-alert v-if="dialog_error" type="error" text class="mb-2">
                            {{dialog_error}}
                        </v-alert>

                        <v-row>
                            <v-col cols="12">
                                <v-select
                                    label="Type"
                                    v-model="location.type"
                                    :items="['WARD', 'OFFICE']"
                                ></v-select>
                            </v-col>

                            <v-col cols="12">
                                <v-text-field
                                    label="Name"
                                    v-model="location.name"
                                    @input="location.name = location.name.toUpperCase()"
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-btn dark color="primary" @click="save">
                            Save
                        </v-btn>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import { useLocationStore } from '@/stores/location'
    import { useStorageStore } from '@/stores/storage'
    import { storeToRefs } from 'pinia'
    import { mdiClose } from '@mdi/js'
    import { ref, watch, onMounted } from 'vue'

    export default {
        setup() {
            const locationStore = useLocationStore()
            const { location_dialog, selected_location, dialog_loading, dialog_error } = storeToRefs(locationStore)
            const { storage_select_items } = storeToRefs(useStorageStore())
            const location = ref({})
            const date_menu = ref(false)

            function closeDialog() {
                location_dialog.value = false
                selected_location.value = null
            }

            function save() {
                if(selected_location.value) {
                    locationStore.updateLocation(location.value, location.value.id)
                } else {
                    locationStore.createLocation(location.value)
                }
            }

            function assignLocation() {
                location.value = Object.assign({}, selected_location.value)
            }

            onMounted(() => {
                assignLocation()
            })

            watch(selected_location, (currentValue) => {
                assignLocation()
            })

            return {
                location_dialog,
                location,
                dialog_loading,
                dialog_error,
                selected_location,
                date_menu,
                storage_select_items,
                closeDialog,
                save,
                ...icons
            }
        },
    }

    const icons = {
        mdiClose
    }
</script>