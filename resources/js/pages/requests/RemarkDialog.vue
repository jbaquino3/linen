<template>
    <div>
        <v-dialog v-model="remark_dialog" persistent max-width="600">
            <v-card :loading="remark_loading" :disabled="remark_loading">
                <v-card-title>
                    <v-container class="d-flex" fluid>
                        Remarks
                        <v-spacer></v-spacer>
                        <v-icon @click="closeDialog">{{mdiClose}}</v-icon>
                    </v-container>
                </v-card-title>

                <v-card-text>
                    <v-container fluid>
                        <v-alert v-if="remark_error" type="error" text class="mb-2">
                            {{remark_error}}
                        </v-alert>

                        <v-row>
                            <v-col cols="12">
                                <v-textarea
                                    outlined
                                    v-model="remark.remarks"
                                ></v-textarea>
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
    import { storeToRefs } from 'pinia'
    import { mdiClose } from '@mdi/js'
    import { ref } from 'vue'

    export default {
        setup() {
            const requestStore = useRequestStore()
            const { remark_dialog, selected_request, remark_loading, remark_error } = storeToRefs(requestStore)
            const remark = ref({})

            function closeDialog() {
                remark_dialog.value = false
                selected_request.value = null
            }

            async function save() {
                const res = await requestStore.createRemark({
                    request_id: selected_request.value.id,
                    remarks: remark.value.remarks
                })

                if(res.status) {
                    remark.value = {}
                }
            }

            return {
                remark_dialog,
                remark,
                remark_loading,
                remark_error,
                selected_request,
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