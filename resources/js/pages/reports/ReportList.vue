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

                <template v-slot:[`item.actions`]="{  }">
                    <div class="d-flex">
                        
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
    import { mdiPlus, mdiAccount, mdiCalendar } from '@mdi/js'

    export default {
        setup() {
            const reportStore = useReportStore()
            const {
                computed_reports,
                reports_loading,
                reports_error,
                selected_report,
                filters,
                filterable
            } = storeToRefs(reportStore)
            const search = ref("")

            onMounted(() => {
                reload()
            })

            function reload() {
                reportStore.fetchReports()
            }

            function destroy(item) {
                reportStore.deleteReport(item.id)
            }

            return {
                computed_reports,
                reports_loading,
                reports_error,
                headers,
                search,
                filters,
                filterable,
                destroy,
                reload,
                ...icons
            }
        },
    }

    const icons = {
        mdiPlus, mdiAccount, mdiCalendar
    }

    const headers = [
        {text: "Ward/Unit", value: "location_name"},
        {text: "Month/Year", value: "month"},
        {text: "Generated", value: "generated_by_name"},
        {text: "Actions", value: "actions"},
    ]
</script>