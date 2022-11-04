<template>
    <div>
        <v-dialog v-model="request_dialog" persistent max-width="600">
            <v-card :loading="dialog_loading" :disabled="dialog_loading">
                <v-card-title>
                    <v-container class="d-flex" fluid>
                        {{selected_request ? "Update Request" : "Create Request"}}
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
                                <v-textarea
                                    outlined
                                    label="Description/Name of Product"
                                    v-model="request.name"
                                ></v-textarea>
                            </v-col>

                            <v-col cols="6">
                                <v-text-field
                                    outlined
                                    label="Quantity"
                                    v-model="request.quantity"
                                    type="number"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="6">
                                <v-select
                                    outlined
                                    label="Unit"
                                    v-model="request.unit"
                                    :items="['PIECE', 'SPOOL', 'YARD', 'ROLL', 'SACK/BAG']"
                                ></v-select>
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
    import { useRequestStore } from '@/stores/request'
    import { useStorageStore } from '@/stores/storage'
    import { storeToRefs } from 'pinia'
    import { mdiClose } from '@mdi/js'
    import { ref, watch, onMounted } from 'vue'

    export default {
        setup() {
            const requestStore = useRequestStore()
            const { request_dialog, selected_request, dialog_loading, dialog_error } = storeToRefs(requestStore)
            const { storage_select_items } = storeToRefs(useStorageStore())
            const request = ref({})
            const date_menu = ref(false)

            function closeDialog() {
                request_dialog.value = false
                selected_request.value = null
            }

            async function save() {
                if(selected_request.value) {
                    await requestStore.updateRequest(request.value, request.value.id)
                } else {
                    await requestStore.createRequest(request.value)
                }

                if(!dialog_error.value) {
                    request.value = {}
                }
            }

            function assignRequest() {
                request.value = Object.assign({}, selected_request.value)
            }

            onMounted(() => {
                assignRequest()
            })

            watch(selected_request, (currentValue) => {
                assignRequest()
            })

            return {
                request_dialog,
                request,
                dialog_loading,
                dialog_error,
                selected_request,
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