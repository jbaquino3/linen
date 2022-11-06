import { defineStore } from 'pinia'
import { computed, reactive, watchEffect, toRefs } from 'vue'
import * as reportApi from '@/api/report'
import { updateArrayByProperty } from '@/plugins/helpers'
import useFilters from '../plugins/filter'

const reportFilters = useFilters()

export const useReportStore = defineStore('report', () => {
    const reports = reactive(reportsObject)
    const filter = getFilterObject()
    const computed_reports = computed(() => reportFilters.applyFilter(reports.data, filter.filterable, filter.filters))

    watchEffect(() => { filter.updateFilters(reports.data) })

    async function fetchReports() {
        reports.init()
        const res = await reportApi.index()
        res.status ? reports.success(res.data) : reports.error(res.data)
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
        computed_reports,
        fetchReports,
        deleteReport
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
        this.data.unshift(data)
        this.selected_report = null
    },
    delete: function(id) {
        const index = this.data.findIndex(m => m.id == id)
        this.data.splice(index, 1)
        this.reports_loading = false
    }
}

function getFilterObject() {
    const ownFilterObject = Object.assign({}, reportFilters.filtersObject)
    ownFilterObject.addFilterable({text: 'Ward/Unit', value: 'location_name', type: 'distinct'})
    ownFilterObject.addFilterable({text: 'Month', value: 'month', type: 'distinct'})
    ownFilterObject.addFilterable({text: 'Year', value: 'year', type: 'distinct'})
    return reactive(Object.assign({}, ownFilterObject))
}