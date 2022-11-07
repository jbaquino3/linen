<template>
    <div>
        <table-filters
            :filterable="filterable"
            :actions="[
                {text: 'Generate Report', color: 'primary', emit: 'add', icon: mdiPlus}
            ]"
            v-model="filters"
            @search="s => search=s"
            @reload="reload"
            @add="generateReport"
        ></table-filters>

        <v-alert v-if="reports_error" type="error" text class="mt-2">
            {{reports_error}}
        </v-alert>
        
        <v-card class="mt-2" flat>
            <v-data-table :headers="headers" :items="computed_reports" :search="search" :disabled="reports_loading" :loading="reports_loading">
                <template v-slot:[`item.type`]="{ item }">
                    <v-chip label dark class="mr-2" :color="item.type == 'WARD' ? 'green' : 'blue'">{{item.type}}</v-chip>
                </template>

                <template v-slot:[`item.location_name`]="{ item }">
                    <div :class="($vuetify.theme.dark ? 'yellow--text' : ' font-weight-medium') + ' font-italic'">
                        {{item.location_name}}
                    </div>
                </template>

                <template v-slot:[`item.month`]="{ item }">
                    <div :class="($vuetify.theme.dark ? 'white--text' : ' font-weight-medium') + ' title'">
                        {{item.month}} {{item.year}}
                    </div>
                </template>

                <template v-slot:[`item.generated_by_name`]="{ item }">
                    <div class="d-flex flex-column">
                        <div :class="($vuetify.theme.dark ? 'white--text' : ' grey--text') + ' caption font-italic'">
                            <v-icon x-small>{{mdiAccount}}</v-icon>
                            {{item.generated_by_name}}
                        </div>
                        <div :class="($vuetify.theme.dark ? 'white--text' : ' grey--text') + ' caption font-italic'">
                            <v-icon x-small>{{mdiCalendar}}</v-icon>
                            {{item.created_at}}
                        </div>
                    </div>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <div class="d-flex">
                        <v-btn small dark color="primary" @click="view(item)">
                            <v-icon left>{{mdiPrinter}}</v-icon>
                            Print
                        </v-btn>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
    import { onMounted, ref } from 'vue'
    import { useReportStore } from '@/stores/report'
    import { storeToRefs } from 'pinia'
    import { mdiPlus, mdiAccount, mdiCalendar, mdiPrinter } from '@mdi/js'
    import { useRouter } from '@/plugins/UseRouter'

    export default {
        setup() {
            const reportStore = useReportStore()
            const {
                computed_reports,
                report_dialog,
                reports_loading,
                reports_error,
                selected_report,
                filters,
                headers,
                filterable
            } = storeToRefs(reportStore)
            const search = ref("")
            const router = useRouter()

            onMounted(() => {
                reload()
            })

            function generateReport() {
                let date = new Date()
                date.setMonth(date.getMonth()-1)

                selected_report.value = {
                    month: date.toLocaleString('default', { month: 'long' }),
                    year: date.getFullYear()
                }

                report_dialog.value = true
            }

            function reload() {
                reportStore.fetchReports()
            }

            function view(item) {
                selected_report.value = item
                router.push("/auth/reports/view")
            }

            function destroy(item) {
                reportStore.deleteReport(item.id)
            }

            return {
                computed_reports,
                reports_loading,
                reports_error,
                generateReport,
                headers,
                search,
                filters,
                filterable,
                destroy,
                reload,
                view,
                ...icons
            }
        },
    }

    const icons = {
        mdiPlus, mdiAccount, mdiCalendar, mdiPrinter
    }
</script>