import { defineStore } from 'pinia'
import { computed, reactive, watchEffect, toRefs } from 'vue'
import * as reportApi from '@/api/report'
import { updateArrayByProperty } from '@/plugins/helpers'
import useFilters from '../plugins/filter'
import { useAuthStore } from '@/stores/auth'

const reportFilters = useFilters()
const authStore = useAuthStore()

export const useReportStore = defineStore('report', () => {
    const reports = reactive(reportsObject)
    const dialog = reactive(dialogObject)
    const filter = getFilterObject()
    const computed_reports = computed(() => reportFilters.applyFilter(reports.data, filter.filterable, filter.filters))

    watchEffect(() => { filter.updateFilters(reports.data) })

    async function fetchReports() {
        reports.init()
        const res = await reportApi.index()
        res.status ? reports.success(res.data) : reports.error(res.data)
    }

    async function generateReport(data) {
        dialog.init()
        const res = await reportApi.generate(data)
        if(res.status) {
            reports.insert(res.data)
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function deleteReport(id) {
        reports.init()
        const res = await reportApi.destroy(id)
        if(res.status) {
            reports.delete(id)
        } else {
            reports.error(res.data)
        }
    }
  
    return {
        ...toRefs(reports),
        ...toRefs(filter),
        ...toRefs(dialog),
        computed_reports,
        headers,
        fetchReports,
        generateReport,
        deleteReport
    }
})

const headers = computed(() => {
    if(authStore.user.role == "USER") {
        return [
            {text: "Month/Year", value: "month"},
            {text: "Generated", value: "generated_by_name"},
            {text: "Actions", value: "actions"},
        ]
    } else {
        return [
            {text: "Ward/Unit", value: "location_name"},
            {text: "Month/Year", value: "month"},
            {text: "Generated", value: "generated_by_name"},
            {text: "Actions", value: "actions"},
        ]
    }
})

const reportsObject = {
    data: [],
    reports_loading: false,
    reports_error: null,
    selected_report: null,
    init: function () {
        this.selected_report = null
        this.reports_loading = true
        this.reports_error = null
    },
    error: function(err) {
        this.reports_loading = false
        this.reports_error = err
    },
    success: function(data) {
        this.reports_loading = false
        this.data = data
    },
    update: function(id, data) {
        this.selected_report = null
        this.success([...updateArrayByProperty(this.data, 'id', id, data)])
    },
    insert: function(data) {
        const item = this.data.find(i => i.id == data.id)
        if(item) {
            this.update(data.id, data)
        } else {
            this.data.unshift(data)
            this.selected_report = null
        }
    },
    delete: function(id) {
        const index = this.data.findIndex(m => m.id == id)
        this.data.splice(index, 1)
        this.reports_loading = false
    }
}

const dialogObject = {
    report_dialog: false,
    dialog_loading: false,
    dialog_error: null,
    init: function () {
        this.dialog_loading = true
        this.dialog_error = null
    },
    error: function(err) {
        this.dialog_loading = false
        this.dialog_error = err
    },
    success: function() {
        this.dialog_loading = false
        this.report_dialog = false
    }
}

function getFilterObject() {
    const ownFilterObject = Object.assign({}, reportFilters.filtersObject)
    ownFilterObject.addFilterable({text: 'Month', value: 'month', type: 'distinct'})
    ownFilterObject.addFilterable({text: 'Year', value: 'year', type: 'distinct'})
    if(authStore.user.role != "USER") {
        ownFilterObject.addFilterable({text: 'Ward/Unit', value: 'location_name', type: 'distinct'})
    }
    return reactive(Object.assign({}, ownFilterObject))
}