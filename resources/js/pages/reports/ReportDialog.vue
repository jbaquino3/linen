<template>
    <div>
        <v-dialog v-model="report_dialog" persistent max-width="600">
            <v-card :loading="dialog_loading" :disabled="dialog_loading">
                <v-card-title>
                    <v-container class="d-flex" fluid>
                        Generate Report
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
                                    label="Ward/Unit"
                                    v-model="report.location_id"
                                    :items="location_select_items"
                                ></v-select>
                            </v-col>

                            <v-col cols="8">
                                <v-select
                                    label="Month"
                                    v-model="report.month"
                                    :items="['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']"
                                ></v-select>
                            </v-col>

                            <v-col cols="4">
                                <v-text-field
                                    label="Year"
                                    v-model="report.year"
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-btn dark color="primary" @click="save">
                            Generate
                        </v-btn>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import { useReportStore } from '@/stores/report'
    import { useLocationStore } from '@/stores/location'
    import { storeToRefs } from 'pinia'
    import { mdiClose } from '@mdi/js'
    import { ref, watchEffect } from 'vue'

    export default {
        setup() {
            const reportStore = useReportStore()
            const { report_dialog, selected_report, dialog_loading, dialog_error } = storeToRefs(reportStore)
            const { location_select_items } = storeToRefs(useLocationStore())
            const report = ref({})

            function closeDialog() {
                report_dialog.value = false
                selected_report.value = null
            }

            function save() {
                reportStore.generateReport(report.value)
            }

            function assignReport() {
                report.value = Object.assign({}, selected_report.value)
            }

            watchEffect(() => {
                assignReport()
            })

            return {
                report_dialog,
                report,
                dialog_loading,
                dialog_error,
                selected_report,
                location_select_items,
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